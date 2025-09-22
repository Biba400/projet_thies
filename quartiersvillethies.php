<?php
// Inclure la configuration de la base de données
include('config.php');

// Définir l'en-tête de réponse pour indiquer que c'est du JSON
header('Content-Type: application/json');

try {
    // Préparer la requête SQL pour récupérer les données avec la géométrie
    $query = "SELECT id, quartier, commune, arr, type_qvh, ST_AsGeoJSON(geom) AS geometry FROM quartiersvillethies";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Vérifier si des résultats ont été trouvés
    if ($stmt->rowCount() > 0) {
        // Initialiser le tableau GeoJSON
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => []
        ];

        // Parcourir chaque ligne de résultats
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Ajouter chaque quartier comme une "feature" dans le tableau GeoJSON
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($row['geometry']), // Décoder la géométrie GeoJSON
                'properties' => [
                    'id' => $row['id'],
                    'quartier' => $row['quartier'],
                    'commune' => $row['commune'],
                    'arr' => $row['arr'],
                    'type_qvh' => $row['type_qvh']
                ]
            ];
            // Ajouter la feature au tableau GeoJSON
            $geojson['features'][] = $feature;
        }

        // Retourner les données en JSON (GeoJSON)
        echo json_encode($geojson);
    } else {
        // Si aucune donnée n'est trouvée, retourner un GeoJSON vide
        echo json_encode(['type' => 'FeatureCollection', 'features' => []]);
    }
    
} catch (PDOException $e) {
    // Gérer les erreurs de connexion ou d'exécution de requête
    echo json_encode(['error' => 'Erreur lors de la récupération des données: ' . $e->getMessage()]);
}
?>
