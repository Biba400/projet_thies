<?php
// Inclure le fichier de configuration
require_once 'config.php';

// Définir le type de contenu pour le GeoJSON
header('Content-Type: application/json');

try {
    // Requête SQL pour récupérer les données nécessaires
    $query = "
        SELECT 
            gid, 
            ST_AsGeoJSON(geom) AS geometry, 
            perimeter, 
            superfice_, 
            admi01_id, 
            nomreg, 
            o_adm01_id 
        FROM region_sn
    ";

    // Préparer et exécuter la requête
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Initialiser le tableau GeoJSON
    $geojson = [
        "type" => "FeatureCollection",
        "features" => []
    ];

    // Parcourir les résultats et construire les features GeoJSON
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $feature = [
            "type" => "Feature",
            "geometry" => json_decode($row['geometry']),
            "properties" => [
                "gid" => $row['gid'],
                "perimeter" => $row['perimeter'],
                "superfice_" => $row['superfice_'],
                "admi01_id" => $row['admi01_id'],
                "nomreg" => $row['nomreg'],
                "o_adm01_id" => $row['o_adm01_id']
            ]
        ];
        $geojson['features'][] = $feature;
    }

    // Retourner les données en GeoJSON
    echo json_encode($geojson, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    // En cas d'erreur, retourner un message JSON
    echo json_encode([
        "error" => true,
        "message" => "Erreur lors de l'exécution de la requête : " . $e->getMessage()
    ]);
}
?>
