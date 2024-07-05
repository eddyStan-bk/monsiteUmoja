<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

    // Vérifier si le nom d'utilisateur existe déjà
    $sql_check = "SELECT * FROM Utilisateurs WHERE nom_utilisateur = :nom_utilisateur";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':nom_utilisateur', $nom_utilisateur, PDO::PARAM_STR);
    $stmt_check->execute();
    $utilisateur_existant = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur_existant) {
        $message = "Nom d'utilisateur déjà pris. Veuillez en choisir un autre.";
    } else {
        $sql = "INSERT INTO Utilisateurs (nom_utilisateur, email, mot_de_passe) VALUES (:nom_utilisateur, :email, :mot_de_passe)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':nom_utilisateur', $nom_utilisateur, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':mot_de_passe', $mot_de_passe, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
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
    <title>Inscription - Umoja</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 400px;
            margin: 100px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }

        .form-group label {
            font-weight: bold;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input[type="password"],
        .password-wrapper input[type="text"] {
            padding-right: 40px;
        }

        .password-wrapper .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            width: 100%;
            margin-top: 20px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Créer un Compte Umoja</h2>
        <?php if (isset($message)) : ?>
            <div class="alert alert-info" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="nom_utilisateur">Nom d'utilisateur</label>
                <input type="text" name="nom_utilisateur" class="form-control" id="nom_utilisateur" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" id="email" required>
            </div>
            <div class="form-group">
                <label for="mot_de_passe">Mot de Passe</label>
                <div class="password-wrapper">
                    <input type="password" name="mot_de_passe" class="form-control" id="mot_de_passe" required>
                    <span class="toggle-password" onclick="togglePassword()">👁️</span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
        <p class="mt-3">Vous avez déjà un compte ? <a href="login.php">Connectez-vous ici</a>.</p>
    </div>
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById('mot_de_passe');
            var togglePasswordButton = document.querySelector('.toggle-password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                togglePasswordButton.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                togglePasswordButton.textContent = '👁️';
            }
        }
    </script>
</body>

</html>