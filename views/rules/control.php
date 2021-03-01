<?php
require("../../classes/rule.php");
require '../../classes/session.php';

Session::check_login_redirect();
$errors = [];

$form = $_POST['form'];
$id = $_POST['id'] ?? 'NULL';
$id_educator = $_POST['id_educator'] ?? 'NULL';
$id_user_child = $_POST['id_user_child'] ?? 'NULL';
$img_saved = $_POST['img_saved'] ?? 'NULL';
$title = $_POST['title'] ?? 'NULL';
$description = $_POST['description'] ?? 'NULL';
$consequences = $_POST['consequences'] ?? 'NULL';

$fileTmpPath = $_FILES['fimagen']['tmp_name'] ?? 'NULL';
$fileName = $_FILES['fimagen']['name'] ?? 'NULL';
$fileSize = $_FILES['fimagen']['size'] ?? 'NULL';
$fileType = $_FILES['fimagen']['type'] ?? 'NULL';
$uploadFileDir = '../../files/img/rules/';
$destPath = $uploadFileDir . $fileName;

if ($fileName == null) {
	$fileName = $img_saved;
}


try {
	if ($form == "Crear") {
		$success = Rule::insert_rule($id_educator, $id_user_child, $title, $description, $consequences, $fileName);
	} else if ($form == "Editar") {
		$success = Rule::update_rule($id, $id_educator, $id_user_child, $title, $description, $consequences, $fileName);
	} else {
		$success = Rule::delete_rule($id);
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
			if ($fileName != null) {
				if (!file_exists($uploadFileDir)) {
					mkdir($uploadFileDir, 0777, true);
				}
				if(!move_uploaded_file($fileTmpPath, $destPath)) {
					$message = 'Image is not saved';
					header('Location: ./index.php?action=create_rule&message='.$message);
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
					header('Location: ./index.php?action=update_rule&message='.$message);
				}
			}
			header('Location: ./index.php?action=update_rule');
		} else {
			header('Location: ./index.php?action=delete_rule');
		}

	} else {
		header('Location: ./index.php?success='.($success === false ? 'false' : 'true'));
	}
}
