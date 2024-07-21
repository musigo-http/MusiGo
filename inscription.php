<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="inscription.css">
    <link rel="shortcut icon" href="musique.jpeg" type="image/x-icon">
    <title>inscription | MusiGo</title>
</head>
<body id="particles-js">
<script src="particles.js-master/particles.min.js"></script>
<div class="container">
<form action="traitement.php" method="post">
    <p id="Inscription">Inscription</p>
    <input type="email" id="email" name="email" placeholder="email" required>
    <input type="password" id="password" name="password" placeholder="password" required>
    <input type="text" name="pseudo" id="pseudo" placeholder="pseudo" required>
    <input type="submit" value="inscription" name="ok" id="ok">
    <p id="lien_link">Vous avez un compte ? <a href="/connexion.php">Connectez-vous</a></p>
</form>
</div>
<p id="groupe">GlobalSphere <img id="global" src="logo_GlobalSphere.jpeg"></p>
<div class="groupe"></div>
<script>
        particlesJS.load('particles-js', 'particles.json', function() {
            console.log('particles.json loaded...');
        });
</script>
</body>
</html>
<?php
    function estDansBlacklist($fichier, $ip) {
        // Lire le contenu du fichier blacklist.txt
        $contenu = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
        // Vérifier si l'IP est dans la liste
        if (in_array($ip, $contenu)) {
            return true;
        } else {
            return false;
        }
    }
    
    // Nom du fichier blacklist
    $fichier = "blacklist.txt";
    
    // Adresse IP à vérifier
    $ipAVerifier = $_SERVER['REMOTE_ADDR'];
    
    // Vérifier si l'IP est dans la blacklist
    if (estDansBlacklist($fichier, $ipAVerifier)) {
        echo "<script>window.location.href = '/ban.php'</script>";
    }
?>