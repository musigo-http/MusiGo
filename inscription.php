<?php
    $svname = "90.48.111.47";
    $username = "root";
    $password = "Mon super mot de passe";
    try{
        $bdd = new PDO("mysql:host=$svname; unix_socket=/run/mysqld/mysqld.sock; dbname=users;", $username, $password);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        echo "ERREUR : ".$e->getMessage();
    }
    /*if(isset($_COOKIE["pseudononhashe"])){
        /*connexion automatique
        extract($_COOKIE);
        $pseudononhashe;//contenue du cookie a durée illimité: pseudo non hashé et mot de passe hashé dans le cookie pseudohashed
        $pseudohashed;
        $sqlrequest12345reQuouestSqlmy = $bdd->prepare("SELECT * FROM users WHERE pseudo = :pseudohash AND password = :pseudohashed");//debut de ma requete sql pour se connecter via le mdp (pseudohashed) et l'email (emailhashed) on hashera pas l'email dans la db mais on la hashera dans la sortie de la requete
        $sqlrequest12345reQuouestSqlmy->bindParam(":pseudohash", $pseudononhashe, PDO::PARAM_STR);
        $sqlrequest12345reQuouestSqlmy->bindParam(":pseudohashed", $pseudohashed, PDO::PARAM_STR);
        $sqlrequest12345reQuouestSqlmy->execute();
        $autrevariable = $sqlrequest12345reQuouestSqlmy->fetch();//autoconnexion
        if($autrevariable["id"] != false){
            echo "<script>document.cookie = 'id=" . $autrevariable['id'] . "'; document.cookie = 'pseudo=" . $autrevariable['pseudo'] . "'; document.cookie = 'privileges=" . $autrevariable['privileges'] . "'; window.location.href = '/index.php'</script>";
        }
    }*/
?>
<!DOCTYPE html>
<html lang="fr" style="overflow: hidden;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="inscription.css">
    <link rel="shortcut icon" href="musique.ico" type="image/x-icon">
    <link rel="icon" href="musique.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Inscription | MusiGo</title>
</head>
<body>
<div class="alldiv1234test30" id="particles-js"></div>
<script src="particles.js-master/particles.min.js"></script>
<div class="container">
<form action="traitement.php" method="post">
    <p id="Inscription">Inscription</p>
    <input type="email" id="email" name="email" placeholder="email" required>
    <input type="password" id="password" name="password" placeholder="password" required><i class="fa-solid fa-eye" id="oeuilFerme"></i><i class="fa-regular fa-eye" id="oeuilOuvert"></i>
    <input type="text" name="pseudo" id="pseudo" placeholder="pseudo" required>
    <input type="submit" value="inscription" name="ok" id="ok">
    <p id="lien_link">Vous avez un compte ? <a href="/connexion.php">Connectez-vous</a></p>
    <!--<p id="resterconnecter">Rester connecté(e)<input type="checkbox" name="resterconnectername" id="resterconnecterid"></p>-->
</form>
</div>
<p id="groupe">GlobalSphere <img id="global" src="logo_GlobalSphere.jpeg"></p>
<div class="groupe"></div>
<script>
        particlesJS.load('particles-js', 'particles.json', function() {
            console.log('particles.json loaded...');
        });
</script>
<script src="inscription.js"></script>
</body>
</html>