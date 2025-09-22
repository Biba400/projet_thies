<?php
// Paramètres de connexion à la base de données PostgreSQL
$host = 'localhost';
$dbname = 'L3_geom';
$dbuser = 'postgres';
$dbpass = 'biba';

// Connexion à la base de données PostgreSQL
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $dbuser, $dbpass);
    // Définir le mode d'erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Requête pour récupérer les données depuis la table pointbase avec le champ géographique 'geog'
$query = "SELECT gid, nom, xcoord, ycoord, altitude, ordre, localite, ST_AsGeoJSON(geog) AS geometry FROM pointbase";

// Exécution de la requête
$stmt = $pdo->prepare($query);
$stmt->execute();

// Récupération des résultats sous forme de tableau associatif
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Construction du tableau GeoJSON
$geojson = array(
    "type" => "FeatureCollection",
    "features" => array()
);

// Parcours des résultats pour construire chaque feature
foreach ($results as $row) {
    $feature = array(
        "type" => "Feature",
        "properties" => array(
            "gid" => $row['gid'],
            "nom" => $row['nom'],         // Champ 'nom' en minuscule
            "xcoord" => $row['xcoord'],
            "ycoord" => $row['ycoord'],
            "altitude" => $row['altitude'], // Champ 'altitude' en minuscule
            "ordre" => $row['ordre'],     // Champ 'ordre' en minuscule
            "localite" => $row['localite']
        ),
        "geometry" => json_decode($row['geometry'])  // Décoder le GeoJSON de la colonne 'geog'
    );
    // Ajouter la feature au tableau GeoJSON
    $geojson['features'][] = $feature;
}

// Définir l'en-tête HTTP pour retourner un fichier GeoJSON
header('Content-Type: application/json');

// Retourner les données au format GeoJSON
echo json_encode($geojson, JSON_PRETTY_PRINT);
?>