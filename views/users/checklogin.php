<?php
require("../../classes/user.php");

$type = $_POST["type"];

function login_failed($type) {
	header('Location: ./login.php?type='.$type);
	exit();
}

function login_succeded() {
	// Redirect to the last URL or the main page
	unset($_SESSION['lasturl']);
	if (isset($_SESSION['lasturl'])) {
		$url = $_SESSION['lasturl'];
		unset($_SESSION['lasturl']);
	} else {
		$url = './../rules/index.php';
	}
	header('Location: ' . $url);
}

session_start();

if ($type == "adult") {
	$email = $_POST['email'];
	$password = $_POST['password'];
	$educator = true;
} else {
	$tutor = $_POST['tutor'];
	$img = $_POST['img'];
	$password = "";
	$educator = false;
}

try {
	if ($type == "adult") {
		$user = User::get_user_from_email($email);
	} else {
		$user = User::get_user_from_tutor_img($tutor, $img);
	}
} catch (InvalidArgumentException $err) {
	$_SESSION['login-error'] = true;
	login_failed($type);
} catch (Exception $err) {
	login_failed($type);
}

if ($type == "child" || $user->password_verify($password)) {
	session_regenerate_id(true); // Regenerate to avoid session fixation
	$_SESSION['loggedin'] = true;
	$_SESSION['user'] = $user->user();
	$_SESSION['type'] = $user->type($educator);
	$_SESSION['fullname'] = $user->fullname();
	$_SESSION['name'] = $user->name();
	$_SESSION['lastcheck'] = time();

	login_succeded();
} else {
	$_SESSION['login-error'] = true;
	login_failed($type);
}
