<?php
require("../../classes/rule.php");
require '../../classes/session.php';

Session::check_login_redirect();
$errors = [];

$form = $_POST['form'];
// $id = $_POST['id'];
$id_user = 15;
$title = $_POST['title'];
$description = $_POST['description'];
$consequences = $_POST['consequences'];
// var_dump($fimagen);
// var_dump($_FILES);
// exit();

$fileTmpPath = $_FILES['fimagen']['tmp_name'];
$fileName = $_FILES['fimagen']['name'];
$fileSize = $_FILES['fimagen']['size'];
$fileType = $_FILES['fimagen']['type'];
$uploadFileDir = '../../files/img/rules/';
$destPath = $uploadFileDir . $fileName;

try {
	if ($form == "create") {
		$success = Rule::insert_rule($id_user, $title, $description, $consequences, $fileName);
	} else {
		$success = Rule::update_rule($id_rule, $title, $description, $consequences, $fileName);
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
		header('Location: ./edit_create.php?action=create_rule');

	} else {
		header('Location: ./profile_child.php?success='.($success === false ? 'false' : 'true'));
	}
}
