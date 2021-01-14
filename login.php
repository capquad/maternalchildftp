<?php
session_start();

if (!isset($_POST['login'])) {
	require('./lib/config/pagesetup.php');
	require('./lib/util/header.php');

	require("./lib/login.html");
	exit();
}

// Process Login Form
require('./lib/server/authorize.php');
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
$result = $db->getResults();
if (count($result) < 1) {
	echo "User does not exist";
	exit();
}
if ($userid === $result['phone']) {
	if ($password === $result['passwd']) {
		$_SESSION['user'] = $userid;
		$_SESSION['loggedin'] = true;
		header("Location: /");
	}
	echo "Invalid Password";
	exit();
}