<?php
// Inclure le fichier de configuration
require_once 'config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Récupérer les données du formulaire
        $id = $_POST['id'];
        $nom_etabli = $_POST['nom_etabli'];
        $commune = $_POST['commune'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $statut = $_POST['statut'] ?? null;
        $sous_statu = $_POST['sous_statu'] ?? null;
        $systeme = $_POST['systeme'] ?? null;
        $quartier = $_POST['quartier'] ?? null;
        $nombre_table_bancs = $_POST['nombre_table_bancs'] ?? null;
        $nombre_eleve = $_POST['nombre_eleve'] ?? null;
        $nombre_classe_pedagogiques = $_POST['nombre_classe_pedagogiques'] ?? null;
        $nombre_salles_physiques = $_POST['nombre_salles_physiques'] ?? null;
        $nombre_enseignants = $_POST['nombre_enseignants'] ?? null;
        $nombre_surveillants = $_POST['nombre_surveillants'] ?? null;

        // Construire la géométrie Point
        $geom = "ST_SetSRID(ST_MakePoint(:longitude, :latitude), 4326)";

        // Préparer la requête SQL de mise à jour
        $query = "
            UPDATE ecole1
            SET 
                nom_etabli = :nom_etabli,
                commune = :commune,
                latitude = :latitude,
                longitude = :longitude,
                statut = :statut,
                sous_statu = :sous_statu,
                systeme = :systeme,
                quartier = :quartier,
               
                nombre_eleve = :nombre_eleve,
                nombre_classe_pedagogiques = :nombre_classe_pedagogiques,
                nombre_salles_physiques = :nombre_salles_physiques,
                nombre_enseignants = :nombre_enseignants,
                
                geom = $geom
            WHERE id = :id
        ";

        $stmt = $pdo->prepare($query);

        // Associer les paramètres
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nom_etabli', $nom_etabli);
        $stmt->bindParam(':commune', $commune);
        $stmt->bindParam(':latitude', $latitude);
        $stmt->bindParam(':longitude', $longitude);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':sous_statu', $sous_statu);
        $stmt->bindParam(':systeme', $systeme);
        $stmt->bindParam(':quartier', $quartier);
        
        $stmt->bindParam(':nombre_eleve', $nombre_eleve, PDO::PARAM_INT);
        $stmt->bindParam(':nombre_classe_pedagogiques', $nombre_classe_pedagogiques, PDO::PARAM_INT);
        $stmt->bindParam(':nombre_salles_physiques', $nombre_salles_physiques, PDO::PARAM_INT);
        $stmt->bindParam(':nombre_enseignants', $nombre_enseignants, PDO::PARAM_INT);
        

        // Exécuter la requête
        $stmt->execute();

        // Message de confirmation
        echo "Les informations de l'école ont été mises à jour avec succès.";
    } catch (PDOException $e) {
        // En cas d'erreur
        echo "Erreur lors de la mise à jour : " . $e->getMessage();
    }
} else {
    echo "Aucune donnée reçue.";
}
?>
