<?php
require("../../classes/user.php");
require '../../classes/session.php';

Session::check_login_redirect();
$errors = [];

$type = $_POST['type'];
$id = $_POST['id'];
$form = $_POST['form'];
$user = $_POST['user'] ?? 'NULL';
$email = $_POST['email'] ?? 'NULL';
$name = $_POST['name'] ?? 'NULL';
$surnames = $_POST['surnames'] ?? 'NULL';
$password = $_POST['pwd'] ?? 'NULL';
$password_confirm = $_POST['pwd-confirm'] ?? 'NULL';
$tutor = $_POST['user-tutor'] ?? 'NULL';
$age = $_POST['age'] ?? 'NULL';
$image = $_POST['img'] ?? 'NULL';

if($type && (strlen($password) < 8 || strlen($password)) > 128) {
	header('Location: ./redireccionmal.php?type='.$type.'&success=false&error=password-length');
	exit();
}

if(!$type) {
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
	if ($form == "data") {
		$success = User::update_user_adult($id, $user, $email, $name, $surnames);
	} else if ($form == "pwd") {
		$success = User::password_update($id, $password, $password_confirm);
	} else {
		$success = User::delete_tutor($id);
	}
} catch(InvalidArgumentException $e) {
	$errors[] = "incorrect_camp";
	$message = $e->getMessage();
	$error = implode(',', $errors);
	header('Location: ./profile_tutor.php?success=false&error='.$error.'&message='.$message);
	exit();
}
if (!$errors) {
	if ($success) {
		if ($form == "data") {
			$_SESSION['user'] = $user;
			$_SESSION['fullname'] = ($surnames == "") ? $name : $surnames . ', ' . $name;
			$_SESSION['name'] = $name;
			header('Location: ./profile_tutor.php?action=data');
		} else if ($form == "pwd") {
			header('Location: ./profile_tutor.php?action=pwd');
		} else {
			header('Location: ./logout.php');
		}
	} else {
		header('Location: ./profile_tutor.php?success='.($success === false ? 'false' : 'true'));
	}
}
