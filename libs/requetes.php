<?php
    include "config.php";
    include "modele.php";
    include_once "maLibUtils.php";
    include_once "config_mail.php";

    $data = array("app"=>"leguvraison");

    if($requestType = valider("requestType")){
        $data["success"]=true;
        switch($requestType){
            case "ajoutProduit":
                $producteur = $_POST["valUser"];
                $legume = $_POST["valLegume"];
                $lot = $_POST["valLot"];
                $quantite = $_POST["valQuantite"];

                $idNewProduct = addNewProduct($producteur, $legume, $quantite, $lot);

                $data["id"] = $idNewProduct;
                $data["nom"] = $legume;
                $data["idProducteur"] = $producteur;
                $data["quantiteDisponible"] = $quantite;
                $data["lot"] = $lot;
                
                $data["adresse"] = getUser($producteur)["adresse"];

            break;

            case "reaprovisionner":
                $produit = $_POST["valProduit"];
                $quantite = $_POST["valQuantite"];
            
                changeQuantity($produit, $quantite);

                $data["id"] = $produit;
                $data["quantiteDisponible"] = $quantite;
            break;

            case "majCommande":
                $commande = $_POST["valCommande"];
                $status = $_POST["valStatus"];
            
                changeStatus($commande, $status);

                $data["id"] = $commande;
                $data["statut"] = $status;
            break;

            case "ajoutCommande":
                $client = $_POST["valClient"];
                $date = $_POST["valDate"];
                $heure = $_POST["valHeure"];
                $produits = $_POST["valProduits"];

                $listLivreur = listUser($role="L");

                $commandes = listDeliveryOrder($listLivreur[0]["id"]);
                $min = count($commandes);
                $idLivreur = $listLivreur[0]["id"];
                foreach ($listLivreur as $livreur){
                    $commandes = listDeliveryOrder($livreur["id"]);
                    $nbCommandes = count($commandes);
                    if($min>$nbCommandes){
                        $min = $nbCommandes;
                        $idLivreur = $livreur["id"];
                    }
                }


                if ($idNewOrder = addOrder($client, $idLivreur, $date, $heure)){
                    $userData = getUser($client);


                    $message = "
                    <h1>Merci pour votre commande!</h1>
                    <p>Vous recevrez votre commande le $date à $heure à l'adresse $userData[adresse]</p>
                    <p>Votre commande contient:</p>
                    <ul>
                    ";

                    foreach ($produits as $produit)
                    {
                        fillOrder($idNewOrder, $produit);
                        $qtt = getQuantity($produit);
                        changeQuantity($produit, $qtt-1);

                        $productData = getProduct($produit);
                        $message = $message."<li>$productData[nom]</li>";
                    }
                    $message = $message."</ul>";

                    $data["id"] = $idNewOrder;
                    $data["idClient"] = $client;
                    $data["idLivreur"] = $idLivreur;
                    $data["date"] = $date;
                    $data["heure"] = $heure;

                    $data["produits"] = $produits;

                    $data["nom"] = $userData["nom"];
                    $data["prenom"] = $userData["prenom"];
                    $data["adresse"] = $userData["adresse"];
                    $data["mail"] = $userData["mail"];
                    $data["telephone"] = $userData["telephone"];

                    if ($mailActivate)
                        mail($userData["mail"], "Commande", $message,'Content-type: text/html');

                } else $data["message"]="impossible de passer la commande";
            break;

            case "majCoordonnees":
                $idUser=$_POST["idUser"];
                $newData=[
                   'nom'=>$_POST['nom'],
                   'prenom'=>$_POST['prenom'],
                   'adresse'=>$_POST['adresse'],
                   'pass'=>$_POST['pass'],
                   'mail'=>$_POST['mail'],
                   'telephone'=>$_POST['telephone'] 
                ];
                    
                    $test=changerAllUserData($idUser,$newData);
    
                    $data["id"] = $idUser;
                    //$data["dataToChange"] = $dataToChange;
                    $data["newData"] = $newData;
            break;

            case "supprimerUser":
                    $idUser=$_POST["idUser"];
                    deleteUser($idUser);
            break;

            default:
                $data["message"]="mauvaise requete"; 
        }

    } else {
        $data["success"]=false;
    }

    echo json_encode($data);

?>