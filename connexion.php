<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="connexion.css">
    <link rel="shortcut icon" href="musique.jpeg" type="image/x-icon">
    <title>connexion | MusiGo</title>
</head>
<body id="particles-js">
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
    $svname = "localhost";
    $username = "root";
    $password = "Mat.at89";
    try{
        $bdd = new PDO("mysql:host=$svname; unix_socket=/tmp/mysql.sock; dbname=utilisateurs;", $username, $password);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        echo "ERREUR : ".$e->getMessage();
    }
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        extract($_POST);
        function detecterMots($phrase, $mots) {
            // Convertir la phrase en minuscules pour rendre la recherche insensible à la casse
            $phraseMinuscule = strtolower($phrase);
        
            // Parcourir chaque mot dans la liste des mots à détecter
            foreach ($mots as $mot) {
                // Convertir le mot en minuscule
                $motMinuscule = strtolower($mot);
        
                // Utiliser strpos pour détecter le mot dans la phrase
                if (strpos($phraseMinuscule, $motMinuscule) !== false) {
                    //on ban
                    file_put_contents("blacklist.txt", "".$_SERVER['REMOTE_ADDR']."\n");
                    echo "<script>window.location.href = '/connexion.php'</script>";
                } else {
                    //on laisse vide pour rien faire
                }
            }
        }
        
        // Exemples d'utilisation
        $listeDeMots = ["OR", "or", "SELECT", "select", "WHERE", "where", "insert", "INSERT", "update", "UPDATE"];
        
        detecterMots("$password", $listeDeMots);
        if($email != "" && $password != ""){
            //connexion a la bdd
            $req = $bdd->query("SELECT * FROM users WHERE email = '$email' AND password = '$password'");
            $rep = $req->fetch();
            if($rep['id'] != false){
                //c'est ok donc faire un systeme de cookie
                //sa fonctionne!: echo "<script>alert('" . $rep['pseudo'] . "');</script>"; de cette façon on peux tout prendre car sa nous renvoie toutes les colones des tables en tent que array
                echo "<script>document.cookie = 'id=" . $rep['id'] . "'; document.cookie = 'pseudo=" . $rep['pseudo'] . "'; document.cookie = 'privileges=" . $rep['privileges'] . "'; window.location.href = '/index.php'</script>";
            }else{
                $error_msg = "email ou mdp inccorect!";
            }
        }
    }
    ?>
<div class="container">
<script src="particles.js-master/particles.min.js"></script>
<form action="" method="post">
    <p id="connexion">Connexion</p>
    <input type="email" id="email" name="email" placeholder="email" required>
    <input type="password" id="password" name="password" placeholder="password" required>
    <input type="submit" value="connexion" name="ok" id="ok">
    <p id="lien_link">Vous n'avez pas de compte ? <a href="/inscription.php">Inscrivez-vous</a></p>

</form>
</div>
<script>
        particlesJS.load('particles-js', 'particles.json', function() {
            console.log('particles.json loaded...');
        });
</script>
<?php
if($error_msg){
    echo $error_msg;
}
?>
<div class="groupe"></div><p id="groupe">GlobalSphere <img id="global" src="logo_GlobalSphere.jpeg"></p>
</body>
</html>