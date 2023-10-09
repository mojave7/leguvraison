<?php 
//redirection en cas d'accès direct a la template
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:index.php?view=accueil");
	die("");
}
include_once("libs/modele.php"); 
include_once("libs/maLibUtils.php");
include_once("libs/maLibForms.php");

$R = role($_SESSION["idUser"]);
if ($R != "C") header("Location:index.php?view=accueil");

$tabLegumes = listProducts($mode="available", $idProducer="all")


?>

<h1>Passer une commande</h1>
<h2>Liste des légumes disponibles</h2>
<p>Vous pouvez selectionner jusqu'à trois produits que vous ne désirez pas<br/>Votre commande comportera trois produits aléatoires parmis les restants</p>
<div id="commande">
	<?php 
		foreach($tabLegumes as $data){
			
			echo "<div id=\"accueilClientLegume$data[id]\" class=\"box prod\" >";
			//echo "<img src=\"../ressources/$data[nom].png\" alt=\"legume$data[id] : $data[nom]\">";
			echo "
			<div>
			<h3> $data[nom] </h3>
			<h4> $data[lot] </h4>
			</div>";
			echo "
			<div>
			<h5 class=\"$data[id]\"> $data[quantiteDisponible] </h5>
			disponible
			</div>";
			echo "
			<div class=\"container\">
			<input type=\"checkbox\" id=\"$data[id]\" name=\"$data[id]\" class=\"check\" onchange=\"countCheck()\">
			<span class=\"checkmark\"></span>
			</div>";

			echo "</div>";
		}
		$date = date('Y-m-d', time()+3600*24);
		$dateMax = date('Y-m-d', time()+3600*24*7);

	?>
	<hr/>
	<h2>Validation</h2>
	<p>Veuillez choisir une date de livraison </p>
	<input type="date" name="dateLivraison" min="<?=$date?>"  max="<?=$dateMax?>">
	<input type="time" name="timeLivraison" min="09:00" max="18:00">
	<p> Vous pouvez commander seulement 7 jours à l'avance</br>Les livraisons se font entre 9h et 18h</p>
</div>

<script src="jquery-3.6.0.min.js"></script>
<script>

//vérifie le nombre de prosuits supprimés
function countCheck(){
	//console.log(event);
	console.log($(".check:checked"));
	if($(".check:checked").length>3){
		$(event.target).prop('checked', false);
	}
}

var taillePanier = 3;

var envoyer =  $("<input type='button' value='Commander'>")
	.click(function(){
		console.log("envoyer");
		date = $("input[name=dateLivraison]").val();

		dateMin = $("input[name=dateLivraison]").attr("min");
		console.log(dateMin);

		dateMax = $("input[name=dateLivraison]").attr("max");
		console.log(dateMax);
		
		heure = $("input[name=timeLivraison]").val();
		heureMin = $("input[name=timeLivraison]").attr("min");
		heureMax = $("input[name=timeLivraison]").attr("max");

		dateF = new Date(date);
		dateFMin = new Date(dateMin);
		dateFMax = new Date(dateMax);
		

		heureF = new Date("2000-01-01 "+heure);
		heureFMin = new Date("2000-01-01 "+heureMin);
		heureFMax = new Date("2000-01-01 "+heureMax);

		if(date != "" && (dateF>=dateFMin) && (dateF<=dateFMax) ){
			if(heure != "" && (heureF>=heureFMin) && (heureF<=heureFMax) ){
				console.log(date+heure);

				disabledProducts = $(".check:checked");
				console.log(disabledProducts);

				tab = [];
				//On récupere la liste des produits que l'utilisateur peut avoir
				disabledProducts.each(function(index){
					console.log(disabledProducts[index].id);
					tab.push(disabledProducts[index].id);
				});
				console.log(tab);

				commande = makePanier(tab);

				idUser = $("#idUser").html();

				if(commande.length) {

					$.ajax({type: "POST",
						url: "libs/requetes.php",
						data: {requestType : "ajoutCommande",
						valClient : idUser,
						valDate : date,
						valHeure : heure,
						valProduits : commande},
						success: function(oRep){
							console.log(oRep);
							rep = JSON.parse(oRep);
							
							//On affiche un message de validation et on redirige vers le profil
							alert("Votre commande à bien été prise en compte");
							window.location.href='index.php?view=profil';
						}
					});
				} else alert("Votre commande est vide");
			} else alert("L'horaire n'est pas valide");
		} else alert("La date n'est pas valide");

	});

$("#commande").append(envoyer);

//crée un panier
function makePanier(disProd){
	result = [];

	qtt = $("h5");

	tabQtt = [];
	tabId = []
	qttTotale = 0;
	qtt.each(function(index){
		if(!disProd.includes(qtt[index].className)){
			console.log(qtt[index].innerHTML);
			qttTotale += parseInt(qtt[index].innerHTML)
			tabQtt.push(parseInt(qtt[index].innerHTML));
			tabId.push(qtt[index].className);
		}
	});
	console.log(tabQtt);
	console.log("qtt totale "+qttTotale);

	//Si il ne reste plus assez de produits
	taillePanierF = Math.min(taillePanier, qttTotale);
	console.log(taillePanier);
	console.log(qttTotale);
	console.log("taille "+taillePanierF);
	//On choisit les différents produits aléatoirment
	for(let n = 0; n < taillePanierF; n++){
		rdm = Math.random()*qttTotale
		console.log(rdm);

		nb = 0;
		for(let i = 0; i < tabQtt.length; i++) {
			nb += tabQtt[i];
			//console.log(nb);
			if(rdm<=nb){
				result.push(tabId[i])
				qttTotale -= 1;
				tabQtt[i] -= 1;
				break;
			}
		}
	}

	console.log(result);
	return result;
}

</script>





