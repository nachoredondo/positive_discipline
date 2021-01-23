<?php
require("../../classes/user.php");

$errors = [];

$type = $_POST['type'];
$user = $_POST['user'] ?? 'NULL';
$email = $_POST['email'] ?? 'NULL';
$name = $_POST['name'];
$surnames = $_POST['surnames'] ?? 'NULL';
$password = $_POST['password'] ?? 'NULL';
$password_confirm = $_POST['password-confirm'] ?? 'NULL';
$tutor = $_POST['user-tutor'] ?? 'NULL';
$age = $_POST['age'] ?? 'NULL';
$image = $_POST['img'] ?? 'NULL';

if($type=="adult" && (strlen($password) < 8 || strlen($password)) > 128) {
	header('Location: ./registrer.php?type='.$type.'&success=false&error=password-length');
	exit();
}

if($type == "child") {
	if ($age=="") {
		header('Location: ./registrer.php?type='.$type.'&success=false&error=no-age');
		exit();
	}
	if ($image == "") {
		header('Location: ./registrer.php?type='.$type.'&success=false&error=no-img');
		exit();
	}
}

try {
	$success = User::insert_user($type, $user, $email, $name, $surnames, $password, $password_confirm, $tutor, $age, $image);
} catch(InvalidArgumentException $e) {
	$errors[] = "incorrect_camp";
	$message = $e->getMessage();
	$error = implode(',', $errors);
	header('Location: ./registrer.php?type='.$type.'&success=false&error='.$error.'&message='.$message);
}
if (!$errors) {
	if ($success) {
		header('Location: ./login.php?type='.$type.'&action=creating');
	} else {
		header('Location: ./registrer.php?type='.$type.'&success='.($success === false ? 'false' : 'true'));
	}
}
