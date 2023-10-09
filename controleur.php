<?php
session_start();

	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/maLibSecurisation.php"; 
	include_once "libs/modele.php";
	include_once "libs/config_mail.php";

	$qs = "";

	if ($action = valider("action"))
	{
		ob_start ();

		echo "Action = '$action' <br />";

		// Un paramètre action a été soumis, on fait le boulot...
		switch($action)
		{
			
			// Connexion //////////////////////////////////////////////////
			case 'Connexion' :
				if ($email = valider("userMail"))
					if ($pass = valider("passe"))

						if ($idUser = checkUser($email, $pass)){
							$qs="?view=accueil";
							////
							$dataUser = getUser($idUser);
							$_SESSION["connecte"] = true;
							$_SESSION["idUser"] = $idUser;
							$_SESSION["role"] = $dataUser["role"];
							$_SESSION["prenom"] = $dataUser["prenom"];
							/////
						}
						else
							$qs="?view=connexion&message=".urlencode("Erreur sur login/mot de passe");
                

			break;

			case 'Deconnexion':
				session_destroy();
				$qs = "?view=connexion";
				echo "test";
			break;

			case "Inscription" :
				
				if ($role = valider("role")){
					if ($nom = valider("nom")){
						if ($prenom = valider("prenom")){
							if ($email = valider("userMail")){
								if ($pass = valider("passe")){
									
									if ($tel = valider("tel")){
										if ($address = valider("adresse")){
											createUser($role, $nom, $prenom, $pass, $address, $email,$tel);
											$qs="?view=connexion";

											$message = "
											<h1>Bienvenue $prenom</h1>
											<p>Merci d'avoir créé un compte chez Leguvraison!</p>
											";

                                            if($mailActivate)
											    mail($email, "Création de compte", $message,'Content-type: text/html');


										}	
									}}}}}}
			break;


		}

	}

	// On redirige toujours vers la page index, mais on ne connait pas le répertoire de base
	// On l'extrait donc du chemin du script courant : $_SERVER["PHP_SELF"]
	// Par exemple, si $_SERVER["PHP_SELF"] vaut /chat/data.php, dirname($_SERVER["PHP_SELF"]) contient /chat

	$urlBase = dirname($_SERVER["PHP_SELF"]) . "/index.php";
	// On redirige vers la page index avec les bons arguments

	header("Location:" . $urlBase . $qs);
	//qs doit contenir le symbole '?'

	// On écrit seulement après cette entête
	ob_end_flush();
	
?>










