<?php
require("../../classes/user.php");
require '../../classes/session.php';

Session::check_login_redirect();
$errors = [];

$from = $_POST['from'];
$id = $_POST['id'];
$name = $_POST['name'] ?? 'NULL';
$tutor = $_POST['tutor'] ?? 'NULL';
$age = $_POST['age'] ?? 'NULL';
$img = $_POST['img'] ?? 'NULL';

if (!$name){
	header('Location: ./profile_child.php?success=false&error='.$error.'&message=Nombre vacío');
	exit();
}

try {
	if ($_SESSION['type'] && $from != "delete_child" && $from != "update-user") {
		$success = User::insert_user("child", 'NULL', 'NULL', $name, 'NULL', 'NULL', $tutor, $age, $img);
	} else {
		if ($from == "update-user"){
			$success = User::update_user_child($id, $tutor, $name, $age, $img);
		} else {

			$success = User::delete_child($id);
		}
	}
} catch(InvalidArgumentException $e) {
	print("catch");
	$errors[] = "incorrect_camp";
	$message = $e->getMessage();
	$error = implode(',', $errors);
	header('Location: ./profile_child.php?success=false&error='.$error.'&message='.$message);
	exit();
}
if (!$errors) {
	if ($success) {
		if ($_SESSION['type'] && $from != "delete_child" && $from != "update-user") {
			header('Location: ./profile_tutor.php?action=create_user');
		} else {
			if ($from == "update-user"){
				if ($_SESSION['type']) {
					header('Location: ./profile_tutor.php?action=update');
				} else {
					$_SESSION['user'] = $tutor . "_" . $img;
					$_SESSION['fullname'] = ($surnames == "") ? $name : $surnames . ', ' . $name;
					$_SESSION['name'] = $name;
					header('Location: ./profile_child.php?action=update');
				}
			} else {
				if ($from != "delete_child") {
					header('Location: ./logout.php');
				} else {
					header('Location: ./profile_tutor.php?action=delete_child');
				}
			}
		}
	} else {
		header('Location: ./profile_child.php?success='.($success === false ? 'false' : 'true'));
	}
}
