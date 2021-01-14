<?php
session_start();
if (!isset($_POST) or !isset($_FILES)) {
	echo json_encode("Get out");
	exit();
}
require('./authorize.php');

$user = authorizeLogin(); // check if user is logged in

function deliverJsonOutput($data)
{
	echo json_encode($data, 512);
	exit();
}

require('../db/db.php');
require('../config/config.inc.php');
require('../db/database.php');

define('VALID_EXT', ['txt', 'docx', 'doc', 'xls', 'xlsx', 'pdf', 'html', 'js', 'sql', 'accdb', 'ppt', 'pptx', 'pptm', 'accda', 'mdb', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'mp3', 'mp4']);

$file = $_FILES['files'];
if (!validateFile($file, VALID_EXT)) {
	$message = $file['name'] . " is an invalid file.";
	deliverJsonOutput(["message" => $message]);
}

if (round($file['size'] / 1.049e6, 2) > 100) {
	$message = "File greater than 100MB";
	deliverJsonOutput(["message" => $message]);
}

$db = new Database(DBHOST, DBUSER, DBPASS);
if (!$db->connect(DBNAME)) {
	deliverJsonOutput(["message" => "Failed to connect to Database. " . $db->getError()]);
}

// deliverJsonOutput(['message' => UPLOAD_DIR]);

if (!is_dir(UPLOAD_DIR)) {
	if (mkdir(UPLOAD_DIR, 0777, true)) {
		deliverJsonOutput(["message" => "DIR created"]);
	} else {
		deliverJsonOutput(["message" => "DIR not created"]);
	}
}
