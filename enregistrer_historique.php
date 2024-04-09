<?php
session_start();
require_once 'connexion_bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pageURL']) && isset($_SESSION['user_id'])) {
    // Récupérer les données de la requête AJAX
    $pageURL = $_POST['pageURL'];
    $id_utilisateur = $_SESSION['user_id'];
    $id_prix_nobel=$_POST['id_prix_nobel'];

    try {
        // Connexion à la base de données
        $connexion = getBD();
        
        // Vérifier le nombre d'enregistrements actuels pour cet utilisateur
        $sql_count = "SELECT COUNT(*) AS total FROM historiquepages WHERE id_utilisateur = :id_utilisateur";
        $stmt_count = $connexion->prepare($sql_count);
        $stmt_count->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $stmt_count->execute();
        $row = $stmt_count->fetch(PDO::FETCH_ASSOC);
        $total_records = $row['total'];
        
        // S'il y a déjà 10 enregistrements, supprimer le plus ancien
        if ($total_records >= 10) {
            $sql_delete_oldest = "DELETE FROM historiquepages WHERE id_utilisateur = :id_utilisateur ORDER BY date_visite LIMIT 1";
            $stmt_delete_oldest = $connexion->prepare($sql_delete_oldest);
            $stmt_delete_oldest->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
            $stmt_delete_oldest->execute();
        }
        
        // Insérer le nouvel enregistrement dans la base de données
        $sql = "INSERT INTO historiquepages (id_utilisateur, page_visitee, id_prix_nobel) VALUES (:id_utilisateur, :page_visitee, :id_prix_nobel)";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $stmt->bindParam(':page_visitee', $pageURL, PDO::PARAM_STR);
        $stmt->bindParam(':id_prix_nobel', $id_prix_nobel, PDO::PARAM_STR);
        $stmt->execute();
        
        echo "Historique enregistré avec succès.";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
