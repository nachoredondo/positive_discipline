<?php
require("../../classes/rule.php");
require '../../classes/session.php';
require '../../classes/Meeting.php';

Session::check_login_redirect();
$errors = [];

$form = $_POST['form'];
$id_meeting = $_POST['id'];
$title = $_POST['title'];
if ($title == "") {
	if ($form == "Crear") {
		header('Location: ./edit_create.php?message=not_title');
		exit();
	} else {
		header('Location: ./edit_create.php?id='.$id_meeting.'&message=not_title');
		exit();
	}
}
$description = $_POST['description'];
$topics = $_POST['topics'];
$date = $_POST['date'];
$date_start = $_POST['date_start'];
$date_end = $_POST['date_end'];
$responsable_act = $_POST['responsable_act'];
$file_saved = $_POST['file_saved'] ?? 'NULL';

$fileTmpPath = $_FILES['fimagen']['tmp_name'];
$fileName = $_FILES['fimagen']['name'];
$fileSize = $_FILES['fimagen']['size'];
$fileType = $_FILES['fimagen']['type'];
$uploadFileDir = '../../files/meeting/';
$destPath = $uploadFileDir . $fileName;

if ($fileName == null) {
	$fileName = $file_saved;
}

try {
	if ($form == "Crear") {
		$success = Meeting::insert_meeting($title, $description, $topics, $date, $date_start, $date_end, $responsable_act, $fileName);
	} else {
		$success = Meeting::update_meeting($id_meeting, $title, $description, $topics, $date, $date_start, $date_end, $responsable_act, $fileName);
	}
} catch(InvalidArgumentException $e) {
	print("catch");
	$errors[] = "incorrect_camp";
	$message = $e->getMessage();
	$error = implode(',', $errors);
	header('Location: ./edit_create.php?success=false&error='.$error.'&message='.$message);
	exit();
}
if (!$errors) {
	if ($success) {
		if (!file_exists($uploadFileDir)) {
			mkdir($uploadFileDir, 0777, true);
		}
		if(!move_uploaded_file($fileTmpPath, $destPath)) {
			$message = 'Image is not saved';
			header('Location: ./edit_create.php?action=create_rule&message='.$message);
		}
		if ($form == "Crear") {
			header('Location: ./index.php?action=create_rule');
		} else {
			header('Location: ./index.php?action=update_rule');
		}

	} else {
		header('Location: ./edit_create.php?success='.($success === false ? 'false' : 'true'));
	}
}
