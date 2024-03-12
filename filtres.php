<?php
    require "bd.php";
    $bdd = getBD();

    if ($_POST["id"] == "Bgenre"){
        $req = $bdd->prepare("SELECT Gender FROM nomine GROUP BY Gender");
        $req->execute();
    }
    elseif ($_POST["id"] == "Bnationalite"){
        $req = $bdd->prepare("SELECT `Born_country` FROM nomine GROUP BY `Born_country`");
        $req->execute();
    }
    $resultat = $req->fetchAll();
    echo json_encode($resultat);
?>