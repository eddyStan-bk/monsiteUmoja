<?php
session_start();
include('config.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.php");
    exit;
}

// Vérifier le rôle de l'utilisateur
$sql_role = "SELECT role FROM Utilisateurs WHERE id = :id";
$stmt_role = $pdo->prepare($sql_role);
$stmt_role->bindParam(':id', $_SESSION['utilisateur_id'], PDO::PARAM_INT);
$stmt_role->execute();
$role = $stmt_role->fetchColumn();

if ($role !== 'utilisateur') {
    die("Accès non autorisé. Seuls les utilisateurs peuvent voter.");
}

$message = "";

// Traitement du formulaire de vote
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $candidat_id = $_POST['candidat'];
    $jetons = $_POST['jetons'];
    $type_jeton = $_POST['type_jeton'];

    // Calcul du montant à payer en fonction du type de jeton
    $montant = ($type_jeton == 'normal') ? 500 * $jetons : 1000 * $jetons;

    // Récupérer l'ID de l'administrateur (par exemple, le premier administrateur trouvé)
    $sql_admin = "SELECT id FROM Utilisateurs WHERE role = 'admin' LIMIT 1";
    $stmt_admin = $pdo->query($sql_admin);
    $admin_id = $stmt_admin->fetchColumn();

    // Préparer et exécuter l'insertion du vote dans la base de données
    $sql_insert = "INSERT INTO Votes (id_utilisateur, id_candidat, jetons, type_jeton, est_paye, date_vote, admin_id, montant) 
                   VALUES (:id_utilisateur, :id_candidat, :jetons, :type_jeton, 0, CURRENT_TIMESTAMP, :admin_id, :montant)";
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->bindParam(':id_utilisateur', $_SESSION['utilisateur_id'], PDO::PARAM_INT);
    $stmt_insert->bindParam(':id_candidat', $candidat_id, PDO::PARAM_INT);
    $stmt_insert->bindParam(':jetons', $jetons, PDO::PARAM_INT);
    $stmt_insert->bindParam(':type_jeton', $type_jeton, PDO::PARAM_STR);
    $stmt_insert->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt_insert->bindParam(':montant', $montant, PDO::PARAM_INT);

    try {
        $stmt_insert->execute();
        $message = "Votre vote a été enregistré. Veuillez contacter l'administrateur sélectionné pour confirmer le paiement.";
    } catch (PDOException $e) {
        $message = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation du Vote</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <?php require "menu.php"; ?>
        <h2>Confirmation du Vote</h2>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="voter.php" class="btn btn-secondary">Retour au vote</a>
    </div>

    <!-- Scripts Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>