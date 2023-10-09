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
?> 

<h1>Mon Compte</h1>
<div id='infosUsers' class="box">
    <ul id="ulInfos">
        <?php 
            include_once("libs/modele.php");
            include_once("libs/maLibUtils.php");
            $idUser=$_SESSION['idUser'];
            $infosUser=getUser($idUser);
            // tprint($infosUser);
            echo "<li>Nom: ".$infosUser['nom']."</li>";
            echo "<li>Prenom: ".$infosUser['prenom']."</li>";
            echo "<li>E-mail: ".$infosUser['mail']."</li>";
            //echo "<li>Mot de passe : ".str_repeat ('*', strlen ($infosUser["pass"]))."</li>"; 
            //y a t-il a un meilleur moyen pour cacher le mdp ?
            echo "<li>Adresse: ".$infosUser['adresse']."</li>";
            echo "<li>N° de téléphone: ".$infosUser['telephone']."</li>";

        ?> 
    </ul>
    <input type="button" id="btnModifier" value="Modifier"/>
    <input type="button" id="btnDeconnexion" value="Deconnexion"/>
    <input type="button" id="btnSupprimer" value="Supprimer"/>
</div>
<hr/>
<div id="suiviDeCommandes">
    <?php
        if($infosUser["role"]=='C') {
            echo "<h2>Suivi de commandes</h2>";
            $commandes=listCustomerOrders($idUser);
            echo "
        <table >
            <thead>
                <tr>
                    <th>N° de commande</th>
                    <th>Date de livraison</th>
                    <th>Heure de livraison</th>
                    <th>Statut</th>
                    <th>Contenu</th>
                </tr>
            </thead> ";
            echo "
            <tbody id=\"suiviCommandesTab\">";
            foreach ($commandes as $commande)
            {
                echo "
                <tr id=\"line$commande[id]\">
                    <td>$commande[id]</td>
                    <td>$commande[date]</td>
                    <td>$commande[heure]</td>
                ";
                
                switch($commande["statut"]) {
                    case "N":
                        echo "<td>En préparation</td>";
                    break;
                    case "P":
                        echo "<td>Préparée</td>";
                    break;
                    case "E":
                        echo "<td>En livraison</td>";
                    break;
                    case "L":
                        echo "<td>Livrée</td>";
                    break;

                }
                $contenu = listOrderContent($commande["id"]);
                echo "<td>";
                foreach ($contenu as $prod){
                    echo "$prod[nom]<br/>";
                }
                echo "</td>";

                echo"</tr>";
            }
            echo "
            </tbody>
        </table> ";
        }
    
    ?>
</div>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>

