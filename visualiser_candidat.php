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
    die("Accès non autorisé.");
}

// Récupérer les détails du candidat
$candidat_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($candidat_id > 0) {
    $sql = "SELECT * FROM Candidats WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $candidat_id, PDO::PARAM_INT);
    $stmt->execute();
    $candidat = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$candidat) {
        die("Candidat non trouvé.");
    }
} else {
    die("ID de candidat invalide.");
}

// Mettre à jour les informations du candidat si le formulaire est soumis
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $categorie = $_POST['categorie'];

    // Validation rapide
    if (empty($nom) || empty($categorie)) {
        $message = "Veuillez remplir tous les champs obligatoires.";
    } else {
        // Mettre à jour le candidat dans la base de données
        $sql = "UPDATE Candidats SET nom = :nom, description = :description, categorie = :categorie WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':categorie', $categorie, PDO::PARAM_STR);
        $stmt->bindParam(':id', $candidat_id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $message = "Candidat mis à jour avec succès !";
            // Actualiser les informations du candidat après mise à jour
            $candidat['nom'] = $nom;
            $candidat['description'] = $description;
            $candidat['categorie'] = $categorie;
        } catch (PDOException $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualiser et Modifier le Candidat</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <?php require "menu.php"; // Inclusion du menu de navigation 
        ?>
        <h2>Visualiser et Modifier le Candidat</h2>
        <?php if (!empty($message)) : ?>
            <div class="alert alert-info" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form action="visualiser_candidat.php?id=<?php echo $candidat_id; ?>" method="post">
            <div class="form-group">
                <label for="nom">Nom du Candidat <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($candidat['nom']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($candidat['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="categorie">Catégorie <span class="text-danger">*</span></label>
                <select class="custom-select" id="categorie" name="categorie" required>
                    <option value="">Sélectionnez la catégorie</option>
                    <option value="Danse Moderne" <?php echo $candidat['categorie'] == 'Danse Moderne' ? 'selected' : ''; ?>>Danse Moderne</option>
                    <option value="Danse Traditionnelle" <?php echo $candidat['categorie'] == 'Danse Traditionnelle' ? 'selected' : ''; ?>>Danse Traditionnelle</option>
                    <option value="Miss" <?php echo $candidat['categorie'] == 'Miss' ? 'selected' : ''; ?>>Miss</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="gerer_candidats.php" class="btn btn-secondary">Retour à la gestion des candidats</a>
        </form>
    </div>

    <!-- Scripts Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>