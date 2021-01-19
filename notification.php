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

if (!isset($_GET['title'])) {
	logEvent("$user tried to navigate to notification page with no notification title", 'error');
	header("Location: /notifications.php");
}
$notification_title = trim(strip_tags(htmlspecialchars(@$_GET['title'])));
if (!preg_match("//", $notification_title)) {
	logEvent("Invalid Notification title entered by $user", "error");
	$_SESSION['flash'] = ['type' => 'danger', 'message' => 'Invalid Notification title'];
	header("Location: /notifications.php");
}

if (!$db->select('dept_notifications', '*', "title='$notification_title'")) {
	logEvent($db->getError(), 'error');
	header("Location: /");
}
$notification = $db->getResults();
if (count($notification) > 0) {
	$notification = $notification[0];
} else {
	header("Location: /notifications.php");
}

// Setup page
require './lib/config/pagesetup.php';
require './lib/util/header.php';
?>
<main>
	<div class="container my-4">
		<h2><?= $notification_title ?></h2>
		<div class="notification-page">
			<pre>
				<?= file_get_contents($notification['file']) ?>
			</pre>
		</div>
	</div>
</main>
<?php
require './lib/util/footer.php';
