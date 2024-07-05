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

// Récupérer les notifications de paiement en attente
$sql = "SELECT * FROM Notifications WHERE type = 'paiement_physique' AND etat = 'attente'";
$stmt = $pdo->query($sql);
$paiements_attente = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Paiements</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <?php require "menu.php"; // Inclusion du menu de navigation pour l'administrateur 
        ?>
        <h2>Gestion des Paiements</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($paiements_attente as $paiement) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($paiement['utilisateur']); ?></td>
                        <td><?php echo htmlspecialchars($paiement['montant']); ?></td>
                        <td><?php echo htmlspecialchars($paiement['date']); ?></td>
                        <td>
                            <a href="confirmer_paiement.php?id=<?php echo $paiement['id']; ?>" class="btn btn-success">Confirmer Paiement</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Scripts Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>