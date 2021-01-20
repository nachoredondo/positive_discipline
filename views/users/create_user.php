<?php
require("../../classes/user.php");

$errors = [];

$type = $_POST['type'];
$user = $_POST['user'] ?? '';
$email = $_POST['email'];
$name = $_POST['name'] ?? '';
$surnames = $_POST['surnames'];
$password = $_POST['password'];
$password_confirm = $_POST['password-confirm'];
$age = $_POST['age'] ?? 'NULL';
$image = $_POST['image'] ?? 'NULL';

if(strlen($password) < 8 || strlen($password) > 128) {
	header('Location: ./registrer.php?type='.$type.'&success=false&error=password-length');
}

try {
	$success = User::insert_user($type, $user, $email, $name, $surnames, $password, $password_confirm, $age, $image);
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
