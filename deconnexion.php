<?php
session_start();
// Détruire toutes les variables de session
session_destroy();
echo "Vous êtes déconnecté.";
