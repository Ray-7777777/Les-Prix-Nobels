<?php
    require_once "bd.php";
    $bdd = getBD();

    if ($_POST["action"] == "menu") {
        if ($_POST["id"] == "Gender") {
            $req = $bdd->prepare("SELECT `Gender` FROM nomine GROUP BY Gender");
        } 
        else if ($_POST["id"] == "Année") {
            $req = $bdd->prepare("SELECT `Année` FROM prix_nobel GROUP BY `Année` ORDER BY `Année` ASC");
        } 
        else if ($_POST["id"] == "Category") {
            $req = $bdd->prepare("SELECT `Nom_catégorie` FROM categorie");
        } 
        else if ($_POST["id"] == "Naissance") {
            $req = $bdd->prepare("SELECT `Born_country` FROM nomine GROUP BY `Born_country`");
        } 
        else if ($_POST["id"] == "Décès") {
            $req = $bdd->prepare("SELECT `Died_country` FROM nomine GROUP BY `Died_country`");
        } 
        elseif ($_POST["id"] == "NombrePrix") {
        }

        $req->execute();
        $resultat = [];
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $resultat[] = $row;
        }
    }
    elseif ($_POST["action"] == "tracer") {
        if ($_POST["catX"] == "Gender" || $_POST["catY"] == "Gender") {
            if ($_POST["catX"] == "Année" || $_POST["catY"] == "Année") {
                $req = $bdd->prepare("SELECT p.Année, COUNT(*), n.Gender AS Nombre FROM prix_nobel p JOIN nomine n ON p.id_nominé = n.id_nominé GROUP BY p.Année, n.Gender ORDER BY p.Année, n.Gender;");
            } elseif ($_POST["catX"] == "Category" || $_POST["catY"] == "Category") {
                $req = $bdd->prepare("SELECT c.Nom_catégorie, COUNT(*), n.Gender AS Nombre FROM prix_nobel p JOIN nomine n ON p.id_nominé = n.id_nominé JOIN categorie c ON p.id_category = c.Id_catégorie GROUP BY c.Nom_catégorie, n.Gender ORDER BY c.Nom_catégorie, n.Gender;");
            } elseif ($_POST["catX"] == "Naissance" || $_POST["catY"] == "Naissance") {
                $req = $bdd->prepare("SELECT n.Born_country, COUNT(*), n.Gender AS Nombre FROM nomine n GROUP BY n.Gender, n.Born_country ORDER BY n.Born_country;");
            } elseif ($_POST["catX"] == "Décès" || $_POST["catY"] == "Décès") {
                $req = $bdd->prepare("SELECT n.Died_country, COUNT(*), n.Gender AS Nombre FROM nomine n GROUP BY n.Gender, n.Died_country ORDER BY n.Died_country;");
            } else {
                $req = $bdd->prepare("SELECT Gender, COUNT(*) AS Nombre FROM nomine GROUP BY Gender;");
            }
        } elseif ($_POST["catX"] == "Année" || $_POST["catY"] == "Année") {
            if ($_POST["catX"] == "Category" || $_POST["catY"] == "Category") {
                $req = $bdd->prepare("SELECT c.Nom_catégorie, COUNT(*), p.Année AS Nombre FROM prix_nobel p JOIN categorie c ON p.id_category = c.id_catégorie GROUP BY c.Nom_catégorie, p.Année ORDER BY c.Nom_catégorie, p.Année;");
            } elseif ($_POST["catX"] == "Naissance" || $_POST["catY"] == "Naissance") {
                $req = $bdd->prepare("SELECT n.Born_country, COUNT(*), p.Année AS Nombre FROM nomine n JOIN prix_nobel p ON n.id_nominé = p.id_nominé GROUP BY n.Born_country, p.Année ORDER BY p.Année, n.Born_country;");
            } elseif ($_POST["catX"] == "Décès" || $_POST["catY"] == "Décès") {
                $req = $bdd->prepare("SELECT n.Died_country, COUNT(*), p.Année AS Nombre FROM nomine n JOIN prix_nobel p ON n.id_nominé = p.id_nominé GROUP BY n.Died_country, p.Année ORDER BY p.Année, n.Died_country;");
            } else {
                $req = $bdd->prepare("SELECT Année, COUNT(*) AS Nombre FROM prix_nobel GROUP BY Année");
            }
        } elseif ($_POST["catX"] == "Category" || $_POST["catY"] == "Category") {
            if ($_POST["catX"] == "Naissance" || $_POST["catY"] == "Naissance") {
                $req = $bdd->prepare("SELECT n.Born_country, COUNT(*), c.Nom_catégorie AS Nombre_de_Prix FROM prix_nobel p JOIN nomine n ON p.id_nominé = n.id_nominé JOIN categorie c ON p.id_category = c.id_catégorie GROUP BY n.Born_country, c.Nom_catégorie ORDER BY c.Nom_catégorie, n.Born_country;");
            } elseif ($_POST["catX"] == "Décès" || $_POST["catY"] == "Décès") {
                $req = $bdd->prepare("SELECT n.Died_country, COUNT(*), c.Nom_catégorie AS Nombre_de_Prix FROM prix_nobel p JOIN nomine n ON p.id_nominé = n.id_nominé JOIN categorie c ON p.id_category = c.id_catégorie GROUP BY n.Died_country, c.Nom_catégorie ORDER BY c.Nom_catégorie, n.Died_country;");
            } else {
                $req = $bdd->prepare("SELECT c.Nom_catégorie, COUNT(p.id_category) AS Nombre_de_Prix FROM categorie c LEFT JOIN prix_nobel p ON c.id_catégorie = p.id_category GROUP BY c.Nom_catégorie;");
            }
        } elseif ($_POST["catX"] == "Naissance" || $_POST["catY"] == "Naissance") {
            if ($_POST["catX"] == "Décès" || $_POST["catY"] == "Décès") {
                $req = $bdd->prepare("SELECT n1.Born_country AS Pays_de_naissance, COUNT(*) AS Nombre_de_Prix, n2.Born_country AS Pays_de_décès FROM prix_nobel p JOIN nomine n1 ON p.id_nominé = n1.id_nominé JOIN nomine n2 ON p.id_nominé = n2.id_nominé GROUP BY n1.Born_country, n2.Born_country ORDER BY n1.Born_country, n2.Born_country;");
            } else {
                $req = $bdd->prepare("SELECT n.Born_country AS Pays_de_naissance, COUNT(*) AS Nombre_de_prix FROM prix_nobel p JOIN nomine n ON p.id_nominé = n.id_nominé GROUP BY n.Born_country ORDER BY n.Born_country;");
            }
        } elseif ($_POST["catX"] == "Décès" || $_POST["catY"] == "Décès") {
            $req = $bdd->prepare("SELECT n.Died_country AS Pays_de_naissance, COUNT(*) AS Nombre_de_prix FROM prix_nobel p JOIN nomine n ON p.id_nominé = n.id_nominé GROUP BY n.Died_country ORDER BY n.Died_country;");
        }
        $req->execute();
        $resultat = [];
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $resultat[] = $row;
        }
    }
    $bdd = null;
    echo json_encode($resultat);
?>