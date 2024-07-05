<?php

// Paramètres de connexion à la base de données
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'umoja_vote');

try {
    // Tentative de connexion à MySQL via PDO
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    // Définir le mode d'erreur PDO à exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Définir le jeu de caractères à UTF8
    $pdo->exec("set names utf8mb4");
} catch (PDOException $e) {
    die("Erreur : Impossible de se connecter à la base de données " . DB_NAME . ": " . $e->getMessage());
}
