<?php
// Configuration de la connexion à la base de données

$host = 'localhost';  // L'hôte de votre base de données
$dbname = 'educationthies';  // Le nom de votre base de données
$username = 'postgres';  // Le nom d'utilisateur de la base de données (utilisateur par défaut de PostgreSQL)
$password = 'biba';  // Le mot de passe de la base de données

// Connexion à la base de données via PDO
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    // Définir le mode d'erreur de PDO sur Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optionnel : définir l'encodage des caractères
    $pdo->exec("SET NAMES 'UTF8'");
   
} catch (PDOException $e) {
    // Si la connexion échoue, afficher un message d'erreur
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
}
?>
