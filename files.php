<?php
include_once('./util/header.php');
if (!$loggedin) {
	header('Location: ../');
}
?>
<!-- Main page content -->
<h1 class="container m-3">File Transfer Portal: <?= $self['des'] ?></h1>
<!-- <p><?= $user ?></p> -->
<div class="container my-5">
	<div class="row">
		<div class="col-md-6">
			<h2>Send Files</h2>
			<form action="./process/filetransfer.php" method="post" enctype="multipart/form-data" class="p-3" id="fileform">

				<!-- File Title Input Section: //IGNORE FOR NOW -->
				<!-- <div class="form-group row">
					<label for="title" class="col-sm-2">Title:</label>
					<div class="col-sm-8 p-0">
						<input type="text" name="title" id="title" class="form-control" required>
						<p class="font-italic text-danger">A Title for Your File</p>
					</div>
				</div> -->

				<div class="form-group row">
					<label for="files" class="col-sm-2">Select File:</label>
					<input type="file" name="files[]" id="files" class="form-control col-sm-8" multiple required>
				</div>
				<div class="form-group row">
					<label for="recipients" class="col-sm-2">Recipients:</label>
					<div class="col-sm-8 p-0">
						<select name="recipients" id="recipients" class="form-control" size="3" multiple>
							<?php
							$db->select('staff', 'id, phone, fname, lname, designation as des, officeid as depid', "phone != '$user'");
							$staff = $db->getResults();
							if (isset($staff[0])) {
								foreach ($staff as $arr1 => $arr2) {
									if ($arr2['depid'] !== $self['depid']) {
										echo "<option value='" . $arr2['id'] . "'>" . $arr2['fname'] . " " . $arr2['lname'] . "</option>";
									}
								}
							} else {
								if ($staff['depid'] !== $self['depid']) {
									echo "<option value='" . $staff['id'] . "'>" . $staff['fname'] . " " . $staff['lname'] . "</option>";
								}
							}
							// print_r($staff);
							?>
						</select>
						<p class="font-italic text-danger">Note: Ctrl + Click To select more than one</p>
					</div>
				</div>

				<button class="btn btn-primary" type="submit" id="fileFormBtn">Submit</button>
				<button class="btn btn-danger" type="reset">Reset</button>
			</form>
		</div>
		<div class="col-md-6 pt-5 pt-md-0" style='height:300px; overflow-y: scroll'>
			<div>
				<div class="row">
					<div class="col">
						<h2>Received Files</h2>
					</div>
					<div class="col">
						<button class="btn btn-dark" id="refresh">Refresh</button>
					</div>
				</div>
				<table class="table m-0 p-0">
					<thead>
						<tr>
							<th>Sender</th>
							<th>File</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="rec-files-table"></tbody>
				</table>
			</div>

			<div class='mt-4'>
				<h2>Sent Files</h2>
				<table class="table m-0 p-0">
					<thead>
						<tr>
							<th>Recipient</th>
							<th>File</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="sent-files-table"></tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>

<?php
include_once('./util/footer.php');
