<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="<?= @BOOTSTRAPCSS ?>">
	<link rel="stylesheet" href="/custom/style.css">
	<title>Maternal-Child FTP</title>

	<script src="<?= @JQUERY ?>"></script>
	<script src="<?= @BOOTSTRAPJS ?>"></script>
</head>

<body>
	<header class="bg-primary">
		<div class="container">
			<h1 class="text-light py-3">Maternal-Child Specialists' Clinics</h1>
		</div>
		<?php
		if ($_SERVER['SCRIPT_NAME'] !== '/login.php' && $_SERVER['SCRIPT_NAME'] !== '/signup.php') {
			echo <<<_
			<nav class="navbar navbar-expand-sm navbar-dark">
				<div class="container">
					<button class="navbar-toggler" type="button" data-target="#navbar" data-toggle="collapse">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div id="navbar" class="navbar-collapse collapse">
						<ul class="navbar-nav">
							<li class="nav-item"><a href="/" class="nav-link">Home</a></li>
							<li class="nav-item"><a href="/ftp.php" class="nav-link">FTP</a></li>
							<li class="nav-item"><a href="/profile.php" class="nav-link">Profile</a></li>
						</ul>
						<ul class="navbar-nav ml-auto">
							<li class="nav-item">
								<a href="/logout.php" class="nav-link">Log Out</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>

_;
		}
		?>
	</header>