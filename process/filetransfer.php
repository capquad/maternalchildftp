<?php
session_start();
require('../util/dbinit.php');
@$user = $_SESSION['user'];
if (!@$user) {
	echo "Please <a href='/'>Login</a>";
	exit();
} else {
	$DB = new Database();
	$DB->connect();
	$DB->select('staff', 'id, officeid as depid', "phone ='$user'");
	$depID = $DB->getResults();
	$depID = $depID['id'];
}
if (isset($_POST) and isset($_FILES)) {
	$multiple_files = [];
	$one_file = [];
	$destination = "assets/uploads/";
	$recipients = $_POST['recipients'];

	// echo $recipients;
	$recipients = str_replace("\"", "", $recipients);
	$recipients = str_replace("[", "", $recipients);
	$recipients = str_replace("]", "", $recipients);

	if (strlen($recipients) > 3) {
		$recipients = explode(",", $recipients); //resolve into array
	}

	// Initialize output
	$result = "";
	$date = date('Y-m-d h:i:s');
	$rows = "sender, receiver, file_link, name, date";

	// Parse values
	$values = ["$depID", ""];
	if (!is_array($recipients) && strlen($recipients) < 1) {
		$db = new Database();
		$db->connect();
		$db->select('staff', 'distinct departmentid', "phone_number != '$user'");
		$res = $db->getResults();
		// $db->disconnect();

		for ($i = 0; $i < count($res); $i++) {
			$values[1] .= $res[$i]['departmentid'];
			if (count($res) - $i != 1) {
				$values[1] .= ", ";
			}
		}
	} elseif (!is_array($recipients) && strlen($recipients) < 4) {
		$values[1] .= "$recipients";
	} elseif (is_array($recipients)) {
		for ($i = 0; $i < count($recipients); $i++) {
			$values[1] .= $recipients[$i];
			if (count($recipients) - $i != 1) {
				$values[1] .= ", ";
			}
		}
	}

	// File validation and processing
	$countfiles = count($_FILES);
	if ($countfiles > 1) {
		// Parse through each file if multiple
		foreach ($_FILES as $file) {
			$fileprocess = processFile($file);
			if (!$fileprocess) {
				$multiple_files = json_encode(array("statusCode" => 400, "msg" => "File Not Uploaded. Check Extension and Try Again", "file" => $file['name']));
			} else {
				$values[2] = $destination . $file['name'];
				$values[3] = $file['name'];
				$values[4] = $date;
				// echo json_encode($values);
				// exit();
				// $params = $values;
				// print_r($values);
				// exit();
				if (!registerFile($file, $values, $rows)) {
					echo json_encode(array("statusCode" => 400, "msg" => "Please Refresh the page", "file" => $file['name']));
					exit();
				} else {
					$multiple_files = json_encode(array("statusCode" => 200, "msg" => "File Uploaded.", "file" => $file['name']));
				}
			};
		}
		echo @json_encode($multiple_files);
	} else {
		$fileprocess = processFile($_FILES['file']);
		if (!$fileprocess) {
			echo json_encode(array("statusCode" => 400, "msg" => "File Not Uploaded. Check Extension and Try Again", "file" => $file['name']));
			exit();
		} else {
			$values[2] = $destination . $_FILES['file']['name'];
			$values[3] = $_FILES['file']['name'];
			$values[4] = $date;
			// print_r($values);
			// exit();
			// $params = "'$user', '$values', '$destination" . $_FILES['file']['name'] . "'";
			if (!registerFile($_FILES['file'], $values, $rows)) {
				echo json_encode(array("statusCode" => 400, "msg" => "Please Refresh the page", "file" => $_FILES['file']['name']));
				exit();
			} else {
				echo json_encode(array("statusCode" => 200, "msg" => "File Uploaded.", "file" => $_FILES['file']['name']));
				exit();
			}
		};
	}
}

function processFile($file)
{
	$valid_extensions = array('pdf', 'doc', 'docx', 'jpeg', 'png', 'jpg', 'xlsx', 'txt', 'mp4', 'xls', 'sql', 'exe', 'html', 'css', 'php');
	$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

	if (array_search($ext, $valid_extensions) !== false) {
		// 
		if (!move_uploaded_file($file["tmp_name"], "../assets/uploads/" . $file["name"])) {
			return false;
		} else {
			return true;
		}
	} else {
		return "false";
	}
};

function registerFile($file, $values, $rows)
{
	$db = new Database();
	$db->connect();

	if ($db->insert('staff_ftp', $values, $rows)) {
		return true;
	} else {
		echo $db->getError();
		return false;
	}
}
