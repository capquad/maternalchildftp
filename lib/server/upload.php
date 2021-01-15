<?php
session_start();

require('./functions.php');
require('./authorize.php');

if (!isset($_POST) or !isset($_FILES)) {
	deliverJsonOutput(["message" => "Forbidden Access"]);
}

if (!validateUserid($_POST['recipient'])) {
	deliverJsonOutput(['message' => 'Invalid Recipient ID']);
}
$recipient = $_POST['recipient'];

$user = authorizeLogin(); // check if user is logged in


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

chdir('../../');
if (!file_exists(UPLOAD_DIR)) {
	if (!mkdir(UPLOAD_DIR, 0777, true)) {
		deliverJsonOutput(['message' => 'Internal Server Error occured. Please try again later']);
	}
}

$filename = $file['name'];
if (file_exists(UPLOAD_DIR . $filename)) {
	// deliverJsonOutput(['message' => "$filename exists already."]);
	$newFilename = $filename;
	for ($i = 0; file_exists(UPLOAD_DIR . $newFilename); $i += 1) {
		$file_name = explode('.', $filename)[0];
		$file_ext = explode('.', $filename)[1];
		$newFilename = "$file_name ($i).$file_ext";
	}
	$filename = $newFilename;
}
$db = new Database(DBHOST, DBUSER, DBPASS);
if (!$db->connect(DBNAME)) {
	deliverJsonOutput(['message' => 'Database failed to connect. Please try again later.']);
}

$targetFile = UPLOAD_DIR . $filename;
$datetime = date('Y-m-d H:i:s');
if (move_uploaded_file($file['tmp_name'], $targetFile)) {
	if (!$db->insert('staff_ftp', ['sender' => $user, 'receiver' => $recipient, 'file_link' => $targetFile, 'name' => $filename, 'date' => $datetime])) {
		logEvent("$targetFile from $user to $recipient was not registered into the database @$datetime", 'error');
	}
	deliverJsonOutput(['message' => "$filename has been uploaded."]);
}

logEvent("$filename from $user upload failed.", 'error');
deliverJsonOutput(["message" => "$filename upload failed."]);