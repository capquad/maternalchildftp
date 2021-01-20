<?php
function authorizeLogin($db = null)
{
	if (!isset($_SESSION['loggedin']) or $_SESSION['loggedin'] !== true) {
		header("Location: /login.php");
		return;
	}
	$user = $_SESSION['user'];
	if ($db !== null) {
		if ($db->select('staff', 'fname, mname, lname, phone, designation, officeid', "phone='$user'")) {
			$user = $db->getResults()[0];
			$user['name'] = join(' ', [$user['lname'], $user['fname'], $user['mname']]);
		}
	}
	return $user;
}

function validateUserid($string)
{
	if (!preg_match("/^[0-9]{11}$/", $string)) {
		return false;
	}
	return true;
}

function validatePassword($string)
{
	if (!preg_match("/^[a-zA-Z0-9_]{8,}$/", $string)) {
		return false;
	}
	return true;
}

function validateFile($file, $valid_extensions)
{
	$file_parts = explode('.', $file['name']);
	$ext = $file_parts[array_key_last($file_parts)];
	return in_array(strtolower($ext), $valid_extensions);
}
