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

class SSPTasks extends SSP {
	private const FROM = "`task` as t
						LEFT JOIN (SELECT position, id_task, us.name as name
                    		FROM `task_children` as tc
                   				INNER JOIN `users` as us ON us.id = tc.id_user
                   			WHERE position = (SELECT MIN(position)
                                        		FROM `task_children`
                                        		WHERE `id_task` = tc.id_task)
                   		) first_child USING (id_task)
        				LEFT JOIN (SELECT position, id_task, us.name as name
                   			FROM `task_children` as tc
                   				INNER JOIN `users` as us ON us.id = tc.id_user
                   			WHERE position = (SELECT MAX(position)
                                        		FROM `task_children`
                                        		WHERE `id_task` = tc.id_task)
                   			) last_child USING (id_task) ";

	public static function exec ($request, $conn) {
		// Datatbles columns - ADD THEM HERE
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes
		$DT_COLUMNS = [
			[ 'db' => 't.id_task', 'dt' => 't_id_task' ],
			[ 'db' => 't.name', 'dt' => 't_name' ],
			[ 'db' => 'description', 'dt' => 'description' ],
			[ 'db' => 'date_modification', 'dt' => 'date_modification' ],
			[ 'db' => 'date_end', 'dt' => 'date_end' ],
			[ 'db' => 'daily', 'dt' => 'daily' ],
			[ 'db' => 'weekly', 'dt' => 'weekly' ],
			[ 'db' => 'monthly', 'dt' => 'monthly' ],
			[ 'db' => 'monday', 'dt' => 'monday' ],
			[ 'db' => 'thursday', 'dt' => 'thursday' ],
			[ 'db' => 'wenesday', 'dt' => 'wenesday' ],
			[ 'db' => 'tuesday', 'dt' => 'tuesday' ],
			[ 'db' => 'friday', 'dt' => 'friday' ],
			[ 'db' => 'saturday', 'dt' => 'saturday' ],
			[ 'db' => 'sunday', 'dt' => 'sunday' ],
			[ 'db' => 'first_child.name', 'dt' => 'first_child_name' ],
			[ 'db' => 'last_child.name', 'dt' => 'last_child_name' ],
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

		$group = " GROUP BY  t.`id_task` ";

		$from = self::FROM;

		$where = self::where_add($where, self::where_user($request['id_user'], $bindings));
		$where_join = self::where_add("", self::where_user($request['id_user'], $bindings_join));

		// Main query to actually get the data
		$sql = "SELECT t.`id_task` as 't.id_task',
					t.`name` as 't.name',
					description, date_modification, date_end, daily, weekly, monthly, monday, thursday, wenesday, tuesday, friday, saturday, sunday,
					first_child.`name` as 'first_child.name',
					last_child.`name` as 'last_child.name'
				FROM $from
				$where
				$group
				$order
				$limit";

		$data = self::sql_exec($db, $bindings, $sql);

		// Total data set length
		$resTotalLength = self::sql_exec($db, $bindings_join,
			"SELECT COUNT(`t`.`id_task`)
			 FROM $from
			 $where_join"
		);
		$recordsTotal = $resTotalLength[0][0];
		// Data set length after filtering
		$recordsFiltered = $recordsTotal;
		if (!empty($where)) {
			$resFilterLength = self::sql_exec($db, $bindings_join,
				"SELECT COUNT(`t`.`id_task`)
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
		return "`t`.`parent` = $binding";
	}
}


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

echo json_encode(
	SSPTasks::exec($_POST, get_db_connection())
);
