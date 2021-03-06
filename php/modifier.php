<?php
require_once "identifiants.php";
require_once "fonctionauthentification.php";
$connexion = mysqli_init();
$connexion->options(MYSQLI_CLIENT_SSL, 'SET AUTOCOMMIT = 0');
$connexion->real_connect($host,$username,$passwd,$dbname);
$connexion->query("SET NAMES utf8mb4");

if(testAuthentification($connexion)==="Authentification valide"){
    $nomfichier=hash("sha256", uniqid());
    $idproduit=htmlspecialchars(mysqli_real_escape_string($connexion, $_POST["idproduit"]));
    $nom=htmlspecialchars(mysqli_real_escape_string($connexion, $_POST['inputnom']));
    $marque=htmlspecialchars(mysqli_real_escape_string($connexion, $_POST['inputmarque']));
    $video=htmlspecialchars(mysqli_real_escape_string($connexion, $nomfichier.$_FILES['inputimages']['name']));
    $videonom=htmlspecialchars(mysqli_real_escape_string($connexion, $_FILES['inputimages']['name']));
    $prix=htmlspecialchars(mysqli_real_escape_string($connexion, $_POST['inputprix']));
    $devise=htmlspecialchars(mysqli_real_escape_string($connexion, $_POST['inputdevise']));
    $descriptions=htmlspecialchars(mysqli_real_escape_string($connexion, $_POST['inputcaracteristique']));
    $quantite=htmlspecialchars(mysqli_real_escape_string($connexion, $_POST['inputquantite']));
    $types=htmlspecialchars(mysqli_real_escape_string($connexion, $_POST['inputcategorie']));
    $conditions=htmlspecialchars(mysqli_real_escape_string($connexion, $_POST['condition']));
    $coordonnees=htmlspecialchars(mysqli_real_escape_string($connexion, $_POST['coordonnees']));
    $nomLieu=htmlspecialchars(mysqli_real_escape_string($connexion, $_POST['nomlieu']));
    $lattitude=htmlspecialchars(mysqli_real_escape_string($connexion, $_POST['lattitude']));
    $longitude=htmlspecialchars(mysqli_real_escape_string($connexion, $_POST['longitude']));
    $details=htmlspecialchars(mysqli_real_escape_string($connexion, $_POST['details']));
    
    $typeVideo=$_FILES['inputimages']['type'];
    $tailleVideo=$_FILES['inputimages']['size'];
    
    function testDonneesAvecVideos($nom, $marque, $typeVideo, $tailleVideo, $prix, $devise, $descriptions, $quantite, $types, $conditions, $coordonnees, $nomLieu) {
        if(
            preg_match("#[\W \w]#", $nom) AND 
            preg_match("#[\W \w]#", $marque) AND 
            ($typeVideo==="video/mp4" OR 
            $typeVideo==="video/3gpp" OR 
            $typeVideo==="video/mpeg" OR 
            $typeVideo==="video/ogg" OR 
            $typeVideo==="video/quicktime" OR 
            $typeVideo==="video/webm" OR 
            $typeVideo==="video/x-m4v" OR 
            $typeVideo==="video/ms-asf" OR 
            $typeVideo==="video/x-ms-wmv" OR 
            $typeVideo==="video/x-msvideo") AND 
            $tailleVideo<100000000 AND 
            preg_match("#[0-9]#", $prix) AND 
            preg_match("#[\W \w]#", $devise) AND 
            preg_match("#[\W \w]#", $descriptions) AND 
            preg_match("#[0-9]#", $quantite) AND 
            preg_match("#[\W \w]#", $types) AND 
            preg_match("#[\W \w]#", $conditions) AND 
            preg_match("#[\W \w]#", $coordonnees) AND 
            preg_match("#[\W \w]#", $nomLieu)
        ){
            return "conforme";
        }else{
            return "nonconforme";
        }
    }
    
    function testDonneesSansVideos($nom, $marque, $typeVideo, $prix, $devise, $descriptions, $quantite, $types, $conditions, $coordonnees, $nomLieu) {
        if(
            preg_match("#[\W \w]#", $nom) AND 
            preg_match("#[\W \w]#", $marque) AND 
            $typeVideo==="" AND 
            preg_match("#[0-9]#", $prix) AND 
            preg_match("#[\W \w]#", $devise) AND 
            preg_match("#[\W \w]#", $descriptions) AND 
            preg_match("#[0-9]#", $quantite) AND 
            preg_match("#[\W \w]#", $types) AND 
            preg_match("#[\W \w]#", $conditions) AND 
            preg_match("#[\W \w]#", $coordonnees) AND 
            preg_match("#[\W \w]#", $nomLieu)
        ){
            return "conforme";
        }else{
            return "nonconforme";
        }
    }
    
    function modificationDonneesFichiers($nomFichierUpload, $nomfichier, $connexion, $idproduit, $nom, $marque, $video, $videonom, $prix, $devise, $descriptions, $quantite, $types, $conditions, $coordonnees, $nomLieu, $lattitude, $longitude, $details){
        if(suppressionFichier($nomFichierUpload, $connexion, $idproduit)==="fichier supprime"){
            $requete=" UPDATE produits SET nom = '$nom', marque = '$marque', video = '$video', videonom = '$videonom', prix = '$prix', devise = '$devise', 
            descriptions = '$descriptions', quantite = '$quantite', types = '$types', conditions = '$conditions', coordonnees = '$coordonnees', nomlieu = '$nomLieu', lattitude = '$lattitude', longitude = '$longitude', details = '$details'    
            WHERE '$idproduit'= idproduit ";
            $requetesql = $connexion->query("$requete");
            if($requetesql){
                $envoifichier=move_uploaded_file($_FILES['inputimages']['tmp_name'], $nomFichierUpload.$nomfichier.basename($_FILES['inputimages']['name']));
                if($envoifichier){
                    echo "Produit modifie";
                }else{
                    echo "echec";
                }
            }else{
                echo "echec";
            }
        }else{
            echo "echec ";
        };
    };
    
    function suppressionFichier($nomFichierUpload, $connexion, $idproduit) {
        $requeteSuppression="SELECT * FROM produits WHERE '$idproduit'= idproduit ";
        $requetesqlSuppression=$connexion->query("$requeteSuppression");
        while($resultat=mysqli_fetch_object($requetesqlSuppression)){
            $unlink=unlink($nomFichierUpload.basename("$resultat->video"));
            if($unlink){
                return "fichier supprime";
            }
        }
    }
    
    function modificationDonneesSansVideo($connexion, $idproduit, $nom, $marque, $prix, $devise, $descriptions, $quantite, $types, $conditions, $coordonnees, $nomLieu, $lattitude, $longitude, $details){
        $requete=" UPDATE produits SET nom = '$nom', marque = '$marque', prix = '$prix', devise = '$devise', 
        descriptions = '$descriptions', quantite = '$quantite', types = '$types', conditions = '$conditions', coordonnees = '$coordonnees', nomlieu = '$nomLieu', lattitude = '$lattitude', longitude = '$longitude', details = '$details'  
        WHERE '$idproduit'= idproduit ";
        $requetesql = $connexion->query("$requete");
        if($requetesql){
            echo "Produit modifie";
        }else{
            echo "echec";
        }
    }
    
    if(
        testDonneesAvecVideos($nom, $marque, $typeVideo, $tailleVideo, $prix, $devise, $descriptions, $quantite, $types, $conditions, $coordonnees, $nomLieu)==="conforme"
    ){
        //suppressionFichier($connexion, $idproduit);
        modificationDonneesFichiers($nomFichierUpload, $nomfichier, $connexion, $idproduit, $nom, $marque, $video, $videonom, $prix, $devise, $descriptions, $quantite, $types, $conditions, $coordonnees, $nomLieu, $lattitude, $longitude, $details);
    }elseif(
        testDonneesSansVideos($nom, $marque, $typeVideo, $prix, $devise, $descriptions, $quantite, $types, $conditions, $coordonnees, $nomLieu)==="conforme"
    ){
        modificationDonneesSansVideo($connexion, $idproduit, $nom, $marque, $prix, $devise, $descriptions, $quantite, $types, $conditions, $coordonnees, $nomLieu, $lattitude, $longitude, $details);
    }else{
        echo "echec";
    }
}else{
    echo "Vous n'etes pas connecte";
}

$connexion->close();
?>