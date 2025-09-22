<?php
// Inclure le fichier de configuration
include 'config.php';

// Définir le type de contenu comme JSON
header('Content-Type: application/json');

try {
    // Requête pour récupérer les données des quartiers avec leur géométrie
    $query = "SELECT quartier, population, ST_AsGeoJSON(geom) as geometry FROM quartieravecpop";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Préparer le tableau GeoJSON
    $geojson = [
        "type" => "FeatureCollection",
        "features" => []
    ];

    // Ajouter chaque quartier comme une "Feature"
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $feature = [
            "type" => "Feature",
            "properties" => [
                "quartier" => $row['quartier'],
                "population" => (int)$row['population']
            ],
            "geometry" => json_decode($row['geometry'])
        ];
        $geojson['features'][] = $feature;
    }

    // Retourner les données en JSON
    echo json_encode($geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    // En cas d'erreur
    echo json_encode(['error' => 'Erreur lors de la récupération des données : ' . $e->getMessage()]);
}
?>
