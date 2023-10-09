<?php 
//redirection en cas d'accès direct a la template
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:index.php?view=accueil");
	die("");
}
?>
<div id="footer">
	<div>
	Leguvraison
	</div>
</div>

</body>
</html>