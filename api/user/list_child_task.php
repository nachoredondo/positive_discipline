<?php
require_once("../../classes/controller.php");
require_once("../../classes/ssp.php");

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

class SSPChilds extends SSP {
	private const FROM = " `users` as user
		INNER JOIN `tutors` as tutor
			ON `user`.`id` = `tutor`.`child`";

	public static function exec ($request, $conn) {
		// Datatbles columns - ADD THEM HERE
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes
		$DT_COLUMNS = [
			[ 'db' => 'id', 'dt' => 'id' ],
			[ 'db' => 'name', 'dt' => 'name' ],
			[ 'db' => 'user', 'dt' => 'user' ],
			[ 'db' => 'password', 'dt' => 'password' ],
			[ 'db' => 'image', 'dt' => 'image' ],
			[ 'db' => 'age', 'dt' => 'age' ],
			[ 'db' => 'id_task', 'dt' => 'id_task' ],
			[ 'db' => 'position', 'dt' => 'position' ],
			[ 'db' => 'number_child', 'dt' => 'number_child' ]
		];
		return self::do_search($request, $conn, $DT_COLUMNS);
	}

	private static function do_search($request, $conn, $columns) {
		$bindings = [];
		$bindings_join = [];
		$db = self::db($conn);

		$request['search']['value'] = $request['search']['value'];
		$from = self::task_children(self::FROM, $request, $bindings);
		$from_join = self::task_children(self::FROM, $request, $bindings_join);
		$from = self::count_children($from, $request, $bindings);
		$from_join = self::count_children($from_join, $request, $bindings_join);

		// Build the SQL query string from the request
		$order = self::order($request, $columns);
		$where = self::filter($request, $columns, $bindings);

		$where = self::where_add($where, self::where_user($request, $bindings));
		// $where = self::where_add($where, self::where_task($request, $bindings));
		$where_join = "";
		$where_join = self::where_add($where_join, self::where_user($request, $bindings_join));
		// $where_join = self::where_add($where_join, self::where_task($request, $bindings_join));

		// Main query to actually get the data
		$sql = "SELECT `user`.*,
			 	id_task,
			 	position,
			 	number_child
			 FROM $from_join
			 $where
			 GROUP BY `user`.`id`
			 $order";
		// print($sql);
		// var_dump($bindings);
		// exit();

		$data = self::sql_exec($db, $bindings,$sql);


		// Total data set length
		$resTotalLength = self::sql_exec($db, $bindings_join,
			"SELECT COUNT(user.id)
			 FROM $from_join
			 $where_join"
		);
		$recordsTotal = $resTotalLength[0][0];
		// Data set length after filtering
		$recordsFiltered = $recordsTotal;
		if (!empty($where_join)) {
			$resFilterLength = self::sql_exec($db, $bindings_join,
				"SELECT COUNT(`user`.id)
				 FROM $from_join
				 $where_join
				 GROUP BY `user`.`id`"
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

	private static function where_user($request, &$bindings) {
		$binding = self::bind($bindings, $request['id_user'], PDO::PARAM_STR);
		return "`tutor`.`parent` = $binding";
	}

	private static function task_children($from, $request, &$bindings) {
		$binding = self::bind($bindings, $request['id_task'], PDO::PARAM_STR);
		return $from . "LEFT JOIN task_children as task_child
								ON task_child.`id_user` = user.`id`
										AND task_child.`id_task` = $binding";
	}

	private static function count_children($from, $request, &$bindings) {
		$binding = self::bind($bindings, $request['id_task'], PDO::PARAM_STR);
		return $from . " INNER JOIN (SELECT count(id) as number_child
                			FROM task_children
               				WHERE `id_task` = $binding)
                		top_child";
	}

}



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

echo json_encode(
	SSPChilds::exec($_POST, get_db_connection())
);
