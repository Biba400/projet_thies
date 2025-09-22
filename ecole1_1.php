<?php
// Inclure le fichier de configuration
require_once 'config.php';

// Définir le type de contenu pour le GeoJSON
header('Content-Type: application/json');

try {
    // Requête SQL pour récupérer les données nécessaires
    $query = "
        SELECT 
            id, 
            ST_AsGeoJSON(geom) AS geometry, 
            latitude, 
            longitude, 
            statut, 
            sous_statu AS sous_statut, 
            nom_etabli, 
            commune, 
            quartier,
            nombre_table_bancs,
            nombre_eleve,
            nombre_classe_pédagogiques,
            nombre_salles_physiques,
            nombre_enseignants,
            nombre_surveillants,
            systeme AS systeme 
        FROM ecole1
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
                "id" => $row['id'],
                "latitude" => $row['latitude'],
                "longitude" => $row['longitude'],
                "statut" => $row['statut'],
                "sous_statut" => $row['sous_statut'],
                "nom_etabli" => $row['nom_etabli'],
                "commune" => $row['commune'],
                "quartier" => $row['quartier'],
                "nombre_table_bancs" => $row['nombre_table_bancs'],
                "nombre_eleve" => $row['nombre_eleve'],
                "nombre_classe_pédagogiques" => $row['nombre_classe_pédagogiques'],
                "nombre_salles_physiques" => $row['nombre_salles_physiques'],
                "nombre_enseignants" => $row['nombre_enseignants'],
                "nombre_surveillants" => $row['nombre_surveillants'],
                "systeme" => $row['systeme']
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
