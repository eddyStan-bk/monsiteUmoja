<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.php");
    exit;
}

// Si l'utilisateur est connecté, rediriger vers le tableau de bord
header("Location: dashboard.php");
exit;
