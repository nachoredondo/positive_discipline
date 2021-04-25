<?php
require("../../classes/rule.php");
require '../../classes/session.php';
require '../../classes/meeting.php';

Session::check_login_redirect();
$errors = [];

$form = $_POST['form'];
$id_meeting = $_POST['id'];
$id_tutor = $_POST['id_tutor'];
$title = $_POST['title'];
$description = $_POST['description'];
$topics = $_POST['topics'];
$date = $_POST['date'];
$date_start = $_POST['date_start'];
$date_end = $_POST['date_end'];
$responsable_act = $_POST['responsable_act'];

// errors form are controled
if ($title == "") {
	if ($form == "Crear") {
		header('Location: ./edit_create.php?message=not_tittle');
		exit();
	} else if ("Editar") {
		header('Location: ./edit_create.php?id='.$id_meeting.'&message=not_title');
		exit();
	}
}
if ($date == "") {
	if ($form == "Crear") {
		header('Location: ./edit_create.php?message=not_date');
		exit();
	} else if ("Editar") {
		header('Location: ./edit_create.php?id='.$id_meeting.'&message=not_date');
		exit();
	}
}

try {
	if ($form == "Crear") {
		$success = Meeting::insert_meeting($id_tutor, $title, $description, $topics, $date, $date_start, $date_end, $responsable_act);
	} else if ($form == "Editar") {
		$success = Meeting::update_meeting($id_meeting, $title, $description, $topics, $date, $date_start, $date_end, $responsable_act);
	} else {
		$success = Meeting::delete_meeting($id_meeting);
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
		if ($form == "Crear") {
			header('Location: ./index.php?action=create_meeting');
		} else {
			header('Location: ./index.php?action=update_meeting');
		}

	} else if ($form == "Editar") {
		header('Location: ./edit_create.php?success='.($success === false ? 'false' : 'true'));
	} else {
			header('Location: ./index.php?action=delete_meeting');
	}
}
