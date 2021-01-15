<?php
function authorizeLogin()
{
	if (!@$_SESSION['loggedin']) {
		header("Location: /login.php");
	}
	$user = $_SESSION['user'];
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

