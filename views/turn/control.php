<?php
require("../../classes/task.php");
require '../../classes/session.php';

Session::check_login_redirect();
$errors = [];

$form = $_POST['form'];
$id = $_POST['id'] ?? 'NULL';
$id_educator = $_POST['id_educator'] ?? 'NULL';
$id_user_child = $_POST['id_user_child'] ?? 'NULL';
if ($form == "Crear" and $id_user_child == 'NULL') {
	header('Location: ./edit_create.php?success=false&error=no-child');
	exit();
} else if ($form == "Editar" and $id_user_child == 'NULL') {
	header('Location: ./edit_create.php?id='.$id.'&success=false&error=no-child');
	exit();
}
$id_child_position = $_POST['id_child_position'] ?? 'NULL';

$name = $_POST['name'] ?? 'NULL';
$description = $_POST['description'] ?? 'NULL';
$date_start = $_POST['date_start'] ?? 'NULL';
$date_end = $_POST['date_end'] ?? 'NULL';
$date_modification = $_POST['date_modification'] ?? 'NULL';
$time_start = $_POST['time_start'] ?? 'NULL';
$time_end = $_POST['time_end'] ?? 'NULL';

$frecuency['daily'] = isset($_POST['daily']) ? '1' : '0';
$frecuency['weekly'] = isset($_POST['weekly']) ? '1' : '0';
$frecuency['monthly'] = isset($_POST['monthly']) ? '1' : '0';
$frecuency['monday'] = isset($_POST['monday']) ? '1' : '0';
$frecuency['thursday'] = isset($_POST['thursday']) ? '1' : '0';
$frecuency['wenesday'] = isset($_POST['wenesday']) ? '1' : '0';
$frecuency['tuesday'] = isset($_POST['tuesday']) ? '1' : '0';
$frecuency['friday'] = isset($_POST['friday']) ? '1' : '0';
$frecuency['saturday'] = isset($_POST['saturday']) ? '1' : '0';
$frecuency['sunday'] = isset($_POST['sunday']) ? '1' : '0';

try {
	if ($form == "Crear") {
		$success = Task::insert_task($id_educator, $name, $description, $date_start, $date_end, $date_modification, $time_start, $time_end, $frecuency, $id_user_child, $id_child_position);
	} else if ($form == "Editar") {
		$success = Task::update_task($id, $id_educator, $name, $description, $date_start, $date_end, $date_modification, $time_start, $time_end, $frecuency, $id_user_child, $id_child_position);
	} else {
		$success = Task::delete_task($id);
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
			header('Location: ./index.php?action=create_option');
		} else if ($form == "Editar") {
			header('Location: ./index.php?action=update_task');
		} else {
			header('Location: ./index.php?action=delete_task');
		}
	} else {
		header('Location: ./index.php?success='.($success === false ? 'false' : 'true'));
	}
}
