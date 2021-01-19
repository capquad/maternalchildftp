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

if (isset($_POST['notification'])) {
	unset($_POST['notification']);
	$notification_title = trim(strip_tags(htmlspecialchars($_POST['title'])));
	$recipient = join(',', $_POST['recipient']);
	$recipient = trim(strip_tags(htmlspecialchars($recipient)));
	// echo $recipient;exit();
	// echo $notification_title;
	// Check for existence of file
	if (!isset($_FILES['notification-file'])) {
		header("Location: /notifications.php");
		exit();
	}

	$notification_file = $_FILES['notification-file'];

	// Validate file type
	$filename = $notification_file['name'];
	if (!validateFile($notification_file, ['txt'])) {
		$_SESSION['flash'] = ['type' => 'danger', 'message' => "Invalid Notification File - $filename uploaded. Only text (.txt) files allowed."];
		header("Location: /notifications.php");
		exit();
	}
	$targetFile = UPLOAD_DIR . $filename;
	if (!uploadFile($notification_file, $db, ['dbname' => 'dept_notifications', 'data' => ['sender' => $user, 'receiver' => $recipient, 'title' => $notification_title, 'date' => date('Y-m-d H:i:s')]], ['user' => $user, 'recipient' => $recipient], ['user' => $user, 'recipient' => $recipient])) {
		$_SESSION['flash'] = [
			'type' => 'danger',
			'message' => "Your notification has not been posted successfully. Please try again or contact the webmaster."
		];
		header("Location: /notifications.php");
	} else {
		$_SESSION['flash'] = ['type' => 'success', 'message' => "Your notification has been posted successfully."];
		header("Location: /notifications.php");
	}
	exit();
}

// import header html and page settings
require './lib/config/pagesetup.php';
require './lib/util/header.php';
?>
<main>
	<section>
		<div class="container mt-4">
			<div class="row">
				<div class="col-md-8 mb-4 mb-sm-0">
					<h2>Notifications</h2>
					<?php
					$min_date = date('Y-m-d H:i:s', time() - 24 * 60 * 60 * 2);
					// echo $sql = "receiver LIKE '%" . $userdetails['officeid'] . "%'";exit();
					if ($db->select('dept_notifications', '*', "(receiver LIKE '%" . $userdetails['officeid'] . "%' or receiver='all') AND date >= '$min_date'")) {
						$notifications = $db->getResults();
						foreach ($notifications as $notification => $info) {
							if ($db->select('staff', 'fname, lname, mname', "phone='" . $info['sender'] . "'")) {
								$info['sender'] = join(' ', $db->getResults()[0]);
							}
							echo "<div class='notification'>";
							echo "<p class='notif-head'><span class='notification-header'>" . $info['title'] . "</span>  <span class='notification-sender'>- " . $info['sender'] . "</span></p>";
							$message = file_get_contents($info['file']);
							echo "<pre>" . $message . "</pre>";
							echo "<a href='/notification.php?title=" . $info['title'] . "'> See this Notification in full</a>";
							echo "</div>";
						}
					} else {
						$error = $db->getError();
						logEvent("USER: $user. ERROR: $error.", "user error");
					}
					?>
				</div>
				<div class="col-md-4">
					<h2>Create A Notification</h2>
					<div>
						<p class="h6">To Create A Notification</p>
						<ol class="list-group">
							<li class="list-group-item">Create a title for your notification.</li>
							<li class="list-group-item">Create a text (.txt) file and fill it with your notification content.</li>
							<li class="list-group-item">Choose recipients and Upload Your Notification File.</li>
							<li class="list-group-item default"><button class="btn btn-primary" type="button" data-toggle="modal" data-target="#notificationModal">Create A Notification</button></li>
						</ol>
						<p class="h6 mt-5">What to know About Notifications</p>
						<ol class="list-group">
							<li class="list-group-item">Notifications last for 48 hours</li>
							<li class="list-group-item">Notifications cannot be deleted after being posted</li>
							<li class="list-group-item">Notifications can be sent to single departments or to all departments</li>
							<!-- <li class="list-group-item"></li> -->
						</ol>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div class="modal fade" id="notificationModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">Send Out A Notification</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<!-- Modal Body -->
				<div class="modal-body">
					<form action="" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<label for="title">Notification Title</label>
							<input type="text" name="title" id="title" class="form-control" max="50" required>
						</div>
						<div class="form-group">
							<label for="file">Notification File (.txt)</label>
							<input type="file" name="notification-file" id="#file" class="form-control" accept="text/plain">
						</div>
						<div class="form-group">
							<label for="recipient">Recipients</label>
							<select name="recipient[]" id="recipient" class="form-control" required="required" size="3" multiple>
								<option value="all" selected>All</option>
								<?php
								if ($db->select('staff_categories', '*')) {
									$depts = $db->getResults();
									foreach ($depts as $dept) {
										echo "<option value='" . $dept['officeid'] . "'>" . $dept['name'] . "</option>";
									}
								}
								?>
							</select>
						</div>
						<div class="form-group">
							<button type="submit" name="notification" class="btn btn-primary">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</main>

<?php
require './lib/util/footer.php';
