<?php 
	session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>LSE Inventar</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body class="loc">
	<header class="header">
		<h1 class="title">Locatii LSE</h1>
		<div class="menu-wrapper">
			<?php include("menu.php"); ?>
		</div>
	</header>
	
	<section class="wrapper">
		<div class="left">
			
		</div>
		<div class="right">
			<form action="" method="POST" id="add_loc" name="add_loc">
				<h2>Adauga locatie</h2>
				<p>Nume Locatie</p>
				<p><input type="text" name="loc_name" placeholder="Sediu x"></p>
				<br/>
				<p>Adresa Locatie</p>
				<p><input type="text" name="loc_addr" placeholder="str iuliu maniu"></p>
				<br/>
				<p><input type="submit" name="add_loc_sub" class="submit-button" value="Adauga"></p>
			</form>
		</div>
	</section>
	
	<footer>
		
	</footer>
</body>
</html>