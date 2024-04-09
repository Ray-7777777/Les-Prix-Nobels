<?php
function recommander_prix_nobel_similaires($id_prix_nobel_actuel, $k = 3) {
    // Connexion à la base de données
    $conn = getBD(); // Assurez-vous d'implémenter correctement cette fonction selon votre configuration de base de données

    // Récupérer les attributs de l'article actuel
    $sql_attributes = "SELECT Année, id_category, Motivation, Prénom, Nom, Date_de_naissance, Date_de_mort, Born_country,Born_city, Died_country,Died_city, Gender, biographie, mots_cles, nom_organisation, ville_organisation, pays_organisation 
                       FROM prix_nobel, nomine, organisation  
                       WHERE id_prix_nobels = :id_prix_nobel 
                       AND prix_nobel.id_nominé=nomine.Id_nominé 
                       AND prix_nobel.id_organisation=organisation.id_organisation";
    $stmt_attributes = $conn->prepare($sql_attributes);
    $stmt_attributes->bindParam(':id_prix_nobel', $id_prix_nobel_actuel, PDO::PARAM_INT);
    $stmt_attributes->execute();
    $attributes = $stmt_attributes->fetch(PDO::FETCH_ASSOC);

    // Calculer la similarité avec les autres prix Nobel
    $sql_similar_nobels = "SELECT id_prix_nobels, 
                            (POW(Année - :annee, 2) + POW(id_category - :id_category, 2) + POW(Date_de_naissance - :date_naissance, 2) + POW(Date_de_mort - :date_mort, 2) + POW(Gender - :genre, 2) + POW(Born_country - :born_country, 2) + POW(Born_city - :born_city, 2) + POW(Died_country - :died_country, 2) + POW(Died_city - :died_city, 2) + POW(Nom - :nom, 2) + POW(Motivation - :motivation, 2) + POW(biographie - :biographie, 2) + POW(mots_cles - :mots_cles, 2) + POW(nom_organisation - :nom_organisation, 2) + POW(ville_organisation - :ville_organisation, 2) + POW(pays_organisation - :pays_organisation, 2)) AS similarity 
                            FROM prix_nobel 
                            JOIN nomine ON prix_nobel.id_nominé = nomine.Id_nominé
                            JOIN organisation ON prix_nobel.id_organisation = organisation.id_organisation
                            WHERE id_prix_nobels != :id_prix_nobel
                            ORDER BY similarity ASC 
                            LIMIT :k";
    $stmt_similar_nobels = $conn->prepare($sql_similar_nobels);
    $stmt_similar_nobels->bindParam(':annee', $attributes['Année'], PDO::PARAM_INT);
    $stmt_similar_nobels->bindParam(':id_category', $attributes['id_category'], PDO::PARAM_INT);
    $stmt_similar_nobels->bindParam(':date_naissance', $attributes['Date_de_naissance'], PDO::PARAM_STR);
    $stmt_similar_nobels->bindParam(':date_mort', $attributes['Date_de_mort'], PDO::PARAM_STR);
    $stmt_similar_nobels->bindParam(':genre', $attributes['Gender'], PDO::PARAM_STR);
    $stmt_similar_nobels->bindParam(':born_country', $attributes['Born_country'], PDO::PARAM_STR);
    $stmt_similar_nobels->bindParam(':born_city', $attributes['Born_city'], PDO::PARAM_STR);
    $stmt_similar_nobels->bindParam(':died_country', $attributes['Died_country'], PDO::PARAM_STR);
    $stmt_similar_nobels->bindParam(':died_city', $attributes['Died_city'], PDO::PARAM_STR);
    $stmt_similar_nobels->bindParam(':nom', $attributes['Nom'], PDO::PARAM_STR);
    $stmt_similar_nobels->bindParam(':motivation', $attributes['Motivation'], PDO::PARAM_STR);
    $stmt_similar_nobels->bindParam(':biographie', $attributes['biographie'], PDO::PARAM_STR);
    $stmt_similar_nobels->bindParam(':mots_cles', $attributes['mots_cles'], PDO::PARAM_STR);
    $stmt_similar_nobels->bindParam(':nom_organisation', $attributes['nom_organisation'], PDO::PARAM_STR);
    $stmt_similar_nobels->bindParam(':ville_organisation', $attributes['ville_organisation'], PDO::PARAM_STR);
    $stmt_similar_nobels->bindParam(':pays_organisation', $attributes['pays_organisation'], PDO::PARAM_STR);
    $stmt_similar_nobels->bindParam(':id_prix_nobel', $id_prix_nobel_actuel, PDO::PARAM_INT);
    $stmt_similar_nobels->bindParam(':k', $k, PDO::PARAM_INT);
    $stmt_similar_nobels->execute();
    $similar_nobels = $stmt_similar_nobels->fetchAll(PDO::FETCH_ASSOC);


    // Fermer la connexion à la base de données
    $conn = null;

    // Renvoyer les prix Nobel similaires
    return $similar_nobels;
}




function recommander_prix_nobel_historique_similaires($id_utilisateur, $k = 3) {
    // Connexion à la base de données
    $conn = getBD(); // Assurez-vous d'implémenter correctement cette fonction selon votre configuration de base de données

    // Récupérer les attributs de l'article actuel
    $sql_attributes = "SELECT id_prix_nobel FROM historiquepages WHERE id_utilisateur = :id_utilisateur";
    $stmt_attributes = $conn->prepare($sql_attributes);
    $stmt_attributes->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
    $stmt_attributes->execute();
    $historique = $stmt_attributes->fetch(PDO::FETCH_ASSOC);



    // Calculer la similarité avec les autres prix Nobel
    $sql_similar_nobels = "SELECT id_prix_nobels, 
                                  (POW(Année - :annee, 2) + POW(id_category - :id_category, 2)) AS similarity 
                           FROM prix_nobel 
                           WHERE id_prix_nobels != :id_prix_nobel
                           ORDER BY similarity ASC 
                           LIMIT :k";
    $stmt_similar_nobels = $conn->prepare($sql_similar_nobels);
    $stmt_similar_nobels->bindParam(':annee', $attributes['Année'], PDO::PARAM_STR);
    $stmt_similar_nobels->bindParam(':id_category', $attributes['id_category'], PDO::PARAM_INT);
    $stmt_similar_nobels->bindParam(':id_prix_nobel', $id_prix_nobel_actuel, PDO::PARAM_INT);
    $stmt_similar_nobels->bindParam(':k', $k, PDO::PARAM_INT);
    $stmt_similar_nobels->execute();
    $similar_nobels = $stmt_similar_nobels->fetchAll(PDO::FETCH_ASSOC);

    // Fermer la connexion à la base de données
    $conn = null;

    // Renvoyer les prix Nobel similaires
    return $similar_nobels;
}

?>

