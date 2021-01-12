<?php
$errormsg = isset($_SESSION['error'])
?>
<div class="container">

	<form action="./util/loginprocess.php" method="post" id="login-form" class="">
		<h2>Login</h2>
		<div class="form-group">
			<div class="row container">
				<label for="userid" class="col-sm-2">Phone Number</label>
				<input type="text" name="userid" id="userid" class="form-control col-sm-8" value="<?php echo @$_SESSION['error']['passwd'] ?>" required>
			</div>
		</div>
		<div class="form-group">
			<div class="row container">
				<label for="passwd" class="col-sm-2">Password</label>
				<input type="password" name="passwd" id="passwd" class="form-control col-sm-8" value="<?php echo @$_SESSION['error']['passwd'] ?>" required>
			</div>
		</div>
		<button class="btn btn-primary" type="submit">Submit</button>
	</form>
</div>