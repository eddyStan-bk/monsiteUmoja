<?php
session_start();
session_unset();  // Libère toutes les variables de session
session_destroy();  // Détruit la session

// Regénère l'ID de session pour éviter les attaques de fixation de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

header("Location: login.php");
exit;
