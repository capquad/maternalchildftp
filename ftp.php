<?php
session_start();
require('./lib/server/authorize.php');

$user = authorizeLogin(); // check if user is logged in

require('./lib/db/db.php');
require('./lib/db/database.php');
require('./lib/config/pagesetup.php');

require('./lib/util/header.php');
$db = new Database(DBHOST, DBUSER, DBPASS);
$db->connect(DBNAME);
?>

<main>
	<div class="container py-3">
		<h2>File Transfer</h2>
		<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequuntur consequatur illum delectus eos illo, ducimus deleniti minus. Nulla deleniti et atque mollitia provident quasi in, iusto necessitatibus quam placeat eveniet!</p>
	</div>
	<div class="container pb-5">
		<div class="row">
			<div class="col-md-6 mb-4 mb-sm-0">
				<form action="/lib/server/upload.php" method="post" enctype="multipart/form-data" id="file-upload-form">
					<div class="form-group">
						<label for="file">Select File</label>
						<input type="file" name="files" multiple class="form-control" required>
					</div>
					<div class="form-group">
						<label for="recipient">Recipient</label>
						<select name="recipient" id="recipient" class="form-control" required>
							<option disabled="disabled" selected>Select Recipient</option>
							<?php
							$db->select("staff", "phone, fname, lname", "phone != '$user'");
							$result = $db->getResults();
							if (count($result) > 0) {
								foreach($result as $staff) {
									$staff['name'] = $staff['fname']." ".$staff['lname'];
									echo "<option value='".$staff['phone']."'>".$staff['name']."</option>";
								}
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<button type="submit" name="upload" class="btn btn-primary" id="upload-btn">Submit</button>
						<button type="reset" class="btn btn-danger" id="reset-btn">Reset</button>
					</div>
				</form>
			</div>
			<div class="col-md-6">
				<div class="text-right">
					<button class="btn btn-dark">Refresh</button>
				</div>
				<h3>Received Files</h3>
				<table id="received-files" class="table table-bordered mb-4">
					<thead>
						<th>Sender</th>
						<th>File</th>
						<th></th>
					</thead>
					<tbody id="received-list"></tbody>
				</table>
				<h3>Sent Files</h3>
				<table id="received-files" class="table table-bordered">
					<thead>
						<th>Sender</th>
						<th>File</th>
						<th></th>
					</thead>
					<tbody id="received-list"></tbody>
				</table>
			</div>
		</div>
	</div>
</main>

<?php
require('./lib/util/footer.php');
?>

<script src="/custom/js/upload.js"></script>

</html>