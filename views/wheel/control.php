<?php
require("../../classes/option_wheel.php");
require '../../classes/session.php';

Session::check_login_redirect();
$errors = [];

$form = $_POST['form'];
$id = $_POST['id'] ?? 'NULL';
$img_saved = $_POST['img_saved'] ?? 'NULL';
$id_user = $_POST['id_user'] ?? 'NULL';
$name = $_POST['name'] ?? 'NULL';

$fileTmpPath = $_FILES['fimagen']['tmp_name'] ?? 'NULL';
$fileName = $_FILES['fimagen']['name'] ?? 'NULL';
$fileSize = $_FILES['fimagen']['size'] ?? 'NULL';
$fileType = $_FILES['fimagen']['type'] ?? 'NULL';
$uploadFileDir = '../../files/img/wheel/';
$destPath = $uploadFileDir . $fileName;

// control errors
if ($form != "delete" && !$name) {
	header('Location: ./edit_create.php?message=Sin título');
	exit();
}
if (!$fileName && !$img_saved && $form != "delete"){
	header('Location: ./edit_create.php?message=Sin adjuntar imagen');
	exit();
}

if ($fileName == null) {
	$fileName = $img_saved;
}

try {
	if ($form == "Crear") {
		$success = Option_wheel::insert_option($id_user, $name, $fileName);
	} else if ($form == "Editar") {
		$success = Option_wheel::update_option($id, $name, $fileName);
	} else {
		$success = Option_wheel::delete_option($id);
	}
} catch(InvalidArgumentException $e) {
	$errors[] = "incorrect_camp";
	$message = $e->getMessage();
	$error = implode(',', $errors);
	header('Location: ./edit_create.php?success=false&error='.$error.'&message='.$message);
	exit();
}
if (!$errors) {
	if ($success) {
		if ($form == "Crear") {
			if ($fileName != null) {
				if (!file_exists($uploadFileDir)) {
					mkdir($uploadFileDir, 0777, true);
				}
				if(!move_uploaded_file($fileTmpPath, $destPath)) {
					$message = 'Image is not saved';
					header('Location: ./index.php?action=create_option&message='.$message);
				}
			}
			header('Location: ./index.php?action=create_option');
		} else if ($form == "Editar") {
			if ($fileName != null) {
				if (!file_exists($uploadFileDir)) {
					mkdir($uploadFileDir, 0777, true);
				}
				if(!move_uploaded_file($fileTmpPath, $destPath)) {
					$message = 'Image is not saved';
					header('Location: ./index.php?action=update_option&message='.$message);
				}
			}
			header('Location: ./index.php?action=update_option');
		} else {
			header('Location: ./index.php?action=delete_option');
		}

	} else {
		header('Location: ./index.php?success='.($success === false ? 'false' : 'true'));
	}
}
