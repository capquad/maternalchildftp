<?php
session_start();


// import basic website functions
require './lib/config/config.inc.php';
// echo INFO_LOG;
require './lib/server/functions.php';

// import database configuration and classes
// initialize database
require './lib/db/db.php';
require './lib/db/database.php';

$db = new Database(DBHOST, DBUSER, DBPASS);
if (!$db->connect(DBNAME)) {
	$error = $db->getError();
	logEvent("USER: $user. Database connection failed: REASON: $error", "error");
}

// validate session
require './lib/server/authorize.php';
$userdetails = authorizeLogin($db);
$user = $userdetails['phone'];

require './lib/config/pagesetup.php';
require './lib/util/header.php';

?>
<main>
	<div class="container my-5">
		<div class="text-center">
			<img src="/" alt="profile picture" width="250" class="rounded-circle profile-ic">
		</div>
	</div>
</main>