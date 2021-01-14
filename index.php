<?php
session_start();
require('./lib/server/authorize.php');

authorizeLogin(); // check if user is logged in

require('./lib/db/db.php');
require('./lib/db/database.php');
require('./lib/config/pagesetup.php');

require('./lib/util/header.php');

?>

<main>
	<div class="container py-4">
		<h2>Dashboard</h2>
		<div class="row mt-4">
			<div class="col-sm-6 col-md-4 mb-4 mb-sm-0">
				<div class="bg-primary text-light p-3">
					<h3>FTP</h3>
				</div>
				<div class="p-3 border-left border-right  feature-desc">
					<p>
						Send and receive files securely from user to user.
					</p>
				</div>
				<div class="bg-danger text-light p-3">
					<a href="/ftp.php" class="text-light">Go to File Transfer Portal</a>
				</div>
			</div>
			<div class="col-sm-6 col-md-4 mb-4 mb-sm-0">
				<div class="bg-primary text-light p-3">
					<h3>Notifications</h3>
				</div>
				<div class="p-3 border-left border-right feature-desc">
					<p>
						Check the Notifications Board for latest news, events and notifications in the clinic.
					</p>
				</div>
				<div class="bg-danger text-light p-3">
					<a href="/notifications.php" class="text-light">Go to Notifications Board</a>
				</div>
			</div>
			<div class="col-sm-6 col-md-4 mb-4 mb-sm-0">
				<div class="bg-primary text-light p-3">
					<h3>Messages</h3>
				</div>
				<div class="p-3 border-left border-right  feature-desc">
					<p>
						Send and receive messages.
					</p>
				</div>
				<div class="bg-danger text-light p-3">
					<a href="/chat.php" class="text-light">Go to Messages</a>
				</div>
			</div>
		</div>
	</div>
</main>

<?php
require('./lib/util/footer.php');
?>

</html>