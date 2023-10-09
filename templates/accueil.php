<?php
//redirection en cas d'accès direct a la template
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:index.php?view=accueil");
	die("");
}

include_once("libs/modele.php"); // listes
include_once("libs/maLibUtils.php");// tprint
include_once("libs/maLibForms.php");// mkTable, mkLiens, mkSelect ..

error_reporting(E_ALL); // & ~E_NOTICE & ~E_WARNING);

if ($_SESSION["role"]=='C') include("templates/accueil_client.php");
else if ($_SESSION["role"]=='P') include("templates/accueil_vendeur.php");
else if ($_SESSION["role"]=='L') include("templates/accueil_livreur.php");
?>
