<?php
session_start();
if (!isset($_POST) or !isset($_FILES)) {
	echo json_encode("Get out");
	exit();
}
require('./authorize.php');

$user = authorizeLogin(); // check if user is logged in

require('../db/db.php');
require('../db/database.php');

define('VALID_EXT', ['txt', 'docx', 'doc', 'xls', 'xlsx', 'pdf', 'html', 'js', 'sql', 'accdb', 'ppt', 'pptx', 'pptm', 'accda', 'mdb', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'mp3', 'mp4']);

$db = new Database(DBHOST, DBUSER, DBPASS);
$db->connect(DBNAME);

$file = $_FILES['files'];
if (!validateFile($file, VALID_EXT)) {
	$message = $file['name'] . " is an invalid file.";
	echo json_encode(["message" => $message]);
	exit();
}

$message = round($file['size'] / 1.049e6, 2) . "MB";

echo json_encode(["message"=>$message]);