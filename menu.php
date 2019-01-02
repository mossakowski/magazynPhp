<nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-between">
	<a class="navbar-brand font-weight-bold" href="#">STAS - System traktujący<br> anomalie stanowe</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item active">
				<a class="nav-link" href="index.php">Stany <span class="sr-only">(current)</span></a>
			</li>
			<li class="nav-item active">
				<a class="nav-link" href="historia.php">Historia <span class="sr-only">(current)</span></a>
			</li>
		</ul>
	</div>
	<span>
	<?php
		echo "Witaj <b>".$_SESSION['login']."</b>";
		echo ' <a href="?wyloguj=1">[Wyloguj]</a>';
		?>
	</span>
</nav>
