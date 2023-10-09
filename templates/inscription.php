<?php 
//redirection en cas d'accès direct a la template
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:index.php?view=accueil");
	die("");
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

<h1>Inscription</h1>

<form action="controleur.php" method="GET">
    <input type="text" name="nom" required/>
    <label for ="nom" class="label-name">
      Nom
    </label>
    <br/>
    <input type="text" name="prenom" required/>
    <label for ="prenom" class="label-name">
      Prénom
    </label>
    <br/>
    <input type="text" name="userMail" required/>
    <label for ="userMail" class="label-name">
      E-mail
    </label>
    <br/>
    <input type="text" name="tel" required/>
    <label for ="tel" class="label-name">
      N° de téléphone
    </label>
    <br/>
    <input type="text" name="adresse"  required/>
    <label for ="adresse" class="label-name">
      Adresse
    </label>
    <br/>
    <input type="password" name="passe" id="inputMdp" required/>
    <label for ="login">Mot de passe</label>

    <input type="checkbox" id="check" class="check" onchange="show()">
    <label for ="check">Afficher</label>

    <br/>
    <input type="radio" name="role" id="C" value="C" required/>
    <label for ="C">
      Client
    </label>
    <br/>
    <input type="radio" name="role" id="P" value="P" required/>
    <label for ="P">
      Producteur
    </label>
    <br/>
    <input type="radio" name="role" id="L" value="L" required/>
    <label for ="L">
      Livreur
    </label>
    <br/>
    <input type="submit" name="action" value="Inscription">
</form>

