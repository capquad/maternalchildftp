<?php
session_start();
require("./lib/classes/server.php");
$db = new Database();
if ($db->connect("admin_mc")) {
	$id = "PER-011220";
	if (!$db->insert("health_care_providers", ["hmoid" => "AXAMSD", "name" => "AXA Mansard Insurance"])) {
		echo $db->getError();
	} else {
		echo "Done";
	}
}
