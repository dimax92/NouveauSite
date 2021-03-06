<?php
require_once "identifiants.php";
$connexion = mysqli_init();
$connexion->options(MYSQLI_CLIENT_SSL, 'SET AUTOCOMMIT = 0');
$connexion->real_connect($host,$username,$passwd,$dbname);
$connexion->query("SET NAMES utf8mb4");

$requete="SELECT * FROM produits";

$requetesql=$connexion->query("$requete");
while($resultat=mysqli_fetch_object($requetesql)){
    $paramidproduit=json_encode("idproduit");
    $paramnom=json_encode("nom");
    $parammarque=json_encode("marque");
    $paramvideo=json_encode("video");
    $paramprix=json_encode("prix");
    $paramdevise=json_encode("devise");
    $paramdescriptions=json_encode("descriptions");
    $paramquantite=json_encode("quantite");
    $paramtypes=json_encode("types");
    $paramconditions=json_encode("conditions");
    $paramlattitude=json_encode("lattitude");
    $paramlongitude=json_encode("longitude");

    $idproduit=json_encode($resultat->idproduit);
    $nom=json_encode($resultat->nom);
    $marque=json_encode($resultat->marque);
    $video=json_encode($resultat->video);
    $prix=json_encode($resultat->prix);
    $devise=json_encode($resultat->devise);
    $descriptions=json_encode($resultat->descriptions);
    $quantite=json_encode($resultat->quantite);
    $types=json_encode($resultat->types);
    $conditions=json_encode($resultat->conditions);
    $lattitude=json_encode($resultat->lattitude);
    $longitude=json_encode($resultat->longitude);
    echo " didi: { $paramidproduit: $idproduit, 
    $paramnom: $nom, 
    $parammarque: $marque, 
    $paramvideo: $video, 
    $paramprix: $prix, 
    $paramdevise: $devise, 
    $paramdescriptions: $descriptions, 
    $paramquantite: $quantite, 
    $paramtypes: $types, 
    $paramconditions: $conditions, 
    $paramlattitude: $lattitude, 
    $paramlongitude: $longitude
 }";
};
$connexion->close();
?>