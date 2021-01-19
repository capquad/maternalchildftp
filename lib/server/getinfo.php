<?php
session_start();

require("./functions.php");
require("./authorize.php");
$user = authorizeLogin();

if (isset($_GET['content'])) {
	require("../db/db.php");
	require("../db/database.php");

	if ($_GET["content"] === "ftp") {
		$db = new Database(DBHOST, DBUSER, DBPASS);
		if (!$db->connect(DBNAME)) {
			deliverJsonOutput(["message" => "WTF" . $db->getError()]);
		}

		if (!$db->select("staff_ftp", "*", "sender='$user' OR receiver = '$user'")) {
			deliverJsonOutput(["message" => "WTF. " . $db->getError()]);
		}
		$result = $db->getResults();
		$data = [];
		$data['sent'] = [];
		$data['received'] = [];
		foreach ($result as $record => $details) {
			if ($details['sender'] === $user) {
				unset($details['sender']);
				$data['sent'][] = $details;
			}
			if ($details['receiver'] === $user) {
				unset($details['receiver']);
				$data['received'][] = $details;
			}
		}
		foreach ($data['sent'] as $rec => $det) {
			$rec_phone = $det['receiver'];
			if ($db->select("staff", "fname, lname, mname", "phone = '$rec_phone'")) {
				$sender = join(" ", $db->getResults()[0]);
				$data['sent'][$rec]['receiver'] = $sender;
			}
		}
		foreach ($data['received'] as $rec => $det) {
			$rec_phone = $det['sender'];
			if ($db->select("staff", "fname, lname, mname", "phone = '$rec_phone'")) {
				$receiver = join(" ", $db->getResults()[0]);
				$data['received'][$rec]['sender'] = $receiver;
			}
		}
		$db->disconnect();
		deliverJsonOutput(["data" => $data]);
	}
}
