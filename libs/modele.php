<?php

/*
Dans ce fichier, on définit diverses fonctions permettant de récupérer des données utiles pour notre TP d'identification. Deux parties sont à compléter, en suivant les indications données dans le support de TP
*/

include_once("maLibSQL.pdo.php");

////////// Users management ///////////
//Créer un utilisateur dans la table user et renvoies son id
function createUser($role, $nom, $firstName, $pass, $address, $email, $phone=""){
	$SQL = "INSERT INTO users(role,nom,prenom,pass,adresse,mail,telephone) VALUES('$role','$nom','$firstName','$pass','$address','$email','$phone')";
	return SQLInsert($SQL);
}
//Renvoyer toutes les informations à propos de l’utilisateur
function getUser($idUser){
	$SQL = "SELECT * FROM users WHERE id='$idUser'";
	$tab = parcoursRs(SQLSelect($SQL));
	// $tab contient au plus UNE SEULE CASE  
	if (count($tab) ==1) return $tab[0];
	else return false;
}
//Vérifier l’identité de l’utilisateur avec son mail et sont mot de passe
//renvoie son id si il existe, false sinon
function checkUser($email, $pass){
	$SQL="SELECT id FROM users WHERE mail='$email' AND pass='$pass'";
	return SQLGetChamp($SQL);
}
//Renvoyer le rôle de l’utilisateur, 0 si client, 1 si producteur, 2 si livreur
function role($idUser){
	$SQL="SELECT role FROM users WHERE id='$idUser'";
	return SQLGetChamp($SQL);
}
//Changer la valeur de la valeur $data pour l’utilisateur, elle ne retourne rien
function changeUserData($idUser, $data, $newData){
	$SQL ="UPDATE users SET $data='$newData' WHERE id='$idUser'"; 
	SQLUpdate($SQL);
}
//Changer les données à propos d’un utilisateur
//$data doit contenir un ensemble cle/valeur des données à changer et des nouvelles valeurs
function changerAllUserData ($idUser, $data){
	$SQL="UPDATE users SET";
	foreach($data as $cle=>$valeur) {
		if( !($cle=="pass" &  $valeur=="")) $SQL.=" $cle='$valeur',";
	}
	$SQL=substr($SQL, 0, -1);
	$SQL.=" WHERE id=$idUser";
	return SQLUpdate($SQL);
}
//Supprimer l’utilisateur
function deleteUser($idUser){
	$SQL = "DELETE FROM users WHERE id='$idUser'";
	SQLDelete($SQL);
}
//Lister l’ensemble des utilisateurs ou les utilisateurs d’un certain rôle
//en l’indiquant avec $role (C, P, L)
function listUser($role="all"){
	$SQL = "SELECT * FROM users";
	if ($role != "all") $SQL .= " WHERE role='$role'";
	return parcoursRs(SQLSelect($SQL));
}

////////// Products management ///////////
//Créer un produit dans la table produits et renvoies son id
function addNewProduct($idProducer, $name, $quantity, $lot){
	$SQL = "INSERT INTO produits(idProducteur,nom,quantiteDisponible,lot) VALUES('$idProducer','$name','$quantity','$lot')";
	return SQLInsert($SQL);
}
//Renvoyer toutes les informations à propos du produit
function getProduct($idProduct){
	$SQL = "SELECT * FROM produits WHERE id='$idProduct'";
	$tab = parcoursRs(SQLSelect($SQL));
	// $tab contient au plus UNE SEULE CASE  
	if (count($tab) ==1) return $tab[0];
	else return false;
}
//Renvoyer la quantité disponible du produit
function getQuantity($idProduct){
	$SQL="SELECT quantiteDisponible FROM produits WHERE id='$idProduct'";
	return SQLGetChamp($SQL);
}
//Modifier la quantité d’un produit disponible (ajout ou retrait)
//on suppose que la valeur ne fait pas passer en négatif
function changeQuantity($idProduct, $quantity){
	$newQuantity = $quantity;
	$SQL ="UPDATE produits SET quantiteDisponible='$newQuantity' WHERE id='$idProduct'"; 
	SQLUpdate($SQL);
}
//Renvoyer la liste de tous les produits. Si $mode="available", renvoyer la liste des produits en stock (quantité =/= 0)
function listProducts($mode="all", $idProducer="all"){
	$SQL = "SELECT P.id, P.idProducteur, U.adresse, P.nom, P.quantiteDisponible, P.lot FROM produits P join users U ON P.idProducteur=U.id"; 
	if ($mode == "available") $SQL .= " WHERE P.quantiteDisponible<>0";
	if ($idProducer != "all") $SQL .= " WHERE P.idProducteur=$idProducer";
	$SQL .= " ORDER BY P.nom ASC";
	return parcoursRs(SQLSelect($SQL)); 
}
//Supprimer le produit
function deleteProduct($idProduct){
	$SQL = "DELETE FROM produits WHERE id='$idProduct'";
	SQLDelete($SQL);
}

////////// Orders management ///////////
//Créer une commande dans la table, met son statut à N et renvoies son id
function addOrder($idCustomer, $idDelivery, $date, $hour){
	$SQL = "INSERT INTO commandes(idClient,idLivreur,date,heure,statut) VALUES('$idCustomer','$idDelivery','$date','$hour','N')";
	return SQLInsert($SQL);
}
//Renvoyer toutes les informations à propos d'une commande
function getOrder($idOrder){
	$SQL = "SELECT * FROM commandes WHERE id='$idOrder'";
	$tab = parcoursRs(SQLSelect($SQL));
	// $tab contient au plus UNE SEULE CASE  
	if (count($tab) ==1) return $tab[0];
	else return false;
}
//Changer le statut de la commande à $status
function changeStatus($idOrder, $status){
	$SQL ="UPDATE commandes SET statut='$status' WHERE id='$idOrder'"; 
	SQLUpdate($SQL);
}
//Lister les commandes d’un client
function listCustomerOrders($idCustomer){
	$SQL = "SELECT * FROM commandes WHERE idClient='$idCustomer'"; 
	return parcoursRs(SQLSelect($SQL)); 
}
//Lister les commandes d’un livreur
function listDeliveryOrder($idDelivery){
	$SQL = "SELECT C.id, C.idClient, C.idLivreur, C.date, C.heure, C.statut, U.nom, U.prenom, U.adresse, U.telephone FROM commandes C LEFT JOIN users U ON C.idClient=U.id WHERE C.idLivreur='$idDelivery'"; 
	$SQL .= " ORDER BY C.date ASC";
	return parcoursRs(SQLSelect($SQL)); 
}
//Supprimer la commande et son contenu de la table contenu
function deleteOrder($idOrder){
	$SQL = "DELETE FROM commandes WHERE id='$idOrder'";
	SQLDelete($SQL);
}

////////// Content management ///////////
//Ajouter un produit à une commande
function fillOrder($idOrder, $idProduct){
	$SQL = "INSERT INTO contenu(idCommande,idProduit) VALUES('$idOrder','$idProduct')";
	return SQLInsert($SQL);
}
//Lister le contenu de la commande
function listOrderContent($idOrder){
	$SQL = "SELECT C.idCommande, C.idProduit, P.nom, P.lot FROM contenu C JOIN produits P ON C.idProduit=P.id WHERE C.idCommande='$idOrder'"; 
	$SQL .= " ORDER BY P.nom ASC";
	return parcoursRs(SQLSelect($SQL)); 
}
?>
