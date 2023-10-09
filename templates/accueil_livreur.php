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

$R = role($_SESSION["idUser"]);
if ($R != "L") header("Location:index.php?view=accueil");


?>
<script src="jquery-3.6.0.min.js"></script>
<script>
    function actualiser(evt) {
        var statut = evt.target.value;
        var idCommande = evt.target.id;

        //console.log(quantite);

        
        $.ajax({type: "POST",
            url: "libs/requetes.php",
            data: {requestType : "majCommande", valCommande : idCommande, valStatus : statut},
            success: function(oRep){
                console.log(oRep);
            }
        });
        
    }
</script>

<h1>Gérer les commandes</h1>
<h2>Liste de vos commandes à livrer</h2>

<div id="sectionProduits">
    <div id="sectionListe">
        <?php
            $commandes = listDeliveryOrder($_SESSION["idUser"]);

            echo "
            <table>
                <thead>
                    <tr>
                        <th>N° de commande</th>
                        <th>Nom</th>
                        <th>Prénom</th>
						<th>Adresse</th>
						<th>N° de téléphone</th>
						<th>Date de livraison</th>
						<th>Heure de livraison</th>
                        <th>Contenu</th>
						<th>Statut</th>
                    </tr>
                </thead> ";
                echo "
                <tbody id=\"produitsTab\">";
                foreach ($commandes as $data)
                {   
                    $N="";
                    $P="";
                    $E="";
                    $L="";
                    if($data["statut"]=="N") $N="selected";
                    else if($data["statut"]=="P") $P="selected";
                    else if($data["statut"]=="E") $E="selected";
                    else if($data["statut"]=="L") $L="selected";
                    echo "
                    <tr id=\"line$data[id]\">
						<td>$data[id]</td>
                        <td>$data[nom]</td>
						<td>$data[prenom]</td>
						<td>$data[adresse]</td>
						<td>$data[telephone]</td>
						<td>$data[date]</td>
						<td>$data[heure]</td>
                        ";
                    $contenu = listOrderContent($data["id"]);
                    echo "<td>";
                    foreach ($contenu as $prod){
                        echo "-$prod[nom]<br/>";
                    }
                    echo "</td>";
    
                    echo
                        "<td>
							<select id=\"$data[id]\" type='select' onchange=\"actualiser(event);\">
								<option value=\"N\" $N>En réparation</option>
								<option value=\"P\" $P>Préparée</option>
								<option value=\"E\" $E>En livraison</option>
								<option value=\"L\" $L>Livrée</option>
							</select>
						</td>
                    </tr>
                    ";
                }
                echo "
                </tbody>
            </table> ";
        ?>
    </div>
    <br />
    <div id="sectionAjouts">
    </div>
</div>
