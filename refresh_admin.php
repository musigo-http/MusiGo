<meta name="robots" content="noindex">
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
<?php
echo "<script>window.location.href = '/panel_admin.php';</script>";
?>