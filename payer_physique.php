<?php
session_start();
include('config.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.php");
    exit;
}
// Récupérer les informations de l'utilisateur
$sql = "SELECT * FROM Utilisateurs WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $_SESSION['utilisateur_id'], PDO::PARAM_INT);
$stmt->execute();
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer le rôle de l'utilisateur
$role = $utilisateur['role'];
// Récupérer la liste des administrateurs avec le rôle 'admin'
$sql = "SELECT id, nom_utilisateur FROM Utilisateurs WHERE role = 'admin'";
$stmt = $pdo->query($sql);
$administrateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisir l'Administrateur pour le Paiement Physique</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <?php require "menu.php"; // Inclusion du menu de navigation 
        ?>
        <h2>Choisir l'Administrateur pour le Paiement Physique</h2>
        <p>Veuillez choisir l'administrateur qui va confirmer votre paiement :</p>
        <form action="confirmer_paiement.php" method="post">
            <div class="form-group">
                <label for="admin">Administrateur</label>
                <select class="form-control" id="admin" name="admin" required>
                    <option value="">Choisir...</option>
                    <?php foreach ($administrateurs as $admin) : ?>
                        <option value="<?php echo $admin['id']; ?>"><?php echo htmlspecialchars($admin['nom_utilisateur']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Confirmer le Paiement</button>
        </form>
    </div>

    <!-- Scripts Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>