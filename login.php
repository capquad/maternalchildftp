<?php
session_start();

if (!isset($_POST['login'])) {
	require('./lib/config/pagesetup.php');
	require('./lib/util/header.php');

	require("./lib/login.php");
	exit();
}

// Process Login Form
require('./lib/server/authorize.php');

require './lib/config/config.inc.php';
require './lib/server/functions.php';

require('./lib/db/db.php');
require('./lib/db/database.php');

unset($_POST['login']);
if (validateUserid($_POST['userid'])) {
	$userid = $_POST['userid'];
} else {
	echo "Invalid Username format";
	exit();
}
if (validatePassword($_POST['password'])) {
	$password = sha1($_POST['password']);
} else {
	echo "Invalid password format";
	exit();
}
$db = new Database(DBHOST, DBUSER, DBPASS);
if (!$db->connect(DBNAME)) {
	echo "Database Connection failed.";
	exit();
}
if (!$db->select("staff", "phone, passwd", "phone='$userid'")) {
	echo $db->getError();
	exit();
}
$result = $db->getResults()[0];
if (@count($result) < 1) {
	logEvent("UNRECOGNIZED USER LOGIN ATTEMPT: $userid");
	// echo "User does not exist";
	$_SESSION['flash'] = ['type' => 'danger', 'message' => 'User does not exist'];
	header("Location: /");
	exit();
}
if ($userid === $result['phone']) {
	if ($password === $result['passwd']) {
		$_SESSION['user'] = $userid;
		$_SESSION['loggedin'] = true;
<<<<<<< HEAD
		$db->disconnect();
=======
		logEvent("$userid was LOGGED IN successfully");
		header("Location: /");
	} else {
		$_SESSION['flash'] = ['type' => 'danger', 'message' => 'Invalid Password'];
		logEvent("$userid FAILED LOG IN ATTEMPT. Reason: Invalid Password supplied.");
>>>>>>> dev
		header("Location: /");
	}
}
