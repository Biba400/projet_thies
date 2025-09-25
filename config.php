<?php
// Configuration de la connexion à la base de données

$host = 'localhost';  // L'hôte de votre base de données
$dbname = 'educationthies';  // Le nom de votre base de données
$username = 'postgres';  // Le nom d'utilisateur de la base de données (utilisateur par défaut de PostgreSQL)
$password = 'biba';  // Le mot de passe de la base de données


define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '5432');
define('DB_NAME', getenv('DB_NAME') ?: 'educationthies');
define('DB_USER', getenv('DB_USER') ?: 'postgres');
define('DB_PASS', getenv('DB_PASS') ?: 'biba'); // remplace par le vrai mot de passe si différent

// Connexion à la base de données via PDO
try {
    $pdo = new PDO("pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    // Définir le mode d'erreur de PDO sur Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optionnel : définir l'encodage des caractères
    $pdo->exec("SET NAMES 'UTF8'");
   
} catch (PDOException $e) {
    // Si la connexion échoue, afficher un message d'erreur
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
}
?>
