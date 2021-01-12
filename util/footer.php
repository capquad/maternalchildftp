		<footer>
			<div class="container">
				<p class="copyright">Copyright Maternal-Child Specialists' Clinics &copy; 2020</p>
			</div>
		</footer>
	</main>
	<?php
		if (@$loggedin) {
			echo "<script src='../js/index.js'></script>";
			if ($_SERVER['SCRIPT_NAME'] == "/files.php") {
				echo "<script src='../js/filehandling.js'></script>";
			}
		}

	?>
</body>
</html>