<?php
// Inclure le fichier de configuration
require_once 'config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Récupérer les données du formulaire
        $nom_etabli = $_POST['nom_etabli'];
        $commune = $_POST['commune'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $statut = $_POST['statut'] ?? null;
        $sous_statut = $_POST['sous_statut'] ?? null;
        $systeme = $_POST['systeme'] ?? null;

        // Construire la géométrie Point
        $geom = "ST_SetSRID(ST_MakePoint(:longitude, :latitude), 4326)";

        // Préparer la requête SQL d'insertion
        $query = "
            INSERT INTO ecole (
                nom_etabli, 
                commune, 
                latitude, 
                longitude, 
                statut, 
                sous_statu, 
                systeme, 
                geom
            ) VALUES (
                :nom_etabli, 
                :commune, 
                :latitude, 
                :longitude, 
                :statut, 
                :sous_statut, 
                :systeme, 
                $geom
            )
        ";

        $stmt = $pdo->prepare($query);

        // Associer les paramètres
        $stmt->bindParam(':nom_etabli', $nom_etabli);
        $stmt->bindParam(':commune', $commune);
        $stmt->bindParam(':latitude', $latitude);
        $stmt->bindParam(':longitude', $longitude);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':sous_statut', $sous_statut);
        $stmt->bindParam(':systeme', $systeme);

        // Exécuter la requête
        $stmt->execute();

        // Rediriger ou afficher un message de succès
        echo "Nouvelle école ajoutée avec succès !";
    } catch (PDOException $e) {
        // En cas d'erreur, afficher un message
        echo "Erreur lors de l'ajout de l'école : " . $e->getMessage();
    }
} else {
    echo "Aucune donnée reçue.";
}
?>
