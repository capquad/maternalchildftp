<?php
function authorizeLogin()
{
	if (!@$_SESSION['loggedin']) {
		header("Location: /");
	}
}

function validateUserid($string)
{
	if (!preg_match("/^[0-9]{11}$/", $string)) {
		return false;
	}
	return true;
}

function validatePassword ($string) {
	if (!preg_match("/^[a-zA-Z0-9_]{8,}$/", $string)) {
		return false;
	}
	return true;
}