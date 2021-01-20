<?php

function logEvent($message, $level = 'info')
{
	$bg = "[" . date('Y-m-d H:i:sA') . "] - " . $message . "\n";
	switch ($level) {
		case 'error':
			$filename = ERROR_LOG;
			break;
		case 'user error':
			$filename = USERERR_LOG;
			break;
		default:
			$filename = INFO_LOG;
	}
	if (file_exists($filename)) {
		$fh = fopen($filename, 'a+');
	} else {
		$fh = fopen($filename, 'w+');
	}
	if (!fwrite($fh, $bg)) {
		return false;
	}
	fclose($fh);
}

function deliverJsonOutput($data)
{
	echo json_encode($data, 512);
	exit();
}

function uploadFile($file, $db, $db_data, $options)
{
	$filename = $file['name'];
	$db_name = $db_data['dbname'];
	$user = $options['user'];
	$recipient = $options['recipient'];
	if (file_exists(UPLOAD_DIR . $filename)) {
		// deliverJsonOutput(['message' => "$filename exists already."]);
		$newFilename = $filename;
		for ($i = 0; file_exists(UPLOAD_DIR . $newFilename); $i += 1) {
			$file_name = explode('.', $filename, 2)[0];
			$file_ext = explode('.', $filename, 2)[1];
			$newFilename = "$file_name ($i).$file_ext";
		}
		$filename = $newFilename;
	}
	$destination = UPLOAD_DIR . $filename;
	// exit();
	if (move_uploaded_file($file['tmp_name'], $destination)) {
		$db_data['data']['file'] = "/assets/upload/$filename";
		if (!$db->insert($db_name, $db_data['data'])) {
			$error = $db->getError();
			logEvent("'$destination' from @$user to $recipient was uploaded but was not registered into the database. $error", 'error');
			return false;
		}
		logEvent("$filename from $user to $recipient has been uploaded. $destination");
		return true;
	}
	return false;
}
