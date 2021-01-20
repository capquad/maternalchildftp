<?php
session_start();

require './lib/server/authorize.php';
require './lib/config/config.inc.php';
require './lib/server/functions.php';
require './lib/db/db.php';
require './lib/db/database.php';

$db = new Database(DBHOST, DBUSER, DBPASS);
$db->connect(DBNAME);

if ($user = authorizeLogin($db)) {
	if (session_destroy()) {
		logEvent("$user was LOGGED OUT successfully.");
	} else {
		logEvent("$user LOG OUT FAILED.", "error");
	}
} else {
	session_destroy();
}

header("Location: /");
