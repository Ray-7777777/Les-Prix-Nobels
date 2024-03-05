<?php
    require "bd.php";
    $bdd = getBD();

    if(isset($_POST["recherche"]) && $_POST["recherche"] != ""){
        $mot = "";
        $url = "";
        $titre = "";
        $chaine = "";
        $compteur = "";
        $mots_cles = strtolower(utf8_decode($_POST["recherche"]));
        $mots_parasites = "avec|pour|dans|de|à";
        $mots_exclus= explode("|", $mots_parasites);
        $requete_and = "SELECT * FROM articles ";
        $filtres = $_POST["filtres"];

        $compteur = 0;
        foreach ($data as $category => $values) {
            foreach ($values as $value) {
                if($compteur == 0){
                    $requete_and .= "WHERE ";
                    $compteur = 1;
                }
                else{
                    $requete_and .= " AND ";
                }
                $requete_and .= $category;
                $requete_and .= "=";
                $requete_and .= $value;
            }
        }
        $compteur = 0;

        $requete_or = $requete_and;

        for($i=0;$i<sizeof($mots_exclus);$i++){
            $mots_cles=str_replace($mots_exclus[$i],"",$mots_cles);
        }

        $mots_cles_fin = explode(" ",$mots_cles);

        for($i=0; $i<sizeof($mots_cles_fin); $i++){
            $mot = rtrim($mots_cles_fin[$i], "s");
            if(strlen($mot)>3){
                if($compteur==0){
                    $requete_and .= "WHERE motscles LIKE '%".$mot."%' ";
                    $requete_or .= "WHERE motscles LIKE '%".$mot."%' ";
                }
                else{
                    $requete_and .= "AND motscles LIKE '%".$mot."%' ";
                    $requete_or .= "OR motscles LIKE '%".$mot."%' ";
                }
                $compteur++;
            }
        }

        $requete_and .= "LIMIT 0,25";

        $retour = $bdd->prepare($requete_and);
        $retour->execute();

        $resultat = $retour->fetchAll(PDO::FETCH_ASSOC);

        if(sizeof($resultat)==0){
            $retour = $bdd->prepare($requete_or);
            $retour->execute();

            $resultat = $retour->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode($resultat);

    }
?>