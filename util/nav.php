	<nav class="navbar bg-dark navbar-dark navbar-expand-sm">
		<a href="../" class="navbar-brand">Home</a>
		<?php

		if (@$loggedin) {
			?>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbar">
			<ul class="navbar-nav">
				<li class="nav-item"><a href="../files.php" class="nav-link">FTP</a></li>
				<li class="nav-item"><a href="../noticeboard.php" class="nav-link">Notice Board</a></li>
				<!-- <li class="nav-item"><a href="#" class="nav-link">Link 3</a></li> -->
				<!-- <li class="nav-item"><a href="#" class="nav-link">Link 4</a></li> -->
				<li class="nav-item"><a href="../loguot.php" class="nav-link">Logout</a></li>
			</ul>

			<ul class="navbar-nav mr-0">
				<li class="nav-item"><a href="../loguot.php" class="nav-link"></a></li>
			</ul>
		</div>
		<?php
	}
	?>
	</nav>
</header>