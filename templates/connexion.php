<?php 

if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:index.php?view=connexion");
	die("");
}

?>


<h1>Connexion</h1>
<?php

if (valider("message","REQUEST"))
{
  echo "<h3>".$_REQUEST["message"]."</h3>";
}
?>

<script>

function show(){
  refMdp = document.getElementById("inputMdp");
  if (refMdp.type === "password") {
      refMdp.type = "text";
  } else {
      refMdp.type = "password";
  }
}
  
</script>



<form action="controleur.php" method="GET">
      
    <input type="email" name="userMail"  autocomplete="off" required/>
    <label for ="login">E-mail</label>
    <br/>
    <input type="password" name="passe" id="inputMdp" required/>
    <label for ="login">Mot de passe</label>

    <input type="checkbox" id="check" class="check" onchange="show()">
    <label for ="check">Afficher</label>
    
    <br/>
    <input type="submit" class="btn" name="action" value="Connexion">
    <a href="./index.php?view=inscription" class="aDecorate">Inscription</a>

</form>


