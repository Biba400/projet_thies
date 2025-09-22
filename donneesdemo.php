<?php
// Inclure le fichier de configuration pour la connexion à la base de données
require_once 'config.php';

// Préparer une requête SQL pour récupérer les données de la table donneesdemo
$query = "SELECT id, ST_AsGeoJSON(geom) AS geom, cav, type_qvh, milieu, commune, quartier, menages, hommes, femmes, total FROM donneesdemo";

try {
    // Exécuter la requête
    $statement = $pdo->prepare($query);
    $statement->execute();

    // Initialiser un tableau pour stocker les fonctionnalités GeoJSON
    $features = [];

    // Parcourir les résultats de la requête
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $feature = [
            "type" => "Feature",
            "geometry" => json_decode($row['geom']),
            "properties" => [
                "id" => $row['id'],
                "cav" => $row['cav'],
                "type_qvh" => $row['type_qvh'],
                "milieu" => $row['milieu'],
                "commune" => $row['commune'],
                "quartier" => $row['quartier'],
                "menages" => $row['menages'],
                "hommes" => $row['hommes'],
                "femmes" => $row['femmes'],
                "total" => $row['total']
            ]
        ];
        $features[] = $feature;
    }

    // Construire l'objet GeoJSON final
    $geojson = [
        "type" => "FeatureCollection",
        "features" => $features
    ];

    // Définir l'en-tête HTTP pour indiquer que la réponse est au format JSON
    header('Content-Type: application/json');

    // Retourner les données GeoJSON
    echo json_encode($geojson);

} catch (PDOException $e) {
    // En cas d'erreur, retourner un message JSON d'erreur
    header('Content-Type: application/json');
    echo json_encode(["error" => "Erreur lors de la récupération des données: " . $e->getMessage()]);
}
?>
