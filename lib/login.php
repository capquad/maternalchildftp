<main>
	<div class="container py-4">
		<h2 class="text-black-50 text-center">Login</h2>
		<div id="login-form">
			<?php
			if (@$_SESSION['login_error']) {
				$error = $_SESSION['login_error'];
				echo "
				<div class='alert alert-danger alert-dismissible'>
					<button class='close' data-dismiss='alert'>&times;</button>
					<p>$error</p>
				</div>
				";
				unset($_SESSION['login_error']);
			}
			?>
			<form action="" method="post">
				<div class="form-group">
					<label for="userid">Phone Number</label>
					<input type="text" id="userid" class="form-control" name="userid" required>
				</div>
				<div class="form-group">
					<label for="passwd">Password</label>
					<input type="password" id="passwd" class="form-control" name="password" required>
				</div>
				<div class="form-group">
					<button type="submit" name="login" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</main>