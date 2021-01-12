<?php
include_once('./util/header.php');
// include_once('./util/nav.php');
if (!$loggedin) {
	include_once('./util/login.php');
	exit();
}
?>
<!-- Main page content -->
<div class="container my-3">
	<h1>
		<?php
		if ($self) {
			echo "Office of the: " . $self['des'];
		}
		?>
	</h1>
</div>
<div class="container mt-5">
	<div class="row">
		<div class="col-md-4 col-sm-12">
			<div class="card mb-4">
				<div class="card-header">
					<h3 class="text-danger">File Transfer</h3>
				</div>
				<div class="card-body">
					<p>Transfer files confidentially from one personnel to the other.</p>
				</div>
				<a href="../files.php" class="card-footer">
					Click here to Enter
				</a>
			</div>
		</div>

		<div class="col-md-4 col-sm-12">
			<div class="card mb-4">
				<div class="card-header">
					<h3 class="text-danger">Notice Board</h3>
				</div>
				<div class="card-body">
					<p>View all communications and information in and around the facility.</p>
				</div>
				<a href="#" class="card-footer">
					Click here to Enter
				</a>
			</div>
		</div>

		<div class="col-md-4 col-sm-12">
			<div class="card mb-4">
				<div class="card-header">
					<h3 class="text-danger">Department Info</h3>
				</div>
				<div class="card-body">
					<p>View and edit your department information.<br /></p>
				</div>
				<a href="#" class="card-footer">
					Click here to Enter
				</a>
			</div>
		</div>
	</div>
</div>

<?php
include_once('./util/footer.php');
