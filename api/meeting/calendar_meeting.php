<?php
require("../../classes/meeting.php");

$id = $_POST['id'] ?? null;

if (!$id) {
	http_response_code(400);
	exit(1);
}

$meeting_obj = new Meeting();
$meeting = $meeting_obj->get_meeting_by_id($_POST['id']);

echo json_encode($meeting, JSON_UNESCAPED_UNICODE);

?>
