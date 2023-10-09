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
if ($R != "V") header("Location:index.php?view=accueil");
?>

<h1>Gérer les légumes</h1>
<h2>Liste de vos légumes</h2>
<script src="jquery-3.6.0.min.js"></script>
<script>
    var inputNouveauLegume = $("<input id='nouveauLegume' type='text' placeholder='Légume'/>")
    var inputContenuLot = $("<input id='contenuLot' type='text' placeholder='Contenu du lot'/>")
    var inputQuantiteDisponible = $("<input id='quantiteDisponible' type='number' step='1' min='0'>").val(0)

    var inputBtnAjout = $("<input name='btnAjout' type='submit' value='Ajouter le légume'>").click(function() {
        console.log("click Ajouter");

        var legume = $("#nouveauLegume").val();
        var lot = $("#contenuLot").val();
        var quantite = $("#quantiteDisponible").val();

        if (legume == "" || lot == "" || quantite == 0) {
            alert("Veuillez terminer votre saisie");
        }
        else {
            idUser = $("#idUser").html();

            $.ajax({type: "POST",
                url: "libs/requetes.php",
                data: {requestType : "ajoutProduit", valUser : idUser, valLegume : legume, valLot : lot, valQuantite : quantite},
                success: function(oRep){
                    console.log(oRep);
                    var tab = JSON.parse(oRep);
                    $("#produitsTab").append("<tr id=\"line" + tab.id + "\"><td>" + tab.nom + "</td><td>" + tab.lot + "</td><td><input id=\""+ tab.id +"\" type='number' step='1' min='0' value=\"" + tab.quantiteDisponible + "\" onchange=\"actualiser(event);\"></td></tr>");
                }
            });
        }
    });

    var divSaisie = $("<div>")
                        .append(inputNouveauLegume)
                        .append("<br/>")
                        .append(inputContenuLot)
                        .append("<br/>")
                        .append(inputQuantiteDisponible)
                        .append("Quantité")
                        .append("<br/>")
                        .append(inputBtnAjout);

    function actualiser(evt) {
        var quantite = evt.target.value;
        var idProduit = evt.target.id;

        console.log(quantite);

        if (quantite != null)
        {
            $.ajax({type: "POST",
                url: "libs/requetes.php",
                data: {requestType : "reaprovisionner", valProduit : idProduit, valQuantite : quantite},
                success: function(oRep){
                    console.log(oRep);
                }
            });
        }
    }

    $(document).ready(function(){
        $("#sectionAjouts").before(divSaisie.clone(true));
    });
</script>

<div id="sectionProduits">
    <div id="sectionListe">
        <?php
            $products = listProducts($mode="all", $idProducer=$_SESSION["idUser"]);

            echo "
            <table>
                <thead>
                    <tr>
                        <th>Légume</th>
                        <th>Lot</th>
                        <th>Quantité disponible</th>
                    </tr>
                </thead> ";
                echo "
                <tbody id=\"produitsTab\">";
                foreach ($products as $data)
                {
                    echo "
                    <tr id=\"line$data[id]\">
                        <td>$data[nom]</td>
                        <td>$data[lot]</td>
                        <td><input id=\"$data[id]\" type='number' step='1' min='0' value=\"$data[quantiteDisponible]\" onchange=\"actualiser(event);\"></td>
                    </tr>
                    ";
                }
                echo "
                </tbody>
            </table> ";
        ?>
    </div>
    <hr/>
    <h2>Ajouter un légume</h2>
    <div id="sectionAjouts">
    </div>
</div>