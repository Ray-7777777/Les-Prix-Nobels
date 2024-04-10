<?php
function recommander_prix_nobel_similaires($id_prix_nobel_actuel, $k = 3) {
    // Connexion à la base de données
    $conn = getBD(); // Assurez-vous d'implémenter correctement cette fonction selon votre configuration de base de données

    // Récupérer les attributs de l'article actuel
    $sql_attributes = "SELECT Année, id_category, Date_de_naissance, Date_de_mort, Born_country, Born_city, Died_country, Died_city, Gender, biographie, mots_cles, nom_organisation, ville_organisation, pays_organisation 
                       FROM prix_nobel, nomine, organisation  
                       WHERE id_prix_nobels = :id_prix_nobel 
                       AND prix_nobel.id_nominé=nomine.Id_nominé 
                       AND prix_nobel.id_organisation=organisation.id_organisation";
    $stmt_attributes = $conn->prepare($sql_attributes);
    $stmt_attributes->bindParam(':id_prix_nobel', $id_prix_nobel_actuel, PDO::PARAM_INT);
    $stmt_attributes->execute();
    $attributes = $stmt_attributes->fetch(PDO::FETCH_ASSOC);

    // Convertir les attributs en JSON pour les transmettre à la fonction Python
    $attributes_json = json_encode($attributes);
    file_put_contents('input.json', $attributes_json);

    // Appeler la fonction Python avec les attributs en tant qu'argument
    $python_command = "python recommander_prix_nobel.py input.json 2>&1";
    $output = shell_exec($python_command);

    // Lire le fichier JSON de sortie
    $output_json = file_get_contents('output.json');

    // Décoder le JSON en tableau associatif
    $output_data = json_decode($output_json, true);

    // Extraire les indices recommandés
    $recommended_indices = $output_data['recommended_indices'];

    return $recommended_indices;
}





function recommander_prix_nobel_historique_similaires($id_utilisateur, $k = 3) {
    // Connexion à la base de données
    $conn = getBD(); // Assurez-vous d'implémenter correctement cette fonction selon votre configuration de base de données

    // Récupérer les attributs de l'article actuel
    $sql_attributes = "SELECT id_prix_nobel FROM historiquepages WHERE id_utilisateur = :id_utilisateur";
    $stmt_attributes = $conn->prepare($sql_attributes);
    $stmt_attributes->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
    $stmt_attributes->execute();
    $historique = $stmt_attributes->fetchAll(PDO::FETCH_ASSOC); // Utilisation de fetchAll au lieu de fetch


    // Si l'historique est vide, retourner un tableau vide
    if (empty($historique)) {
        return [];
    }

    // Supprimer les doublons de l'historique
    $historique_unique = array_unique(array_column($historique, 'id_prix_nobel'));

    // Initialiser un tableau pour stocker les informations sur les prix Nobel en historique
    $historique_info = [];

    // Parcourir chaque prix Nobel en historique pour récupérer ses informations
    foreach ($historique_unique as $prix_nobel_id) {
        // Récupérer les attributs du prix Nobel...
    }


    // Parcourir chaque prix Nobel en historique pour récupérer ses informations
    foreach ($historique as $prix_nobel_id) {

        // Récupérer les attributs du prix Nobel
        $sql_attributes = "SELECT Année, id_category, Date_de_naissance, Date_de_mort, Born_country, Born_city, Died_country, Died_city, Gender, biographie, mots_cles, nom_organisation, ville_organisation, pays_organisation 
                        FROM prix_nobel, nomine, organisation  
                        WHERE id_prix_nobels = :id_prix_nobel 
                        AND prix_nobel.id_nominé=nomine.Id_nominé 
                        AND prix_nobel.id_organisation=organisation.id_organisation";
        $stmt_attributes = $conn->prepare($sql_attributes);
        $stmt_attributes->bindParam(':id_prix_nobel', $prix_nobel_id['id_prix_nobel'], PDO::PARAM_INT);
        $stmt_attributes->execute();
        $attributes = $stmt_attributes->fetchAll(PDO::FETCH_ASSOC);

        // Stocker les attributs dans le tableau $historique_info
        $historique_info += $attributes;
    }

    // Convertir l'historique en JSON pour les transmettre à la fonction Python
    $historique_json = json_encode($historique_info);
    file_put_contents('historique.json', $historique_json);



    // Appeler la fonction Python avec l'historique en tant qu'argument
    $python_command = "python recommander_prix_nobel_historique.py historique.json 2>&1";
    $output = shell_exec($python_command);

    // Lire le fichier JSON de sortie
    $output_json = file_get_contents('output_historique.json');

    // Décoder le JSON en tableau associatif
    $output_data = json_decode($output_json, true);

    // Extraire les indices recommandés
    $recommended_indices = $output_data['recommended_indices'];

    // Fermer la connexion à la base de données
    $conn = null;

    return $recommended_indices;

    // Fermer la connexion à la base de données
    $conn = null;

    // Renvoyer les prix Nobel similaires
    return $similar_nobels;
}

?>

