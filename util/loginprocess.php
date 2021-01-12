<?php
session_start();
require('dbinit.php');

if (!isset($_POST)) {
	header("Location: ../");
} else {
	$bad_input = [];
	foreach ($_POST as $field => $value) {
		$value = trim(htmlspecialchars($value));
		
		if ($field == 'userid') {
			if (!preg_match('/[0-9]{11,}/', $value)) {
				$bad_input[$field] = $value;
			}
		}
		if ($field == "passwd") {
			if (!preg_match('/[a-zA-Z0-9_]{8,}/', $value)) {
				$bad_input[$field] = $value;
			}
		}
	}

	if (count($bad_input) > 0) {
		// extract($bad_input);
		$_SESSION['error']['login_input'] = "Invalid Input. Please use phone number and alphanumeric password to login";
	} else {
		$user = $_POST['userid'];
		$passwd = sha1($_POST['passwd']);
		$db = new Database();
		// echo "staffid='$user' AND password='$passwd'";
		if ($db->connect()) {
			$db->select('staff', 'phone, passwd', "phone='$user' AND passwd='$passwd'");

			$res = $db->getResults();
			if ($res['phone'] === $user and $res['passwd'] == $passwd) {
				$_SESSION['login'] = true;
				$_SESSION['user'] = $user;
			} else {
				$_SESSION['login'] = false;
				$_SESSION['error']['message'] = "Wrong Username or Password";
			}
			print_r($res);
		} else {
			$_SESSION['error']['message'] = "The connection seems to be down. Please check with the ICT Office";
		}
	}
	header("Location: ../");
}