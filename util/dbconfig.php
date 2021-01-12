<?php

if (isset($_GET)) {
	if ($_GET['passwd'] != "adeojo32") {
		echo "Not authorized to Access this page";
		exit();
	} else {
		require("dbinit.php");
		$db = new Database();
		// $db->connect();
		$staffid = "08137033531";
		$passwd = sha1($staffid);
		if ($db->update('staff', ['passwd'=> "$passwd"], ["phone = $staffid"])) {
			echo "Done!";
		} else {
			echo $db->error;
		}

	}
}

?>