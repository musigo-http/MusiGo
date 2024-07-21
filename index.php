<?php
$svname = "localhost";
$username = "root";
$password = "Mat.at89";

try {
    $bdd = new PDO("mysql:host=$svname;unix_socket=/tmp/mysql.sock;dbname=utilisateurs;", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "ERREUR : " . $e->getMessage();
    exit();
}

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
if($_COOKIE){
    extract($_COOKIE);
    if(isset($id)){
        //on laisse vide pour dire que il n'y a aucune redirection a faire.
        $file = intval(file_get_contents("compteur_vue.txt"));
        $file++;
        file_put_contents("compteur_vue.txt", $file);
    }else{
        echo "<script>window.location.href = '/connexion.php'</script>";
    }
}else{
    echo "<script>window.location.href = '/connexion.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/dist/boxicons.js" integrity="sha512-Dm5UxqUSgNd93XG7eseoOrScyM1BVs65GrwmavP0D0DujOA8mjiBfyj71wmI2VQZKnnZQsSWWsxDKNiQIqk8sQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="shortcut icon" href="musique.jpeg" type="image/x-icon" id="favicon">
    <title>MusiGo</title>
</head>
<body>
    <script>
        function playMusic(titre){
            fetch("/"+titre+"/audio.mp3").then(res=>res.arrayBuffer()).then((buffer)=>{
                audio.src = ("data:audio/mp3;base64,"+_arrayBufferToBase64(buffer));
            })

            function _arrayBufferToBase64( buffer ) {
                var binary = '';
                var bytes = new Uint8Array( buffer );
                var len = bytes.byteLength;
                for (var i = 0; i < len; i++) {
                    binary += String.fromCharCode( bytes[ i ] );
                }
                return window.btoa( binary );
            }
        }
        
        function playMusic2(titre, blase){
            fetch("/"+titre+"/audio.mp3").then(res=>res.arrayBuffer()).then((buffer)=>{
            document.getElementById(blase).src = ("data:audio/mp3;base64,"+_arrayBufferToBase64(buffer));
        })

            function _arrayBufferToBase64( buffer ) {
                var binary = '';
                var bytes = new Uint8Array( buffer );
                var len = bytes.byteLength;
                for (var i = 0; i < len; i++) {
                    binary += String.fromCharCode( bytes[ i ] );
                }
                return window.btoa( binary );
            }
        }
    </script>
    <div class="menu">
        <!--menu 30%-->
        <h2 id="titre">Menu</h2>
        <ul id="liste">
            <li id="home"><i class="fa-solid fa-house"></i>acceuil</li>
            <li><span class="material-symbols-outlined">queue_music</span>playlist</li>
            <li><i class="fa-solid fa-music"></i>les plus écoutées</li>
            <li id="likes"><i class="fa-solid fa-heart"></i>j'aime</li>
            <?php
            if($privileges === "ADMIN"){
                echo "<li id='admin'><i class='fa-solid fa-wrench'></i>PANEL - ADMIN</li>";
            }
            ?>
        </ul>
    </div>
    <div class="header">
        <!--header 100% taille 10 a 20%-->
        <form action="" method="get" id="form_1">
            <input type="search" name="search" id="search" placeholder="Rechercher">
            <button type="submit" id="next"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
        <button id="deco">Déconnexion</button>
        <div class="pseudo">
        <?php
        echo "<h3 id='pseudo'>" . $pseudo . "</h3>";
        ?>
        </div>
    </div>
    <div class="content" id="content">
    <?php
if(isset($_COOKIE["page"])){
    $page = $_COOKIE["page"];
    switch ($page){
        case 'index':
            //on fait rien on est sur l'index c'est normal (home)
            break;
        case 'likes':
            //on fait tout se qui a a faire pour likes: genre on recup les likes via se que on a fait plus bas et si ils sont deux c bon on les affiches si ils sont trois on affiche pas le troisieme mais une fleche sur la droite pour faire une annimation pour pouvoir voir les autres au cas ou ils sont plus que 2
            echo "<script>document.getElementById('content').style = 'justify-content: flex-start;';</script>";
            $pseudo = isset($_COOKIE["pseudo"]) ? $_COOKIE["pseudo"] : '';

            if ($pseudo) {
                $req2 = $bdd->prepare("SELECT likes_musique FROM users WHERE pseudo = :pseudo");
                $req2->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
                $req2->execute();
                $rep2 = $req2->fetch();

                // Affiche le contenu pour déboguer
                echo "<script>console.log('likes_musique: " . $rep2['likes_musique'] . "');</script>";

                if ($rep2 && $rep2['likes_musique']) {
                    $mots2 = explode(", ", $rep2['likes_musique']);
                    $mot_total2 = count($mots2);
                    for ($i2 = 0; $i2 < $mot_total2; $i2++) {
                        if (!empty($mots2[$i2])) {//mtn chrch son image depuis le dossier
                            echo "<div class='recherche2' id='recherche2".$i2."'><img src='" .$mots2[$i2]. "/default.jpg' id='image_musique2'><br><p style='color: rgb(204, 197, 197);' id='texte_musique2' value=''>" . $mots2[$i2] . "</p></div>";
                            echo '<audio id="myAudio2'.$i2.'"></audio><script>playMusic2("'.$mots2[$i2].'", "myAudio2'.$i2.'")</script>';
                            echo '<script>document.addEventListener("DOMContentLoaded", function() { var playMusic = document.getElementById("play"); var pauseMusic = document.getElementById("pause"); if (playMusic && pauseMusic && document.getElementById("recherche2'.$i2.'") && document.getElementById("recherche2'.$i2.'")) { document.getElementById("recherche2'.$i2.'").addEventListener("click", function() { document.getElementById("myAudio2'.$i2.'").play(); playMusic.style.display = "none"; pauseMusic.style.display = "block"; pauseMusic.addEventListener("click", function() { document.getElementById("myAudio2'.$i2.'").pause(); pauseMusic.style.display = "none"; playMusic.style.display = "block"; }); playMusic.addEventListener("click", function() { document.getElementById("myAudio2'.$i2.'").play(); playMusic.style.display = "none"; pauseMusic.style.display = "block"; }); }); } else { console.error("One or more elements are missing in the DOM."); } });</script>';
                        } 
                    }
                } 
            }
            break;
    }
}
    ?>
    <?php
$champ_recherche = $_GET["search"];
if($champ_recherche){
//lancer le js please wait
/*
if($_COOKIE["page"] == "likes"){
    echo "<script>document.cookie = 'page=home';</script>";
    echo "<script>document.cookie = 'search=".$champ_recherche."';</script>";
    echo "<script>window.location.href = '/index.php';";
}*/
unset($_COOKIE);
$mot_cle = "musique " . $champ_recherche;
$apiKey = 'AIzaSyCgo3b56i2z0MpWugFAr6w7valcjrAFjhk';//mateopapa.at@gmail.com:AIzaSyCgo3b56i2z0MpWugFAr6w7valcjrAFjhk ,mateoapidu89@gmail.com: AIzaSyCcV7pybPnJovIAR_GVVR3UngFBtP5wJhM.
$url = 'https://www.googleapis.com/youtube/v3/search?part=snippet&q=' . urlencode($mot_cle) . '&maxResults=' . '3' . '&key=' . $apiKey;
$requete_http = file_get_contents($url);
if($requete_http !== false){
    $donnee_json = json_decode($requete_http, true);
    $videoId = $donnee_json['items'][0]['id']['videoId'];
    file_put_contents("file.json", $videoId);
}else{
    echo "error";
}
$request = 'https://www.googleapis.com/youtube/v3/videos?id=' . $videoId . '&key=' . $apiKey . '&part=snippet';
$suite = file_get_contents($request);
if($suite !== false){
    $donnee = json_decode($suite);
    $videoTitle = $donnee->items[0]->snippet->title;
    file_put_contents("name2.json", $videoTitle);
}
if(file_exists($videoTitle)){
        $req1 = $bdd->query("SELECT likes_musique FROM users WHERE pseudo = '$pseudo'");
        $rep1 = $req1->fetch();
        
        $mots1 = explode(", ", $rep1['likes_musique']);
        $mot_total1 = count($mots1);
        for ($i1 = 0; $i1 < $mot_total1; $i1++) {
            if ($mots1[$i1] === $videoTitle) {
                //dire au code qui suit que la personne a liké la musique
                echo "<script>document.cookie = 'like=yes';</script>";
                break;
            }else{
                echo "<script>document.cookie = 'like=no';</script>";
            }
        }
    echo "<div id='recherche'><img src='" . $videoTitle . "/default.jpg' id='image_musique'><br><p style='color: rgb(204, 197, 197);' id='texte_musique' value=''>" . $videoTitle . "</p></div>";
    echo '<audio id="myAudio"></audio><script>playMusic("'.$videoTitle.'")</script>';
    //setcookie("musique", $videoTitle);
    shell_exec("rm -r name2.json");
    shell_exec("rm -r file.json");
}else{
//partie pour rechercher la video demandé
$apiUrl = 'https://www.googleapis.com/youtube/v3/videos?part=snippet&id=' . $videoId . '&key=' . $apiKey;

$response = file_get_contents($apiUrl);

if ($response !== false) {
    $data = json_decode($response, true);
    // Récupérer la miniature de la vidéo
    $thumbnailUrl = $data['items'][0]['snippet']['thumbnails']['default']['url'];
    file_put_contents("data.json", $response);
    shell_exec("curl -O " . $thumbnailUrl);
} else {
    echo 'Erreur lors de la requête à l\'API YouTube';
}
//téléchargement des sous titres:
//$url_lien_url_lien = "https://www.googleapis.com/youtube/v3/captions/{$videoId}?key={$apiKey}";
//$responsesive_response_je_blague_ptdr_ces_response_et_pas_reponsive = file_get_contents($url_lien_url_lien);
//file_put_contents("paroles.txt", $responsesive_response_je_blague_ptdr_ces_response_et_pas_reponsive);
$url_url = "https://www.youtube.com/api/timedtext?v=" . $videoId . "&asr_langs=ar&caps=asr&xorp=true&hl=fr&ip=0.0.0.0&ipbits=0&expire=1648958812&sparams=ip,ipbits,expire,v,asr_langs,caps,xorp&signature=27C8A2C8B5C4D4497C38432FAAD4DE4405B418C4.D74794E1125C6F9D896E3C52228F0E3DC1B2A5C4&key=yttt1&kind=asr&lang=ar&fmt=srv3";

// Récupérer les données JSON à partir de l'API YouTube
//$response_response = file_get_contents($url_url);
shell_exec("curl -O " . $url_url);
// Convertir les données JSON en tableau associatif
//$data_data = json_decode($response_response, true);

file_put_contents("data.data.json", $response_response);
shell_exec("python3 main.py");
mkdir($videoTitle);
shell_exec('mv audio.mp3 "' . $videoTitle . '"');
shell_exec("rm -r audio.mp3");
shell_exec('mv default.jpg "' . $videoTitle . '"');
shell_exec("rm -r default.jpg");
shell_exec("rm -r name2.json");//IAM - Demain c'est loin (Clip officiel)
//shell_exec('mv paroles.txt "' . $videoTitle . '"');//sous titres déplacé mais peut etre pas supprimé
file_put_contents("musiques.txt", $videoTitle . "\n", FILE_APPEND);
echo "<div id='recherche'><img src='" . $videoTitle . "/default.jpg' id='image_musique'><br><p style='color: rgb(204, 197, 197);' id='texte_musique' value=''>" . $videoTitle . "</p></div>";
echo '<audio id="myAudio"></audio><script>playMusic("'.$videoTitle.'")</script>';
//setcookie("musique", $videoTitle);
}
}//faire en sorte que le script fasse une requete a youtube afin de recuperer le titre de la video demandé, si le dossier qui se nomme par le titre de la video n'existe pas le creer avec le code si dessu sinon eviter de surcharger le serveur et d'epuiser mon quota de point pour l'api de youtube
?><!--reçevoir la video dans un cookie pour l'afficher-->
        <!--content 70%-->
        <!---->
        <div class="musique">
        </div><!--quand une recherche et effectué sa l'envoie avec l'api de youtube et sa me telecharge sur le serveur les premiers 10 ou 5 resultats, filtre: musique {titre demandé} pui sa le telecharge sur le serv afin d'en extraire l'image (miniature)-->
    </div>
    <div class="player">
        <!--player 100% taille 10 a 20%-->
        <!--<img src="logo de la musique en cours de lecture" alt="">-->
        <?php
        if($videoId){
            echo "<img src='" . $videoTitle . "/default.jpg' id='img_musique'><p id='titre_musique'>" . $videoTitle . "</p>";
        }
        ?>
        <?php
    if($_COOKIE){
        extract($_COOKIE);
    if(isset($videotitle)){//si la page n'est pas interragie sa ne fonctionne pas, donc faire en sorte que des que le bouton play est qliqué si il ya des cookies sa nous lance a ou sa en était
        echo "<img src='" . $videotitle . "/default.jpg' id='img_musique' style='display: block!important;'><p id='titre_musique' style='display: block!important;'>" . $videotitle . "</p>";//si l'image et cliquer invoquer du js pour envoyer le cookie
        echo '<audio id="myAudio" currentTime="' . $musique . '"></audio><script>playMusic("'.$videotitle.'")</script>';
    }
    }
    ?>
        <div class="button_music">
            <i class="fa-solid fa-backward" id="arriere"></i>
            <i class="fa-solid fa-play" id="play"></i>
            <i class="fa-solid fa-pause" id="pause"></i><!--le masque si l'audio n'est pas joué ou sur pause et afficher selui d'en haut avec du javascript-->
            <i class="fa-solid fa-forward"></i>
            <i class="fa-regular fa-heart" id="heart"></i><!--sinon mettre quand c'est liké le fa-solid-->
            <script>
                    var coeur = document.getElementById("heart");
                    coeur.style = "cursor: pointer;";
        coeur.addEventListener("click", function() {
        var titre_de_la_video = getCookie("videotitle");
        var pseudo = getCookie("pseudo");
        var action = (coeur.className == "fa-regular fa-heart") ? "like" : "unlike";
        
        // Mise à jour de la classe CSS du cœur
        coeur.className = (action === "like") ? "fa-solid fa-heart" : "fa-regular fa-heart";
        
        // Envoi de la requête AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "index.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                console.log(xhr.responseText); // Affichage de la réponse du serveur pour debug
            }
        };
        xhr.send("action=" + action + "&titre=" + titre_de_la_video + "&pseudo=" + pseudo);
    });
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $titre = $_POST['titre'];
    $pseudo = $_POST['pseudo'];

    if ($action == 'like') {
        $sql = "UPDATE users SET likes_musique = CONCAT(IFNULL(likes_musique, ''), '$titre', ', ') WHERE pseudo = :pseudo";
    } else if ($action == 'unlike') {
        $req = $bdd->prepare("SELECT likes_musique FROM users WHERE pseudo = :pseudo");
        $req->execute(['pseudo' => $pseudo]);
        $rep = $req->fetch();
        
        $mots = explode(", ", $rep['likes_musique']);
        $mot_total = count($mots);
        
        for ($i = 0; $i < $mot_total; $i++) {
            if ($mots[$i] === $titre) {
                unset($mots[$i]);
                break;
            }
        }
        
        $mots = array_values($mots);
        
        $nouveaux_titres = implode(", ", $mots);
        
        $update = $bdd->prepare("UPDATE users SET likes_musique = :likes_musique WHERE pseudo = :pseudo");
        $update->execute(['likes_musique' => $nouveaux_titres, 'pseudo' => $pseudo]);
    } else {
        echo "Action inconnue.";
        exit();
    }

    $stmt = $bdd->prepare($sql);
    //$stmt->bindParam(':titre', $titre);
    $stmt->bindParam(':pseudo', $pseudo);

    if ($stmt->execute()) {
        echo "Mise à jour réussie.";
    } else {
        echo "Erreur lors de la mise à jour.";
    }
}
?>

            <input type="range" id="seekBar" value="0" max="100" min="0">
        </div>
    </div>
    <script src="main.js"></script>
</body>
</html>