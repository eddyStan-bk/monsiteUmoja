<?php
session_start();
include('config.php');

// Vérifier si l'administrateur est connecté
// Vérifier si l'utilisateur est connecté et s'il a le rôle d'administrateur
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.php");
    exit;
}


// Récupérer les votes en attente de confirmation
$sql = "SELECT * FROM Votes WHERE est_paye = 0";
$stmt = $pdo->query($sql);
$votes_attente = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votes en Attente</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <!-- Menu de Navigation Latéral -->
        <?php require "menu.php"; ?>
        <h2>Votes en Attente de Confirmation</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID du Vote</th>
                    <th>ID Utilisateur</th>
                    <th>ID Candidat</th>
                    <th>Jetons</th>
                    <th>Type de Jeton</th>
                    <th>Montant</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($votes_attente as $vote) : ?>
                    <tr>
                        <td><?= htmlspecialchars($vote['id']) ?></td>
                        <td><?= htmlspecialchars($vote['id_utilisateur']) ?></td>
                        <td><?= htmlspecialchars($vote['id_candidat']) ?></td>
                        <td><?= htmlspecialchars($vote['jetons']) ?></td>
                        <td><?= htmlspecialchars($vote['type_jeton']) ?></td>
                        <td><?= htmlspecialchars($vote['montant']) ?></td>
                        <td>
                            <form method="post" action="confirmer_paiement.php">
                                <input type="hidden" name="vote_id" value="<?= $vote['id'] ?>">
                                <button type="submit" class="btn btn-primary">Confirmer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>