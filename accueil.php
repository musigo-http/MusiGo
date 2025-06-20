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
    if(isset($_COOKIE["pseudononhashe"])){
        /*connexion automatique*/
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
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="accueil1.css">
    <link rel="shortcut icon" href="musique.ico" type="image/x-icon">
    <link rel="icon" href="musique.ico" type="image/x-icon">
    <title>Accueil | MusiGo</title>
</head>
<body>
    <div class="header">
        <h1 id="titre">MusiGo</h1>
        <div class="button-container">
            <button id="connexion">Se Connecter</button>
            <button id="inscription">S'Inscrire</button>
        </div>
    </div>
    <div class="entree">
            <div class="texte">
                <h1 id="slogantexteecoutezmusqieu">Écoutez votre musique préférée en toute tranquillité 🙌</h1>
            </div>
        </div>
    <div class="slogans">
        <div class="avantages">
            <div class="noads">
                <h2>Pas de pubs</h2>
                <img src="ad.png" alt="Pas de pubs" id="noadsimg">
            </div>
            <div class="intuitif">
                <h2>Interface intuitive</h2>
                <img src="int.png" alt="Interface intuitive" id="intuitif-image">
            </div>
            <div class="gratuit">
                <h2>100% Gratuit</h2>
                <img src="free.png" alt="Gratuit" id="gratuit-image">
            </div>
        </div>
    </div>
    <script src="accueil.js"></script>
</body>
</html>