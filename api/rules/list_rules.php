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

class SSPRules extends SSP {
	private const FROM = "rules";

	public static function exec ($request, $conn) {
		// Datatbles columns - ADD THEM HERE
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes
		$DT_COLUMNS = [
			[ 'db' => 'id', 'dt' => 'id' ],
			[ 'db' => 'id_user', 'dt' => 'id_user' ],
			[ 'db' => 'title', 'dt' => 'title' ],
			[ 'db' => 'description', 'dt' => 'description' ],
			[ 'db' => 'consequences', 'dt' => 'consequences' ],
			[ 'db' => 'img_consequences', 'dt' => 'img_consequences' ],
		];
		return self::do_search($request, $conn, $DT_COLUMNS);
	}

	private static function do_search($request, $conn, $columns) {
		$bindings = [];
		$db = self::db($conn);

		$request['search']['value'] = $request['search']['value'];

		// Build the SQL query string from the request
		$limit = self::limit($request, $columns);
		$order = self::order($request, $columns);
		$where = self::filter($request, $columns, $bindings);

		// Main query to actually get the data
		$sql = "SELECT *
			 FROM `".self::FROM."`
			 $where
			 $order
			 $limit";

		$data = self::sql_exec($db, $bindings,
			"SELECT *
			 FROM ".self::FROM."
			 $where
			 $order
			 $limit"
		);

		// Total data set length
		$resTotalLength = self::sql_exec($db,
			"SELECT COUNT(id)
			 FROM ".self::FROM
		);
		$recordsTotal = $resTotalLength[0][0];
		// Data set length after filtering
		$recordsFiltered = $recordsTotal;
		if (!empty($where)) {
			$resFilterLength = self::sql_exec($db, $bindings,
				"SELECT COUNT(id)
				 FROM ".self::FROM."
				 $where"
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

}


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

echo json_encode(
	SSPRules::exec($_POST, get_db_connection())
);
