<?php
require("../../classes/option_stop.php");
require '../../classes/session.php';

Session::check_login_redirect();
$errors = [];

$form = $_POST['form'];
$id = $_POST['id'] ?? 'NULL';
$id_user = $_POST['id_user'];
$type = $_POST['type'];
$title = $_POST['title'];
$file = $_POST['file_saved'] ?? 'NULL';
$link_saved = $_POST['link_saved'] ?? 'NULL';
$link_new = $_POST['link_new'] ?? 'NULL';
$position = $_POST['position'];
$position_old = $_POST['position_old'];

$fileTmpPath = $_FILES['file']['tmp_name'] ?? 'NULL';
$fileName = $_FILES['file']['name'] ?? 'NULL';
$fileSize = $_FILES['file']['size'] ?? 'NULL';
$fileType = $_FILES['file']['type'] ?? 'NULL';

if ($fileSize > 8000000) {
	header('Location: ./edit_create.php?type='.$type.'&message=size-file-exceeded');
	exit();
}

if ($type == "image") {
	$folder = "img";
} else if ($type == "audio") {
	$folder = "audio";
} else if ($type == "video") {
	$folder = "video";
} else if ($type == "youtube") {
	if ($link_new != "") {
		$link_new = explode("?v=", $link_new);
		if (count($link_new) > 1) {
			$link = $link_new[1];
		} else {
			header('Location: ./edit_create.php?type='.$type.'&message=incorrect-link');
		}
	} else if ($link_saved != "") {
		header('Location: ./edit_create.php?type='.$type.'&message=not-link');
	} else {
		$link = $link_saved;
	}
}

if ($type != "youtube"){
	$uploadFileDir = '../../files/stop/'.$folder.'/';
	$destPath = $uploadFileDir . $fileName;

	if ($fileName == null) {
		$fileName = $file;
	}
}

try {
	if ($form == "Crear") {
		if ($type != "youtube") {
			$success = Option_Stop::insert_stop($id_user, $type, $title, $fileName, $position);
		} else {
			$success = Option_Stop::insert_stop($id_user, $type, $title, $link, $position);
		}
	} else if ($form == "Editar") {
		if ($type != "youtube") {
			$success = Option_Stop::update_stop($id, $id_user, $type, $title, $fileName, $position, $position_old);
		} else {
			$success = Option_Stop::update_stop($id, $id_user, $type, $title, $link, $position, $position_old);
		}
	} else {
		$success = Option_Stop::delete_stop($id, $id_user, $position_old);
	}
} catch(InvalidArgumentException $e) {
	$errors[] = "incorrect_camp";
	$message = $e->getMessage();
	$error = implode(',', $errors);
	header('Location: ./edit_create.php?success=false&type='.$type.'&error='.$error.'&message='.$message);
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
					header('Location: ./edit_create.php?action=create_option&type='.$type.'&message='.$message);
				}
			}
			header('Location: ./edit_create.php?action=create_option&type='.$type);
		} else if ($form == "Editar") {
			if ($fileName != null) {
				if (!file_exists($uploadFileDir)) {
					mkdir($uploadFileDir, 0777, true);
				}
				if(!move_uploaded_file($fileTmpPath, $destPath)) {
					$message = 'Image is not saved';
					header('Location: ./edit_create.php?action=update_option&type='.$type.'&message='.$message);
				}
			}
			header('Location: ./edit_create.php?action=update_option&type='.$type);
		} else {
			header('Location: ./edit_create.php?action=delete_option&type='.$type);
		}

	} else {
		header('Location: ./edit_create.php?type='.$type.'&success='.($success === false ? 'false' : 'true'));
	}
}
