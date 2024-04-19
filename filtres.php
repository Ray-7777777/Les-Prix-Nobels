<?php
    //Connection à la base de données
    require_once "bd.php";
    $bdd = getBD();

    //Requête à la base de données en fonction du type de filtre selectionné
    if ($_POST["id"] == "Bgenre"){
        $req = $bdd->prepare("SELECT Gender FROM nomine GROUP BY Gender");
        $req->execute();
    }
    elseif ($_POST["id"] == "Bnationalite"){
        $req = $bdd->prepare("SELECT `Born_country` FROM nomine GROUP BY `Born_country`");
        $req->execute();
    }
    $resultat = $req->fetchAll();
    //Déconnection de la base de données
    $bdd = null;
    echo json_encode($resultat);
?>