<?php
function recommander_prix_nobel_similaires($id_prix_nobel_actuel, $k = 3) {
    // Connexion à la base de données
    $conn = getBD(); // Assurez-vous d'implémenter correctement cette fonction selon votre configuration de base de données

    // Récupérer les attributs de l'article actuel
    $sql_attributes = "SELECT Année, id_category, Motivation FROM prix_nobel WHERE id_prix_nobels = :id_prix_nobel";
    $stmt_attributes = $conn->prepare($sql_attributes);
    $stmt_attributes->bindParam(':id_prix_nobel', $id_prix_nobel_actuel, PDO::PARAM_INT);
    $stmt_attributes->execute();
    $attributes = $stmt_attributes->fetch(PDO::FETCH_ASSOC);

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

