<?php
session_start();

include_once "libs/maLibUtils.php";

$view = valider("view");

//pour débuger
/*$_SESSION["connecte"] = true;
$_SESSION["idUser"] = 7;
$_SESSION["role"] = 'P';
$_SESSION["nom"] = 'Nom';*/
if(!valider("connecte","SESSION") & $view!="inscription") $view="connexion";
///////////////////////////////////////////////////////

include("templates/header.php");

echo "<div class=\"corp\">";
if (file_exists("templates/$view.php"))
    include("templates/$view.php");
  else
    echo "mauvaise view";
echo "</div>";

include("templates/footer.php");

?>