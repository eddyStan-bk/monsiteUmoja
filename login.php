<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $sql = "SELECT * FROM utilisateurs WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur) {
        if (password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            session_regenerate_id(true);
            $_SESSION['utilisateur_id'] = $utilisateur['id'];
            $_SESSION['nom_utilisateur'] = $utilisateur['nom_utilisateur'];
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Mot de passe incorrect !";
        }
    } else {
        $message = "Utilisateur non trouvé !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Umoja</title>
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
        <h2>Connexion à Umoja</h2>
        <?php if (isset($message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <form action="login.php" method="post">
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
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
        <p class="mt-3">Vous n'avez pas encore de compte ? <a href="register.php">Créez-en un ici</a>.</p>
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