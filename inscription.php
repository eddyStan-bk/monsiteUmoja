<?php
session_start();
include('config.php');

// Récupérer les données du formulaire d'inscription
$nom_utilisateur = $_POST['nom_utilisateur'];
$email = $_POST['email'];
$mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT); // Hasher le mot de passe

// Insérer l'utilisateur dans la base de données
$sql = "INSERT INTO Utilisateurs (nom_utilisateur, email, mot_de_passe) VALUES (:nom_utilisateur, :email, :mot_de_passe)";
$stmt = $pdo->prepare($sql);

$stmt->bindParam(':nom_utilisateur', $nom_utilisateur, PDO::PARAM_STR);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->bindParam(':mot_de_passe', $mot_de_passe, PDO::PARAM_STR);

try {
    $stmt->execute();
    echo "Inscription réussie !";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
