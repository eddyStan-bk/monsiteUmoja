<?php
session_start();
include('config.php');

// Traitement de la confirmation de paiement
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vote_id = $_POST['vote_id']; // ID du vote à confirmer

    // Mettre à jour le statut du vote dans la base de données
    $sql_update = "UPDATE Votes SET est_paye = 1 WHERE id = :vote_id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->bindParam(':vote_id', $vote_id, PDO::PARAM_INT);

    try {
        $stmt_update->execute();

        // Rediriger vers le dashboard après confirmation
        header("Location: dashboard.php");
        exit; // Assurez-vous de sortir du script après la redirection
    } catch (PDOException $e) {
        $error_message = "Erreur lors de la confirmation du paiement : " . $e->getMessage();
    }
}
