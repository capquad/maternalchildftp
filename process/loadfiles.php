<?php
session_start();
@$user = $_SESSION['user'];
if (!$user) {
	exit();
}
include("../util/dbinit.php");

$db = new Database();
$db->connect();
// get department id of user
$db->select('staff', 'id', "phone=$user");
$depid = $db->getResults()['id'];

$depid = "$depid";
// use department id to get name
// $db->select('departments', 'name', "id=");
// $depname = $db->getResults()['name'];

if ($db->select('staff_ftp', "*", "receiver LIKE '%$depid%' or receiver=''")) {
	$recFiles = $db->getResults();
	// print_r($recFiles);
	// exit();
}
if ($db->select('staff_ftp', "*", "sender='$depid'")) {
	$sentFiles = $db->getResults();
}

$result = array();
$result['sent'] = [];
$result['received'] = [];

$db->select('staff', 'id, fname, lname');
$deps = $db->getResults();
$departments = [];
for ($i = 0; $i < count($deps); $i++) {
	$departments[$deps[$i]['id']] = $deps[$i]['lname'] . " " . $deps[$i]['fname'];
}

if (@count($sentFiles) > 0) {

	$temp = [];

	foreach (@$sentFiles as $file => $info) {
		if (is_array($info)) {
			if ($info['receiver'] !== '[]') {
				$recipient = $info['receiver'];
				$info['receiver'] = "";
				if (strlen($recipient) > 1) {
					$recipient = str_replace('[', '', $recipient);
					$recipient = str_replace(']', '', $recipient);
					$recipients = explode(',', $recipient);
					foreach ($recipients as $recip) {
						foreach ($departments as $dep => $name) {
							if ($recip == $dep) {
								$info['receiver'] .= "$name, ";
							}
						}
					}
				}
			} else {
				$info['receiver'] = 'ALL';
			}
			$temp[] = $info;
		} else {
			foreach ($departments as $depid => $name) {
				if ($file == "receiver") {
					if ($info == $depid) {
						$info = $name;
					} else if ($info == '[]') {
						$info = 'ALL';
					}
				}
			}
			$temp[$file] = $info;
		}
	}
	$result['sent'] = $temp;
}

if (@count($recFiles) > 0) {
	$temp = [];

	foreach (@$recFiles as $file => $info) {
		if (is_array($info)) {
			foreach ($departments as $depid => $name) {
				if ($info['sender'] == $depid) {
					$info['sender'] = $name;
				}
			}
			$temp[] = $info;
		} else {
			foreach ($departments as $depid => $name) {
				if ($file == "sender") {
					if ($info == $depid) {
						$info = $name;
					}
				}
			}
			$temp[$file] = $info;
		}
	}
	$result['received'] = $temp;
}

echo json_encode($result);
// use department id to get all sent and received files
// get receivers of all those files too