<?php
session_start();
require('dbinit.php');
// unset($_SESSION['login']);
@$loggedin = $_SESSION['login'] === true ? true : false;
include_once('./util/header.html');
include_once('./util/nav.php');
if (!$loggedin) {
	if ($_SERVER['SCRIPT_NAME'] !== "/index.php") {
		header("Location: /");
	} else include_once('./util/login.php');
	exit();
}
@$user = @$loggedin === true ? $_SESSION['user'] : false;
if ($user) {
	$db = new Database();
	if ($db->connect()) {
		$db->select('staff', 'fname, lname, designation as des, officeid as depid', "phone='$user'");
		$self = $db->getResults();
		// echo $self['depid'];
	}
}
$error = isset($_SESSION['error']) ? $_SESSION['error'] : false;