<script type="text/javascript"> 
infosUser=<?php echo json_encode($infosUser);?>; 
// ça marche mais est-ce qu'il y a un meilleur moyen de récupérer les infos
//  sans faire une requête une deuxième fois ?
// sachant que dans la page html j'ai pas toutes les infos, il me manque le mdp avec ma méthode
// tout faire en jQuery ? faire la première requête en ajax ?
$(document).ready(function(){
    $("#btnModifier").click(function (){
        console.log("btnModifier click");
        jUlInfos=$("#ulInfos");
        jFormModif=$("<form id='formModif'>").prop("action","libs/requetes.php");
        jInputText=$("<input/>").prop('type','texte');
        jLabel=$("<label>");
        
        ajoutInputText("inputNom","nom","nom","Nom",jFormModif);

        ajoutInputText("inputPrenom","prenom","prenom","Prenom",jFormModif);

        ajoutInputText("inputMail","mail","mail","Mail",jFormModif);

        jInputMdp=$("<input type='password'/>").attr('id', 'inputMdp').prop('name','pass')
        //     .val(infosUser['pass']);
        jFormModif.append(jInputMdp);
        jFormModif.append(jLabel.clone(true).prop("for","inputMdp").text('Mot de passe'));
        

        jShowMdp=$("<input type='checkbox'/>").attr('id','showMdp').click(function(){
            //permet d'afficher ou cacher le mdp
            // source : https://www.w3schools.com/howto/howto_js_toggle_password.asp
            refMdp = document.getElementById("inputMdp");
            if (refMdp.type === "password") {
                refMdp.type = "text";
            } else {
                refMdp.type = "password";
            }
        });
        
        jFormModif.append(jShowMdp);
        jFormModif.append(jLabel.clone(true).prop("for","showMdp")
            .text('Afficher'),$('<br/>'));
    
        ajoutInputText("inputAdresse","adresse","adresse","Adresse",jFormModif);

        ajoutInputText("inputTelephone","telephone","telephone","Telephone",jFormModif);

        jInputIdUser=$("<input type='hidden'/>").prop("name","idUser").val(infosUser["id"]);
        jFormModif.append(jInputIdUser);
        $("#btnModifier").hide();
        $("#btnSupprimer").hide();
        $("#btnDeconnexion").hide();

        jBtnEnregistrer=$("<input type='button'>").attr('id','btnEnregistrer').val("Enregistrer")
            .click(function (){
                formArray=($('form').serializeArray());
                console.log(formArray);
                $.post("libs/requetes.php?requestType=majCoordonnees",formArray,function(data,status){
                    <?php $_SESSION["prenom"] = getUser($_SESSION["idUser"])["prenom"]?>;
                    document.location.reload();
                
                    // jUlInfos.empty();
                    // jLi=$("<li>");
                    // jUlInfos.append(jLi.clone(true).text("Nom : "+formArray[0]['value']));
                    // jUlInfos.append(jLi.clone(true).text("Prenom : "+formArray[1]['value']));
                    // jUlInfos.append(jLi.clone(true).text("Adresse mail : "+formArray[2]['value']));
                    // jUlInfos.append(jLi.clone(true).text("Mot de passe : "+"*".repeat(formArray[3]['value'].length)));
                    // jUlInfos.append(jLi.clone(true).text("Adresse : "+formArray[4]['value']));
                    // jUlInfos.append(jLi.clone(true).text("Telephone : "+formArray[5]['value']));



                    // jFormModif.replaceWith(jUlInfos);
                    // $("#btnModifier").show();
                    // $("#btnSupprimer").show();
                    // $("#btnDeconnexion").show();
                })
            });
        jBtnAnnuler=$("<input type='button'>").attr('id','btnAnnuler').val("Annuler")
            .click(function(){
                console.log("btnAnnuler click")
                jFormModif.replaceWith(jUlInfos);
                $("#btnModifier").show();
                $("#btnSupprimer").show();
                $("#btnDeconnexion").show();
            })

        jFormModif.append(jBtnEnregistrer,jBtnAnnuler);

        jUlInfos.replaceWith(jFormModif);
    });
    $("#btnSupprimer").click(function(){
        console.log('btnSupprimer click');
        $.post("libs/requetes.php?requestType=supprimerUser",{"idUser":infosUser['id']},function(data,status){
            console.log(data);
            window.location.href='controleur.php?action=Deconnexion';
        })
    });
    $("#btnDeconnexion").click(function(){
        console.log('btnDeconnexion click');
        window.location.href='controleur.php?action=Deconnexion';
    });
});


function ajoutInputText (idName, name, champData, label, formToAppend){
    //ajoute un input text au form formToAppend
    //cet input aura comme id : idName; name : name; sa valeur sera celle contenue dans le champ champData de infosUser
    jInputText=$("<input/>").prop('type','texte');
    jLabel=$("<label>");
    jInput=jInputText.clone(true).attr('id', idName).prop('name',name).val(infosUser[champData]);
    formToAppend.append(jInput);

    formToAppend.append(jLabel.clone(true).prop("for",idName).text(label),$('<br/>'));
}

</script>