<?php
session_start();
include('config.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.php");
    exit;
}
$sql = "SELECT role FROM Utilisateurs WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $_SESSION['utilisateur_id'], PDO::PARAM_INT);
$stmt->execute();
$role = $stmt->fetchColumn();

// Récupérer les informations de l'utilisateur connecté
$utilisateur_id = $_SESSION['utilisateur_id'];
$sql_user = "SELECT * FROM utilisateurs WHERE id = :utilisateur_id";
$stmt_user = $pdo->prepare($sql_user);
$stmt_user->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
$stmt_user->execute();
$utilisateur = $stmt_user->fetch(PDO::FETCH_ASSOC);

// Sélectionner les votes confirmés pour affichage
$sql_votes = "SELECT v.id, v.id_utilisateur, v.id_candidat, v.est_paye, v.date_vote, v.jetons, v.type_jeton, v.montant, v.admin_id, u.nom_utilisateur AS candidat_nom
              FROM votes v
              LEFT JOIN utilisateurs u ON v.id_candidat = u.id
              WHERE v.est_paye = 1";
$stmt_votes = $pdo->query($sql_votes);
$votes_confirmes = $stmt_votes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        /* Styles pour le menu latéral */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            /* Largeur du menu */
            background-color: #343a40;
            color: #fff;
            padding-top: 60px;
            /* Espace pour le logo ou le titre */
            transition: all 0.3s ease;
        }

        .sidebar ul.navbar-nav li.nav-item {
            padding: 8px 15px;
            /* Espacement des liens */
        }

        .sidebar ul.navbar-nav li.nav-item:hover {
            background-color: #495057;
            /* Couleur au survol */
        }

        .main-content {
            margin-left: 250px;
            /* Marge pour le contenu principal */
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .sidebar.active {
            width: 80px;
            /* Largeur réduite pour le mode actif */
        }

        .sidebar.active .navbar-nav {
            text-align: center;
            /* Centrer le texte dans le menu réduit */
        }
    </style>
</head>

<body>
    <!-- Menu de Navigation Latéral -->
    <?php require "menu.php"; ?>

    <!-- Contenu principal -->
    <div class="main-content" id="main-content">
        <h2>Bienvenue sur votre Dashboard, <?= htmlspecialchars($utilisateur['nom_utilisateur']) ?></h2>
        <hr>

        <!-- Tableau des votes confirmés -->
        <h3>Votes Confirmés</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID du Vote</th>
                        <th>ID de l'Utilisateur</th>
                        <th>Candidat</th>
                        <th>Date du Vote</th>
                        <th>Nombre de Jetons</th>
                        <th>Type de Jeton</th>
                        <th>Montant</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($votes_confirmes as $vote) : ?>
                        <tr>
                            <td><?= htmlspecialchars($vote['id']) ?></td>
                            <td><?= htmlspecialchars($vote['id_utilisateur']) ?></td>
                            <td><?= htmlspecialchars($vote['candidat_nom']) ?></td>
                            <td><?= htmlspecialchars($vote['date_vote']) ?></td>
                            <td><?= htmlspecialchars($vote['jetons']) ?></td>
                            <td><?= htmlspecialchars($vote['type_jeton']) ?></td>
                            <td><?= htmlspecialchars($vote['montant']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chargement de Bootstrap JS et jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Script JavaScript pour gérer le menu latéral -->
    <script>
        $(document).ready(function() {
            // Toggle pour ouvrir/fermer le menu latéral
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#main-content').toggleClass('active');
            });
        });
    </script>
</body>

</html>