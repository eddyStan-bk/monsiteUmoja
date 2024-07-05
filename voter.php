<?php
session_start();
include('config.php');

// Vérifier si l'utilisateur est connecté
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

if ($role !== 'utilisateur') {
    die("Accès non autorisé. Seuls les utilisateurs peuvent voter.");
}

// Récupérer la liste des candidats
$sql = "SELECT * FROM Candidats";
$stmt = $pdo->query($sql);
$candidats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des administrateurs
$sql = "SELECT * FROM Utilisateurs WHERE role = 'admin'";
$stmt = $pdo->query($sql);
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <?php require "menu.php"; ?>
        <h2>Voter</h2>
        <form action="confirmer_vote.php" method="post">
            <div class="form-group">
                <label for="candidat">Choisir le Candidat</label>
                <select class="form-control" id="candidat" name="candidat" required>
                    <?php foreach ($candidats as $candidat) : ?>
                        <option value="<?php echo $candidat['id']; ?>"><?php echo htmlspecialchars($candidat['nom']); ?> (<?php echo htmlspecialchars($candidat['categorie']); ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="jetons">Nombre de Jetons</label>
                <input type="number" class="form-control" id="jetons" name="jetons" min="1" required>
            </div>
            <div class="form-group">
                <label for="type_jeton">Type de Jeton</label>
                <select class="form-control" id="type_jeton" name="type_jeton" required>
                    <option value="normal">Normal (500 FC)</option>
                    <option value="vert">Vert (1000 FC)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="admin">Choisir l'Administrateur pour Confirmer le Vote</label>
                <select class="form-control" id="admin" name="admin" required>
                    <?php foreach ($admins as $admin) : ?>
                        <option value="<?php echo $admin['id']; ?>"><?php echo htmlspecialchars($admin['nom_utilisateur']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Voter</button>
        </form>
    </div>

    <!-- Scripts Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>