<?php
require '../../classes/session.php';
require '../../classes/meeting.php';

Session::check_login_redirect();
$errors = [];

$form = $_POST['form'];
$id_meeting = $_POST['id'];
$file_saved = $_POST['file_saved'] ?? 'NULL';

$fileTmpPath = $_FILES['fimagen']['tmp_name'];
$fileName = $_FILES['fimagen']['name'];
$fileSize = $_FILES['fimagen']['size'];
$fileType = $_FILES['fimagen']['type'];
$uploadFileDir = '../../files/meeting/';
$destPath = $uploadFileDir . $fileName;

// errors form are controled
if ($fileName == "" && $form != "delete") {
	header('Location: ./index.php?action=not_attachment');
	exit();
}

try {
	if ($form == "Crear" || $form == "Editar") {
		$success = Meeting::modifify_act($id_meeting, $fileName);
	} else {
		$success = Meeting::modifify_act($id_meeting, "");
	}
} catch(InvalidArgumentException $e) {
	print("catch");
	$errors[] = "incorrect_camp";
	$message = $e->getMessage();
	$error = implode(',', $errors);
	header('Location: ./edit_create_minutes.php?success=false&error='.$error.'&message='.$message);
	exit();
}
if (!$errors) {
	if ($success) {
		if (!file_exists($uploadFileDir)) {
			mkdir($uploadFileDir, 0777, true);
		}
		if(!move_uploaded_file($fileTmpPath, $destPath)) {
			$message = 'Act is not saved';
			header('Location: ./edit_create_minutes.php?action=create_meeting&message='.$message);
		}
		if ($form == "Crear") {
			header('Location: ./index.php?action=create_act');
		} else if ($form == "Editar") {
			header('Location: ./index.php?action=update_act');
		} else {
			header('Location: ./index.php?action=delete_act');
		}

	} else if ($form == "Editar") {
		header('Location: ./edit_create_minutes.php?success='.($success === false ? 'false' : 'true'));
	} else {
			header('Location: ./index.php?action=delete_act');
	}
}
