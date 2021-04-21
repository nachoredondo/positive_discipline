<?php
require("../../classes/user.php");

$errors = [];

$type = $_POST['type'];
$user = $_POST['user'] ?? 'NULL';
$user_tutor = $_POST['user-tutor'] ?? 'NULL';
$password_tutor = $_POST['password-tutor'] ?? 'NULL';
$email = $_POST['email'] ?? 'NULL';
$name = $_POST['name'];
$surnames = $_POST['surnames'] ?? 'NULL';
$password = $_POST['password'] ?? 'NULL';
$password_confirm = $_POST['password-confirm'] ?? 'NULL';
$tutor = $_POST['user-tutor'] ?? 'NULL';
$age = $_POST['age'] ?? 'NULL';
$image = $_POST['img'] ?? 'NULL';

if($type == "child") {
	$parameter = "";
} else {
	$parameter = "&user=".$user."&name=".$name."&email=".$email."&surnames=".$surnames;
}

if($type == "tutor" && (strlen($password) < 8 || strlen($password) > 128)) {
	header('Location: ./registrer.php?type='.$type.'&success=false&error=password-length'.$parameter);
	exit();
}
if($type == "tutor" && $password != $password_confirm) {
	header('Location: ./registrer.php?type='.$type.'&success=false&error=no-same-password'.$parameter);
	exit();
}

if($type == "child") {
	if ($age == "") {
		header('Location: ./registrer.php?type='.$type.'&success=false&error=no-age');
		exit();
	}
	if ($image == "") {
		header('Location: ./registrer.php?type='.$type.'&success=false&error=no-img');
		exit();
	}
	$user_tutor_child = User::get_user_from_user($user_tutor);
	if (!$user_tutor_child) {
		header('Location: ./registrer.php?type='.$type.'&success=false&error=no-user-tutor');
	}
	if (!$user_tutor_child->password_verify($password_tutor)) {
		header('Location: ./registrer.php?type='.$type.'&success=false&error=no-pass-tutor');
		exit();
	}
}

try {
	$success = User::insert_user($type, $user, $email, $name, $surnames, $password, $tutor, $age, $image);
} catch(InvalidArgumentException $e) {
	$errors[] = "incorrect_camp";
	$message = $e->getMessage();
	$error = implode(',', $errors);
	header('Location: ./registrer.php?type='.$type.'&success=false&error='.$error.'&message='.$message.$parameter);
}
if (!$errors) {
	if ($success) {
		header('Location: ./login.php?type='.$type.'&action=created');
	} else {
		header('Location: ./registrer.php?type='.$type.'&success='.($success === false ? 'false' : 'true'));
	}
}
