<?php

session_start(); // on démarre la session
session_unset(); // supprime toutes les variables de session
session_destroy(); // détruit la session

// Redirige vers la page de login ou accueil
header("Location: /connexion.php");
exit();

?>