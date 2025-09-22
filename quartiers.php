<?php
// Inclure la configuration de la base de données
include('config.php');

try {
    // Préparer la requête SQL pour récupérer toutes les données de la table quartiersvillethies
    $query = "SELECT * FROM quartiersvillethies";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Vérifier s'il y a des résultats
    if ($stmt->rowCount() > 0) {
        // Récupérer toutes les lignes de la table
        $quartiers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Afficher les résultats
        echo "<h3>Liste des Quartiers</h3>";
        echo "<table border='1'>";
        echo "<tr>
                <th>ID</th>
                <th>Quartier</th>
                <th>Commune</th>
                <th>Arrondissement</th>
                <th>Type</th>
              </tr>";
        
        foreach ($quartiers as $quartier) {
            echo "<tr>
                    <td>" . htmlspecialchars($quartier['id']) . "</td>
                    <td>" . htmlspecialchars($quartier['quartier']) . "</td>
                    <td>" . htmlspecialchars($quartier['commune']) . "</td>
                    <td>" . htmlspecialchars($quartier['arr']) . "</td>
                    <td>" . htmlspecialchars($quartier['type_qvh']) . "</td>
                  </tr>";
        }
        
        echo "</table>";
    } else {
        echo "Aucun quartier trouvé.";
    }
    
} catch (PDOException $e) {
    // Gérer les erreurs lors de l'exécution de la requête
    echo "Erreur lors de la récupération des données: " . $e->getMessage();
}
?>
