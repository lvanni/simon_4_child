<?php 
session_start();

if (isset($_SESSION["auth"])) {
	if (isset($_POST["disconnect"])) {
		unset($_SESSION["auth"]);
	} else {
		if (isset($_POST["filename"])) {
			//echo "results/" . $_POST["filename"];
			unlink("results/" . $_POST["filename"]);
		}
	}
} else if (isset($_POST["login"]) && isset($_POST["pass"])) {
	if ($_POST["login"] == "admin" && md5($_POST["pass"]) == "81dc9bdb52d04dc20036dbd8313ed055") { # pass => 1234
		$auth = true;
		$_SESSION["auth"] = true;
	} else {
		$auth = false;
	}
} 


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes">
<title>Simon | Flash version</title>

<link href="../lib/foundation-6/css/foundation.min.css" rel="stylesheet" media="all" type="text/css">
<link href="../lib/foundation-6/css/app.css" rel="stylesheet" media="all" type="text/css">
<link href="style.css" rel="stylesheet" media="all" type="text/css"> 

<script src="../lib/jquery.min.js"></script>
<script src="../lib/foundation-6/js/foundation.min.js"></script>
<script src="../lib/foundation-6/js/app.js"></script>
<script src="../lib/jquery.flip.min.js"></script>
<script src="../lib/fastclick.js"></script>
<script type="application/javascript">
	window.addEventListener('load', function () {
		FastClick.attach(document.body);
	}, false);
</script> 
</head>
<body>

	<?php if (isset($_SESSION["auth"])) { ?>

		<h2>Résulats :</h2>
		<table>
		<?php 
		//$files = scandir('results/');
		$cpt = 0;
		foreach(glob("results/*.csv") as $file) {
			$file = str_replace("results/", "", $file);
			if ($file != "." && $file != "..") { ?>
					<tr>
						<td>
							<a href="results/<?php echo $file ?>"><?php echo $file ?></a>
						</td>
						<td>
							<a href="results/<?php echo $file ?>" class="tiny success button">Voir</a>
						</td>
						<td>
							<form action="." method="post">
								<input type="hidden" name="filename" value="<?php echo $file ?>" />
								<input type="submit" class="tiny alert button" value="Effacer" />
							</form>
						</td>
					</tr>
				<?php
			}
		} ?>	
		</table>
		
		<form action="" method="post">
			<input type="hidden" name="disconnect" value="1" />
			<input type="submit" value="Quitter" />
		</form>
	
	<?php } else { ?>
	
		<h2>Authentification requise :</h2>
		<form action="" method="POST">
			<input type="text" name="login" placeholder="login" /><br />
			<input type="password" name="pass" placeholder="password" /><br />
			<?php if (isset($auth) && !$auth ) { ?>
			<span style="color:red;">Mauvais mot de passe ou login...</span><br />
			<?php } ?>
			<input type="submit" value="envoyer" />
		</form>
		
	<?php } ?>
</body>
</html>