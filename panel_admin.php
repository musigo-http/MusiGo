<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <link rel="shortcut icon" href="musique.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="robots" content="noindex">
    <title>PANEL - ADMIN</title>
</head>
<body>
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
    $svname = "90.48.111.47";
    $username = "root";
    $password = "Mon super mot de passe";
    
    try {
        $bdd = new PDO("mysql:host=$svname; unix_socket=/run/mysqld/mysqld.sock; dbname=users;", $username, $password);
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
            echo "<div class='sqli'>";
            echo "<p id='logssqli'>".file_get_contents("logs_sqli.txt")."</p>";
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
echo "<div class='sequejedoitfaire'>";//debut de la div sequejedoitfaire mettre des checks box
echo "<textarea id='sequejedoitfairetextarea'>".file_get_contents("sequejedoitfaire.lst")."</textarea>";//avec js: textarea.value > dans le fichier
echo "<script>document.getElementById('sequejedoitfairetextarea').addEventListener('input', () => {const xhr = new XMLHttpRequest(); xhr.open('POST', '/panel_admin.php', true); xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); xhr.onreadystatechange = function () { if (xhr.readyState === 4 && xhr.status === 200) { const response = JSON.parse(xhr.responseText); if (response.status === 'success') { document.getElementById('response').innerText = response.message; } else { document.getElementById('response').innerText = 'Une erreur est survenue.'; } } }; xhr.send('action=' + encodeURIComponent(document.getElementById('sequejedoitfairetextarea').value));});</script>";
if(isset($_POST["action"])){
    file_put_contents("sequejedoitfaire.lst", $_POST["action"]);
}
echo "</div>";
            echo "<div class='infosls'>";
            echo "<p id='vues'>depuis le lancement du site " . file_get_contents("compteur_vue.txt") . " visites ont été éfféctuées</p></div>";
            echo "</div>";
            //echo "<script>var dernier_id = " . $rep['id'];
            //UPDATE `users` SET `privileges` = 'user' WHERE `users`.`pseudo` = 'API'; de ADMIN a UTILISATEUR
            //UPDATE `users` SET `privileges` = 'ADMIN' WHERE `users`.`pseudo` = 'API'; de UILISATEUR a ADMIN
            //UPDATE `users` SET `pseudo` = 'API - ADMIN' WHERE `users`.`pseudo` = 'API'; de API a API - ADMIN
            //UPDATE `users` SET `pseudo` = 'API' WHERE `users`.`pseudo` = 'API - ADMIN'; de API - ADMIN a API
     
    
        } catch(PDOException $e) {
            $error_msg = $e->getMessage();
            echo $error_msg;
        }
        extract($_COOKIE);
        if(isset($commande)){// && $commande2M

        try{
            $res = $bdd->prepare($commande);
            $res->execute();
            $reponse = $res->fetch();
            //var_dump($reponse);
            unset($commande);
        }catch(PDOException $i){
            echo $i;
        }
        try{
            $res = $bdd->prepare($commande2);
            $res->execute();
            $reponse = $res->fetch();
            //var_dump($reponse);
            unset($commande2);
        }catch(PDOException $i){
            echo $i;
        }
            }
?>
<div class="container_bottom">
<form id="postier" action="" method="POST">
<label id="fleche">>>><input type="text" id="sql_request" name="sql_request" autocomplete="off"></label>
</form>
<div class=controle>
<button id="microouvert"><i class="fa-solid fa-microphone"></i></button>
<button id="microferme"><i class="fa-solid fa-microphone-slash"></i></button>
</div>
<script>
document.getElementById("microouvert").addEventListener("click", function(){
    document.getElementById("microouvert").style.backgroundColor = "chartreuse";
    document.getElementById("microferme").style.backgroundColor = "white";
    //requete ici pour areter la musique
    var xhr = new XMLHttpRequest();
        xhr.open("GET", "http://192.168.1.14:8080/control?lecture=stop", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                console.log(xhr.responseText); // Affichage de la réponse du serveur pour debug
            }
        };//essayer d'url encoder tt sa
        xhr.send("http://192.168.1.14:8080/control?lecture=stop");
});
document.getElementById("microferme").addEventListener("click", function(){
    document.getElementById("microouvert").style.backgroundColor = "white";
    document.getElementById("microferme").style.backgroundColor = "chartreuse";
    var xhr = new XMLHttpRequest();
        xhr.open("GET", "http://192.168.1.14:8080/control?lecture=play", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                console.log(xhr.responseText); // Affichage de la réponse du serveur pour debug
            }
        };//essayer d'url encoder tt sa
        xhr.send("http://192.168.1.14:8080/control?lecture=play");
});
</script>
<?php
#suppression du fichier fonctionne mais le fait automatiquement avant le telechargement sur la machine cliente meme si le code et placé apprès
shell_exec("rm -r output_sql.json");
shell_exec("rm -r output_bash.json");
?>
</div>
<!--<a href="http://90.100.231.167:9000">phpmyadmin</a>-->

<div id="getelement"></div>
<script src="main-admin.js"></script>
</body>
</html>