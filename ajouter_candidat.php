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

// Initialiser les variables
$nom = $description = $categorie = $message = '';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $categorie = $_POST['categorie'];

    // Validation rapide (vous pouvez ajouter plus de validation ici)
    if (empty($nom) || empty($categorie)) {
        $message = "Veuillez remplir tous les champs obligatoires.";
    } else {
        // Insérer le candidat dans la base de données
        $sql = "INSERT INTO Candidats (nom, description, categorie) VALUES (:nom, :description, :categorie)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':categorie', $categorie, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $message = "Candidat ajouté avec succès !";

            // Réinitialiser les champs du formulaire après succès
            $nom = $description = $categorie = '';
            // Rediriger vers la page gerer_candidats.php après l'ajout réussi
            header("Location: gerer_candidats.php");
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
    <title>Ajouter un Candidat</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Ajouter un Candidat</h2>
        <?php if (!empty($message)) : ?>
            <div class="alert alert-info" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form action="ajouter_candidat.php" method="post">
            <div class="form-group">
                <label for="nom">Nom du Candidat <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            <div class="form-group">
                <label for="categorie">Catégorie <span class="text-danger">*</span></label>
                <select class="custom-select" id="categorie" name="categorie" required>
                    <option value="">Sélectionnez la catégorie</option>
                    <option value="Danse Moderne" <?php if ($categorie == 'Danse Moderne') echo ' selected'; ?>>Danse Moderne</option>
                    <option value="Danse Traditionnelle" <?php if ($categorie == 'Danse Traditionnelle') echo ' selected'; ?>>Danse Traditionnelle</option>
                    <option value="Miss" <?php if ($categorie == 'Miss') echo ' selected'; ?>>Miss</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter Candidat</button>
            <a href="dashboard.php" class="btn btn-secondary">Retour au Dashboard</a>
        </form>
    </div>
</body>

</html>