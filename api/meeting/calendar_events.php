<?php
require_once("../../classes/session.php");
require_once("../../classes/controller.php");
Session::check_login_redirect();

function get_db_connection($dbname = null) {
	$conn = Controller::get_global_connection();
	return $conn;
}

function is_valid_date(?string $str = '', ?string $fmt = 'Y-m-d') {
	if (strlen($str) > 10) // Remove time part in case it has it
		$str = substr($str, 0, 10);
	return DateTime::createFromFormat($fmt, $str) ? $str : false;
}

function query($sql, ...$vars) {
	$conn = get_db_connection();
	$res = $conn->prepare($sql);
	$res->execute();
	return $res;
}

$date_start = is_valid_date($_POST['start'] ?? null);
$date_end = is_valid_date($_POST['end'] ?? null);

if (!$date_start || !$date_end) {
	http_response_code(400);
	exit(1);
}

$sql = "SELECT `id`, `title`, `date` as `start`
	FROM `meeting`
	WHERE `date` BETWEEN '$date_start' AND '$date_end'";

$result = query($sql);

$events = [];
if ($result) {
	$events = $result->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode($events, JSON_UNESCAPED_UNICODE);

?>
