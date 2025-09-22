<?php
// Connexion à la base
$host = 'localhost';
$dbname = 'L3_geom';
$dbuser = 'postgres';
$dbpass = 'biba';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["error" => true, "message" => "Connexion échouée : " . $e->getMessage()]));
}

// Requête : jointure spatiale et comptage
$sql = "
    SELECT 
        r.nomreg,
        p.ordre,
        COUNT(*) AS total_points
    FROM 
        pointbase p
    JOIN 
        region_sn r 
    ON 
        ST_Within(p.geog::geometry, r.geom)
    GROUP BY 
        r.nomreg, p.ordre
    ORDER BY 
        r.nomreg, p.ordre
";

try {
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode(["error" => true, "message" => "Erreur SQL : " . $e->getMessage()]);
}
?>
