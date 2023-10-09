<?php 
//redirection en cas d'accès direct a la template
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:index.php?view=accueil");
	die("");
}

include_once "libs/maLibUtils.php";



if(valider("prenom","SESSION") && valider("connecte","SESSION")) $name = valider("prenom","SESSION") ;

// On envoie l'entête Content-type correcte avec le bon charset
header('Content-Type: text/html;charset=utf-8');

// Pose qq soucis avec certains serveurs...
echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8\" />
	<title>Leguvraison</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300&display=swap" rel="stylesheet">
</head>
<body>
	<div id="header">
		<a href="index.php?view=accueil" id="headerHome">
			<img src="ressources/home.png" id="imageHome" alt="home">
		</a>

		<a href="index.php?view=accueil" id="headerLogo">
			<img src="ressources/logo.png" id="imageLogo" alt="logo">
		</a>

		<div id="headerUser">
		<?php
			if(valider("connecte","SESSION")){
				echo "<div id=\"idUser\" class=\"hidden\">$_SESSION[idUser]</div>";
				echo "<div id=\"role\" class=\"hidden\">$_SESSION[role]</div>";

				echo "<a href=\"index.php?view=profil\" class=\"aDecorate\">$name</a>";

				echo "<form action=\"controleur.php\" method=\"POST\">";
				echo "<input type=\"submit\" name=\"action\" value=\"Deconnexion\">";
				echo "</form>";
			}
			/*else{
				echo "<input type=\"button\" id=\"headerConnexionButton\" value=\"Connexion\">";
			}*/
		?>
		</div>

	</div>
