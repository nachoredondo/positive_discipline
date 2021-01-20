<?php
require("../../classes/user.php");

function login_failed() {
	header('Location: ./login.php?type=child');
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

$email = $_POST['email'];
$password = $_POST['password'];

try {
	$user = User::get_user_from_email($email);
} catch (InvalidArgumentException $err) {
	$_SESSION['login-error'] = true;
	login_failed();
} catch (Exception $err) {
	login_failed();
}

// Passwords have to be encrypted with password_hash($pwd, PASSWORD_BCRYPT);
// To keep compatibility with a future Node implementation (which lacks Argon support)
if ($user->password_verify($password)) {
	session_regenerate_id(true); // Regenerate to avoid session fixation
	$_SESSION['loggedin'] = true;
	$_SESSION['user'] = $user->user();
	$_SESSION['type'] = $user->type();
	$_SESSION['fullname'] = $user->fullname();
	$_SESSION['lastcheck'] = time();

	login_succeded();
} else {
	$_SESSION['login-error'] = true;
	login_failed();
}
