<?php
require_once("../../classes/controller.php");
require_once("../../classes/ssp.php");
require '../../classes/session.php';
require '../../classes/user.php';
Session::check_login_redirect();

// Session::check_login_error();

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

function get_db_connection($dbname = null) {
	$conn = Controller::get_global_connection();
	return $conn;
}

class SSPRules extends SSP {
	private const FROM = "`rules` as rule ";
	private const FROM_CHILD = "INNER JOIN `rules_children` as rule_children
								ON rule.id = rule_children.id_rule";

	public static function exec ($request, $conn) {
		// Datatbles columns - ADD THEM HERE
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes
		$DT_COLUMNS = [
			[ 'db' => 'id', 'dt' => 'id' ],
			[ 'db' => 'id_educator', 'dt' => 'id_educator' ],
			[ 'db' => 'title', 'dt' => 'title' ],
			[ 'db' => 'description', 'dt' => 'description' ],
			[ 'db' => 'consequences', 'dt' => 'consequences' ],
			[ 'db' => 'img_consequences', 'dt' => 'img_consequences' ],
		];
		return self::do_search($request, $conn, $DT_COLUMNS);
	}

	private static function do_search($request, $conn, $columns) {
		$bindings = [];
		$bindings_join = [];
		$db = self::db($conn);

		$request['search']['value'] = $request['search']['value'];

		// Build the SQL query string from the request
		$limit = self::limit($request, $columns);
		$order = self::order($request, $columns);
		$where = self::filter($request, $columns, $bindings);

		$from = self::FROM;

		if ($_SESSION['type']) {
			$id_parent = $request['id_user'];
		} else {
			$id_parent = User::get_parent($request['id_user']);
		}
		$where = self::where_add($where, self::where_user($id_parent, $bindings));
		$where_join = self::where_add("", self::where_user($id_parent, $bindings_join));
		if (!$_SESSION['type']) {
			$from .= self::FROM_CHILD;
			$where = self::where_add($where, self::where_user_child($request['id_user'], $bindings));
			$where_join = self::where_add($where_join, self::where_user_child($request['id_user'], $bindings_join));
		} else if ($request['child'] != 'all') {
			$from .= self::FROM_CHILD;
			$where = self::where_add($where, self::where_user_child($request['child'], $bindings));
			$where_join = self::where_add($where_join, self::where_user_child($request['child'], $bindings_join));
		}

		// Main query to actually get the data
		$sql = "SELECT *
			 FROM $from
			 $where
			 $order
			 $limit";

		$data = self::sql_exec($db, $bindings, $sql);

		// Total data set length
		$resTotalLength = self::sql_exec($db, $bindings_join,
			"SELECT COUNT(`rule`.`id`)
			 FROM $from
			 $where_join"
		);
		$recordsTotal = $resTotalLength[0][0];
		// Data set length after filtering
		$recordsFiltered = $recordsTotal;
		if (!empty($where)) {
			$resFilterLength = self::sql_exec($db, $bindings_join,
				"SELECT COUNT(`rule`.`id`)
				 FROM $from
				 $where_join"
			);
			$recordsFiltered = $resFilterLength[0][0];
		}
		/*
		 * Output
		 */
		return [
			"draw" => isset($request['draw']) ? intval($request['draw']) : 0,
			"recordsTotal" => intval($recordsTotal),
			"recordsFiltered" => intval($recordsFiltered),
			"data" => self::data_output($columns, $data),
		];
	}

	static function where_add(?string $where = '', string $cond, string $glue = ' AND ') : string {
		if (empty($cond))
			return $where;
		return $where ? $where . $glue . $cond : 'WHERE ' . $cond;
	}

	private static function where_user($id_parent, &$bindings) {
		$binding = self::bind($bindings, $id_parent, PDO::PARAM_STR);
		return "`rule`.`id_educator` = $binding";
	}

	private static function where_user_child($id_user, &$bindings) {
		$binding = self::bind($bindings, $id_user, PDO::PARAM_STR);
		return "`rule_children`.`id_user` = $binding";
	}
}


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

echo json_encode(
	SSPRules::exec($_POST, get_db_connection())
);
