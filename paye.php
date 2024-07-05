<?php
session_start();
include('config.php');

// Vérifier si l'utilisateur est connecté et s'il est administrateur
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.php");
    exit;
}

// Vérifier le rôle de l'utilisateur
$sql = "SELECT role FROM Utilisateurs WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $_SESSION['utilisateur_id'], PDO::PARAM_INT);
$stmt->execute();
$role = $stmt->fetchColumn();

if ($role !== 'admin') {
    // Rediriger vers une page d'erreur ou afficher un message d'erreur
    die("Accès non autorisé.");
}

// Récupérer l'ID du paiement à confirmer depuis l'URL
if (isset($_GET['id'])) {
    $paiement_id = $_GET['id'];

    // Mettre à jour l'état du paiement dans la base de données
    $sql = "UPDATE Notifications SET etat = 'confirme' WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $paiement_id, PDO::PARAM_INT);

    try {
        $stmt->execute();
        $message = "Paiement confirmé avec succès !";
    } catch (PDOException $e) {
        $message = "Erreur : " . $e->getMessage();
    }
} else {
    $message = "ID de paiement non spécifié.";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation du Paiement</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <?php require "menu_admin.php"; // Inclusion du menu de navigation pour l'administrateur 
        ?>
        <h2>Confirmation du Paiement</h2>
        <?php if (isset($message)) : ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <a href="paiements.php" class="btn btn-primary">Retour à la Gestion des Paiements</a>
    </div>

    <!-- Scripts Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>