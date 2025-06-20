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
    }/*
    if(isset($_COOKIE["pseudononhashe"])){
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
                    file_put_contents("logs_sqli.txt", $_SERVER["REMOTE_ADDR"]." ".$_SERVER["HTTP_USER_AGENT"].">>$phrase<br><br>\n\n", FILE_APPEND);//FILE_APPEND
                }
            }
        }

        // Exemples d'utilisation
        $listeDeMots = [ "OR", "or", "SELECT", "select", "WHERE", "where", "INSERT", "insert", "UPDATE", "update", "DELETE", "delete", "DROP", "drop", "CREATE", "create", "ALTER", "alter", "FROM", "from", "JOIN", "join", "INNER", "inner", "LEFT", "left", "RIGHT", "right", "OUTER", "outer", "FULL", "full", "GROUP", "group", "HAVING", "having", "UNION", "union", "ORDER", "order", "BY", "by", "LIMIT", "limit", "OFFSET", "offset", "VALUES", "values", "SET", "set", "AND", "and", "NOT", "not", "NULL", "null", "IS", "is", "LIKE", "like", "IN", "in", "EXISTS", "exists", "CASE", "case", "WHEN", "when", "THEN", "then", "ELSE", "else", "END", "end" ];
        
        detecterMots("$password", $listeDeMots);
        if($email != "" && $password != ""){
            //connexion a la bdd
            $req = $bdd->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
            $req->bindParam(':email', $email, PDO::PARAM_STR);
            $req->bindParam(':password', hash("sha256", $password), PDO::PARAM_STR);
            $req->execute();
            $rep = $req->fetch();
            if($rep['id'] != false){
                //c'est ok donc faire un systeme de cookie
                //sa fonctionne!: echo "<script>alert('" . $rep['pseudo'] . "');</script>"; de cette façon on peux tout prendre car sa nous renvoie toutes les colones des tables en tent que array
                // connexion.php (juste après la connexion réussie)
                session_start();
                $_SESSION['user_id'] = $rep['id'];
                $_SESSION['pseudo'] = $rep['pseudo'];
                $_SESSION['privileges'] = $rep['privileges'];
                header("Location: /index.php");
                exit;
            }else{
                $error_msg = "email ou mot de passe inccorect!";
            }
        }                                                                                        
    }
    ?>

<!DOCTYPE html>
<html lang="fr" style="overflow: hidden;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="connexion.css">
    <link rel="shortcut icon" href="musique.ico" type="image/x-icon">
    <link rel="icon" href="musique.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Connexion | MusiGo</title>
</head>
<body>

    <div class="alldiv1234test30" id="particles-js"></div>
<div class="container">
<script src="particles.js-master/particles.min.js"></script>
<form action="" method="post">
    <p id="connexion">Connexion</p>
    <input type="email" id="email" name="email" placeholder="email" required>
    <input type="password" id="password" name="password" placeholder="password" required><i class="fa-solid fa-eye" id="oeuilFerme"></i><i class="fa-regular fa-eye" id="oeuilOuvert"></i><!--par defaut le champ de texte est masqué et en type password, quand on peux voir le mdp 'oeuil est fermé et a l'inverse quand on peux pas le voir-->
    <input type="submit" value="connexion" name="ok" id="ok">
    <p id="lien_link">Vous n'avez pas de compte ? <a href="/inscription.php">Inscrivez-vous</a></p>
    <?php
if($error_msg){
    echo "<p id='error_connexion'>".$error_msg."</p>";
}
?>
<!--<p id="rester_connecter">Rester connecté(e)<input type="checkbox" name="connecter" id="connecter"></p>-->
</form>
</div>
<script>
        particlesJS.load('particles-js', 'particles.json', function() {
            console.log('particles.json loaded...');
        });
</script>
<?php
//error connexion ici
?>
<div class="groupe"></div><p id="groupe">GlobalSphere <img id="global" src="logo_GlobalSphere.jpeg"></p>
<script src="connexion.js"></script>
</body>
</html>