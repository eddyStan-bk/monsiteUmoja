<?php
session_start();
include('config.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.php");
    exit;
}

// Vérifier si l'utilisateur est administrateur
$sql = "SELECT role FROM Utilisateurs WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $_SESSION['utilisateur_id'], PDO::PARAM_INT);
$stmt->execute();
$role = $stmt->fetchColumn();

if ($role !== 'admin') {
    // Rediriger vers une page d'erreur ou afficher un message d'erreur
    die("Accès non autorisé.");
}

// Supprimer un candidat si l'ID est passé dans l'URL
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $sql = "DELETE FROM Candidats WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    try {
        $stmt->execute();
        $message = "Candidat supprimé avec succès !";
    } catch (PDOException $e) {
        $message = "Erreur : " . $e->getMessage();
    }
}

// Récupérer tous les candidats de la base de données
$sql = "SELECT * FROM Candidats";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$candidats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Séparer les candidats par catégorie
$candidats_moderne = array_filter($candidats, function ($candidat) {
    return $candidat['categorie'] == 'Danse Moderne';
});

$candidats_traditionnelle = array_filter($candidats, function ($candidat) {
    return $candidat['categorie'] == 'Danse Traditionnelle';
});

$candidats_miss = array_filter($candidats, function ($candidat) {
    return $candidat['categorie'] == 'Miss';
});
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Candidats</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container-flex {
            display: flex;
        }

        .menu {
            flex: 0 0 200px;
            margin-right: 20px;
        }

        .tables {
            flex: 1;
            display: flex;
            flex-wrap: wrap;
        }

        .table-container {
            flex: 1;
            min-width: 300px;
            margin: 10px;
        }

        .table-container h3 {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container container-flex">
        <div class="menu">
            <?php require "menu.php"; // Inclusion du menu de navigation 
            ?>
        </div>
        <div class="tables">
            <?php if (isset($message)) {
                echo "<p>$message</p>";
            } ?>

            <div class="table-container">
                <h3>Danse Moderne</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($candidats_moderne as $candidat) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($candidat['nom']); ?></td>
                                <td>
                                    <a href="visualiser_candidat.php?id=<?php echo $candidat['id']; ?>" class="btn btn-info">Visualiser</a>
                                    <a href="gerer_candidats.php?delete=<?php echo $candidat['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce candidat ?');">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-container">
                <h3>Danse Traditionnelle</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($candidats_traditionnelle as $candidat) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($candidat['nom']); ?></td>
                                <td>
                                    <a href="visualiser_candidat.php?id=<?php echo $candidat['id']; ?>" class="btn btn-info">Visualiser</a>
                                    <a href="gerer_candidats.php?delete=<?php echo $candidat['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce candidat ?');">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-container">
                <h3>Miss</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($candidats_miss as $candidat) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($candidat['nom']); ?></td>
                                <td>
                                    <a href="visualiser_candidat.php?id=<?php echo $candidat['id']; ?>" class="btn btn-info">Visualiser</a>
                                    <a href="gerer_candidats.php?delete=<?php echo $candidat['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce candidat ?');">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>