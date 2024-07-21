<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <link rel="shortcut icon" href="musique.jpeg" type="image/x-icon">
    <title>PANEL - ADMIN</title>
</head>
<body>
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
    if($_COOKIE){
        extract($_COOKIE);
        if($id){
            if($privileges == "ADMIN"){
                //on ne fait rien pour rester sur le site
            }else{
                echo "<script>window.location.href = '/index.php';</script>";
            }
        }
    }//une fois la verif faite, connexion a la bdd
    $svname = "localhost";
    $username = "root";
    $password = "Mat.at89";
    
    try {
        $bdd = new PDO("mysql:host=$svname; unix_socket=/tmp/mysql.sock; dbname=utilisateurs;", $username, $password);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "ERREUR : ".$e->getMessage();
    }
    
        try {
            // Utilisation de requête préparée pour éviter les injections SQL
            $req = $bdd->prepare("SELECT * FROM users");
            $req->execute();
            $var = 0;
            echo "<div class='contain'>";
            echo "<div id='boutonssl'>";
            echo "<button id='btn_index'>retour a l'index</button><script>document.getElementById('btn_index').addEventListener('click', function(){window.location.href = '/index.php';});</script>";
            echo "</div>";
            echo "<div class='liste'>";
            while ($rep = $req->fetch()) {
                $var++;
                echo "<p id='pseudoss'>" . $rep['pseudo'] . ': ' . $rep['date'] . "</p><button id='" . $rep['pseudo'] . "' class='btn_btn_adm'>user => ADMIN</button><script>document.cookie = 'id_users" . $var . "=" . $rep['pseudo'] . "'; document.cookie = 'dernier_id=" . $rep['id'] . "'</script>";//rajouter un bouton user => ADMIN pour la var $rep['pseudo']
            }
            echo "</div>";
            //alalallalalalallaalalalalalalalalalazalkazllaalalfgfezdferfvggrfrfg
            echo "<div class='output'>";
            extract($_POST);
if($sql_request){
    if(file_exists("output_bash.json")){
        shell_exec("rm -r output_bash.json");
    }else{
        if(file_exists("output_sql.json")){
            shell_exec("rm -r output_sql.json");
        }
    }
if(strpos($sql_request, "CMD: ") === 0){
    $apres_sous_chaine = substr($sql_request, strlen("CMD: "));
    $output_bash = shell_exec($apres_sous_chaine);
    $output_lines = explode("\n", $output_bash);

    // Initialiser un tableau pour stocker les résultats
    $json_array = [];

    // Parcourir chaque ligne de la sortie
    foreach($output_lines as $index => $line){
        // Ignorer les lignes vides
        if(trim($line) === '') continue;

        // Ajouter les données au tableau au format spécifié
        $json_array[] = [
            "id" => $index + 1,  // Index de ligne + 1 comme ID
            "filename" => $line  // Nom de fichier extrait de la sortie
        ];
    }
    $json_data2 = json_encode($json_array, JSON_PRETTY_PRINT);
    file_put_contents("output_bash.json", $json_data2 . "\n", FILE_APPEND);
    echo "<p id='result'>$output_bash</p>";
    echo '<script>var divParent = document.getElementById("boutonssl"); var bouton = document.createElement("button"); bouton.id = "telechargementos"; bouton.innerHTML = "télécharger la sortie de commande en json"; divParent.appendChild(bouton); document.getElementById("telechargementos").addEventListener("click", function(){var download = document.createElement("a"); download.href = "output_bash.json"; download.download = "output_bash.json"; document.body.appendChild(download); download.click();});</script>';
}else{
if(strpos($sql_request, "SQL: ") === 0){
$apres_sous_chaine2 = substr($sql_request, strlen("SQL: "));
$requete2 = $bdd->prepare($apres_sous_chaine2);
$requete2->execute();
$reponse2 = $requete2->fetchAll(PDO::FETCH_ASSOC);
$json_data = json_encode($reponse2, JSON_PRETTY_PRINT);
file_put_contents("output_sql.json", $json_data . "\n", FILE_APPEND);
echo "<p id='result'>" . print_r($reponse2, true) . "</p>";
echo '<script>var divParent = document.getElementById("boutonssl"); var bouton = document.createElement("button"); bouton.id = "telechargementos"; bouton.innerHTML = "télécharger la sortie de commande en json"; divParent.appendChild(bouton); document.getElementById("telechargementos").addEventListener("click", function(){var download = document.createElement("a"); download.href = "output_sql.json"; download.download = "output_sql.json"; document.body.appendChild(download); download.click();});</script>';
}
}
}echo "</div>";//fin de la div output
            echo "<div class='infosls'>";
            echo "<p id='vues'>depuis le lancement du site " . file_get_contents("compteur_vue.txt") . " visites ont été éfféctuées</p></div>";
            echo "</div>";
            //echo "<script>var dernier_id = " . $rep['id'];
            //UPDATE `users` SET `privileges` = 'user' WHERE `users`.`pseudo` = 'API'; de ADMIN a UTILISATEUR
            //UPDATE `users` SET `privileges` = 'ADMIN' WHERE `users`.`pseudo` = 'API'; de UILISATEUR a ADMIN
            //UPDATE `users` SET `pseudo` = 'API - ADMIN' WHERE `users`.`pseudo` = 'API'; de API a API - ADMIN
            //UPDATE `users` SET `pseudo` = 'API' WHERE `users`.`pseudo` = 'API - ADMIN'; de API - ADMIN a API
    
    
        } catch(PDOException $e) {
            $error_msg = "Erreur lors de l'exécution de la requête : " . $e->getMessage();
            echo $error_msg;
        }
        extract($_COOKIE);
        if($commande){// && $commande2

        try{
            $res = $bdd->prepare($commande);
            $res->execute();
            $reponse = $res->fetch();
            //var_dump($reponse);
            unset($commande);
        }catch(PDOException $i){
            //echo $i;
        }
        try{
            $res = $bdd->prepare($commande2);
            $res->execute();
            $reponse = $res->fetch();
            //var_dump($reponse);
            unset($commande2);
        }catch(PDOException $i){
            //echo $i;
        }
            }
?>
<div class="container_bottom">
<form id="postier" action="" method="POST">
<label id="fleche">>>><input type="text" id="sql_request" name="sql_request" autocomplete="off"></label>
</form>
<?php
#suppression du fichier fonctionne mais le fait automatiquement avant le telechargement sur la machine cliente meme si le code et placé apprès
#shell_exec("rm -r output_sql.json");
#shell_exec("rm -r output_bash.json");
?>
</div>
<!--<a href="http://90.100.231.167:9000">phpmyadmin</a>-->

<div id="getelement"></div>
<script src="main-admin.js"></script>
</body>
</html>