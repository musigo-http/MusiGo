<?php
session_start();
$svname = "90.48.111.47";
$username = "root";
$password = "Mon super mot de passe";

try {
    $bdd = new PDO("mysql:host=$svname; unix_socket=/run/mysqld/mysqld.sock;dbname=users;", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "ERREUR : " . $e->getMessage();
    exit();
}

// index.php

if (!isset($_SESSION['user_id'])) {
    header("Location: /accueil.php");
    exit;
}

$file = intval(file_get_contents("compteur_vue.txt"));
$file++;
file_put_contents("compteur_vue.txt", $file);//index & connexion
$pseudo = $_SESSION['pseudo'];
$id = $_SESSION['user_id'];
$privileges = $_SESSION['privileges'];
?>

<?php
function aleatoire($cheminpath){
    // Le dossier à scanner
    $dir = $cheminpath;

    // Ouvre le dossier
    $files = scandir($dir);

    // Filtre les fichiers "." et ".."
    $files = array_diff($files, array('.', '..'));

    // Si le dossier contient des fichiers
    if (count($files) > 0) {
        // Choisit un fichier au hasard
        $randomFile = $files[array_rand($files)];
        
        // Affiche le fichier choisi
        return $randomFile;
    } else {
        return "None";
    }
}
?>
    <?php
    $maxtry = 0;
    if(isset($_POST["musiquealeatoire"]) && $_POST["musiquealeatoire"] == "suivant"){
        $ancien_titre = $_COOKIE["pathreel"];
        $genres = $_COOKIE["genre"];
        $premierchoix = aleatoire("MUSIQUES/".base64_encode($genres));
        $deuxiemechoix = aleatoire("MUSIQUES/".base64_encode($genres)."/".$premierchoix);
        $vraipath = "MUSIQUES/".base64_encode($genres)."/".$premierchoix."/".$deuxiemechoix;
        while($ancien_titre == $vraipath && $maxtry < 10){
            $genres = $_COOKIE["genre"];
            $premierchoix = aleatoire("MUSIQUES/".base64_encode($genres));
            $deuxiemechoix = aleatoire("MUSIQUES/".base64_encode($genres)."/".$premierchoix);
            $vraipath = "MUSIQUES/".base64_encode($genres)."/".$premierchoix."/".$deuxiemechoix;
            $maxtry++;
        }
        //setcookie("testokokok", "r");
        //echo '<audio id="myAudio"></audio><script>document.addEventListener("DOMContentLoaded", function() {playMusic("'.$vraipath.'"); audio.play(); alert("test")});</script>';
        //echo $vraipath;
        header('Content-Type: application/json');
        echo json_encode([
            "path" => $vraipath
        ]);
        //echo "<script>var path = '".$vraipath."'</script>";
        //echo "<script>alert('test')</script>";
        //error_log("vrai path: ".$vraipath);
        exit;
    }else{
        error_log("rien");
    }
    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_index.css">
    <link rel="manifest" href="manifest.json">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/dist/boxicons.js" integrity="sha512-Dm5UxqUSgNd93XG7eseoOrScyM1BVs65GrwmavP0D0DujOA8mjiBfyj71wmI2VQZKnnZQsSWWsxDKNiQIqk8sQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="shortcut icon" href="musique.ico" type="image/x-icon" id="favicon">
    <link rel="icon" href="musique.ico" type="image/x-icon">
    <title>MusiGo</title>
</head>
<body>
    <div class="containerParamPlaylist" id="containerParamPlaylist">
        <i class="fa-regular fa-circle-xmark" id="croix"></i>
        <div class="plusdeplaylist" id="plusdeplaylist">
            <i class="fa-solid fa-plus"></i>
        </div>
    </div>
    <?php
    function spotifysearchgenre($spoutput){
    $token = "";
    $spoutput = urlencode($spoutput);;

    //transformer tout l'api de spotify en fonction car il y a des autres bout de code qui nessesitent la reponse de l'api mais il s'ont plus haut que l'api
    $spotifyurl = "https://api.spotify.com/v1/search?q=".$spoutput."&type=track&limit=1";
        $headerspotify = [
            "Authorization: Bearer $token"
        ];
        $initcurl = curl_init($spotifyurl);
        curl_setopt($initcurl, CURLOPT_HTTPHEADER, $headerspotify); // Ajoute le header
        curl_setopt($initcurl, CURLOPT_RETURNTRANSFER, true); // Retourne la réponse sous forme de chaîne
        $responsespotify = curl_exec($initcurl);
        if(curl_errno($initcurl)) {
            error_log("cURL error: " . curl_error($initcurl));
        }
        $httpcode = curl_getinfo($initcurl, CURLINFO_HTTP_CODE);
        curl_close($initcurl);
        error_log("httpcode: ".$httpcode);
        if($httpcode !== 200){
            $headers = [
                'Authorization: Basic ' . base64_encode("254eb4636e7545c5bc307ab879b865a0:d0f1a55dd2f646b390fa2038ebe784cc"),
                'Content-Type: application/x-www-form-urlencoded',
            ];
        
            $post_fields = http_build_query([
                'grant_type' => 'client_credentials',
            ]);
        
            $ch123 = curl_init('https://accounts.spotify.com/api/token');
            curl_setopt($ch123, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch123, CURLOPT_POSTFIELDS, $post_fields);
            curl_setopt($ch123, CURLOPT_RETURNTRANSFER, true);
        
            $response = curl_exec($ch123);
            curl_close($ch123);
        
            $data = json_decode($response, true);
            $token = $data['access_token']; // Met à jour la variable par référence
            error_log("token: ".$token);
            $spotifyurl = "https://api.spotify.com/v1/search?q=".$spoutput."&type=track&limit=1";
            $headerspotify = [
                "Authorization: Bearer $token"
            ];
            $initcurl = curl_init($spotifyurl);
            curl_setopt($initcurl, CURLOPT_HTTPHEADER, $headerspotify); // Ajoute le header
            curl_setopt($initcurl, CURLOPT_RETURNTRANSFER, true); // Retourne la réponse sous forme de chaîne
            
            $responsespotify = curl_exec($initcurl);
            
            curl_close($initcurl);
        }
        $data = json_decode($responsespotify, true);
        $artist_id = $data["tracks"]["items"][0]["album"]["artists"][0]["id"];//["artists"][0]["id"];
        $artistname = $data["tracks"]["items"][0]["album"]["artists"][0]["name"];
    
        error_log("artist id: ".$artist_id);
        
    
    
        $spotifyurl2 = "https://api.spotify.com/v1/artists/".$artist_id;
    
        $initcurl2 = curl_init($spotifyurl2);
        curl_setopt($initcurl2, CURLOPT_HTTPHEADER, $headerspotify); // Ajoute le header
        curl_setopt($initcurl2, CURLOPT_RETURNTRANSFER, true); // Retourne la réponse sous forme de chaîne
        
        $responsespotify2 = curl_exec($initcurl2);
        $data2 = json_decode($responsespotify2, true);
        curl_close($initcurl2);
        $kk = $data2["genres"][0];//var les plus importantes: $artist_id, $genrer, $artistname
        if($kk != NULL){
            return $genrer = $kk;
        }
        else{
            return $genrer = "inclassable";
        }
    }




    function spotifysearchartistid($spoutput1){
    $token = "";
    $spoutput1 = urlencode($spoutput1);;

    //transformer tout l'api de spotify en fonction car il y a des autres bout de code qui nessesitent la reponse de l'api mais il s'ont plus haut que l'api
    $spotifyurl = "https://api.spotify.com/v1/search?q=".$spoutput1."&type=track&limit=1";
        $headerspotify = [
            "Authorization: Bearer $token"
        ];
        $initcurl = curl_init($spotifyurl);
        curl_setopt($initcurl, CURLOPT_HTTPHEADER, $headerspotify); // Ajoute le header
        curl_setopt($initcurl, CURLOPT_RETURNTRANSFER, true); // Retourne la réponse sous forme de chaîne
        
        $responsespotify = curl_exec($initcurl);
        if(curl_errno($initcurl)) {
            error_log("cURL error: " . curl_error($initcurl));
        }
        $httpcode = curl_getinfo($initcurl, CURLINFO_HTTP_CODE);
        curl_close($initcurl);
        error_log("httpcode: ".$httpcode);
        if($httpcode !== 200){
            $headers = [
                'Authorization: Basic ' . base64_encode("254eb4636e7545c5bc307ab879b865a0:d0f1a55dd2f646b390fa2038ebe784cc"),
                'Content-Type: application/x-www-form-urlencoded',
            ];
        
            $post_fields = http_build_query([
                'grant_type' => 'client_credentials',
            ]);
        
            $ch123 = curl_init('https://accounts.spotify.com/api/token');
            curl_setopt($ch123, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch123, CURLOPT_POSTFIELDS, $post_fields);
            curl_setopt($ch123, CURLOPT_RETURNTRANSFER, true);
        
            $response = curl_exec($ch123);
            curl_close($ch123);
        
            $data = json_decode($response, true);
            $token = $data['access_token']; // Met à jour la variable par référence
            error_log("token: ".$token);
            $spotifyurl = "https://api.spotify.com/v1/search?q=".$spoutput1."&type=track&limit=1";
            $headerspotify = [
                "Authorization: Bearer $token"
            ];
            $initcurl = curl_init($spotifyurl);
            curl_setopt($initcurl, CURLOPT_HTTPHEADER, $headerspotify); // Ajoute le header
            curl_setopt($initcurl, CURLOPT_RETURNTRANSFER, true); // Retourne la réponse sous forme de chaîne
            
            $responsespotify = curl_exec($initcurl);
            
            curl_close($initcurl);
        }
        $data = json_decode($responsespotify, true);
        $artist_id = $data["tracks"]["items"][0]["album"]["artists"][0]["id"];//["artists"][0]["id"];
        $artistname = $data["tracks"]["items"][0]["album"]["artists"][0]["name"];
    
        error_log("artist id: ".$artist_id);
        
    
    
        $spotifyurl2 = "https://api.spotify.com/v1/artists/".$artist_id;
    
        $initcurl2 = curl_init($spotifyurl2);
        curl_setopt($initcurl2, CURLOPT_HTTPHEADER, $headerspotify); // Ajoute le header
        curl_setopt($initcurl2, CURLOPT_RETURNTRANSFER, true); // Retourne la réponse sous forme de chaîne
        
        $responsespotify2 = curl_exec($initcurl2);
        $data2 = json_decode($responsespotify2, true);
        curl_close($initcurl2);
        $kk = $data2["genres"][0];//var les plus importantes: $artist_id, $genrer, $artistname
        if($kk != NULL){
            $genrer = $kk;
        }
        else{
            $genrer = "inclassable";
        }
        return $artist_id;
    }



    function spotifysearchartistname($spoutput2){
    $token = "";
    $spoutput2 = urlencode($spoutput2);;

    //transformer tout l'api de spotify en fonction car il y a des autres bout de code qui nessesitent la reponse de l'api mais il s'ont plus haut que l'api
    $spotifyurl = "https://api.spotify.com/v1/search?q=".$spoutput2."&type=track&limit=1";
        $headerspotify = [
            "Authorization: Bearer $token"
        ];
        $initcurl = curl_init($spotifyurl);
        curl_setopt($initcurl, CURLOPT_HTTPHEADER, $headerspotify); // Ajoute le header
        curl_setopt($initcurl, CURLOPT_RETURNTRANSFER, true); // Retourne la réponse sous forme de chaîne
        
        $responsespotify = curl_exec($initcurl);
        if(curl_errno($initcurl)) {
            error_log("cURL error: " . curl_error($initcurl));
        }
        $httpcode = curl_getinfo($initcurl, CURLINFO_HTTP_CODE);
        curl_close($initcurl);
        error_log("httpcode: ".$httpcode);
        if($httpcode !== 200){
            $headers = [
                'Authorization: Basic ' . base64_encode("254eb4636e7545c5bc307ab879b865a0:d0f1a55dd2f646b390fa2038ebe784cc"),
                'Content-Type: application/x-www-form-urlencoded',
            ];
        
            $post_fields = http_build_query([
                'grant_type' => 'client_credentials',
            ]);
        
            $ch123 = curl_init('https://accounts.spotify.com/api/token');
            curl_setopt($ch123, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch123, CURLOPT_POSTFIELDS, $post_fields);
            curl_setopt($ch123, CURLOPT_RETURNTRANSFER, true);
        
            $response = curl_exec($ch123);
            curl_close($ch123);
        
            $data = json_decode($response, true);
            $token = $data['access_token']; // Met à jour la variable par référence
            error_log("token: ".$token);
            $spotifyurl = "https://api.spotify.com/v1/search?q=".$spoutput2."&type=track&limit=1";
            $headerspotify = [
                "Authorization: Bearer $token"
            ];
            $initcurl = curl_init($spotifyurl);
            curl_setopt($initcurl, CURLOPT_HTTPHEADER, $headerspotify); // Ajoute le header
            curl_setopt($initcurl, CURLOPT_RETURNTRANSFER, true); // Retourne la réponse sous forme de chaîne
            
            $responsespotify = curl_exec($initcurl);
            
            curl_close($initcurl);
        }
        $data = json_decode($responsespotify, true);
        $artist_id = $data["tracks"]["items"][0]["album"]["artists"][0]["id"];//["artists"][0]["id"];
        $artistname = $data["tracks"]["items"][0]["album"]["artists"][0]["name"];
    
        error_log("artist id: ".$artist_id);
        
    
    
        $spotifyurl2 = "https://api.spotify.com/v1/artists/".$artist_id;
    
        $initcurl2 = curl_init($spotifyurl2);
        curl_setopt($initcurl2, CURLOPT_HTTPHEADER, $headerspotify); // Ajoute le header
        curl_setopt($initcurl2, CURLOPT_RETURNTRANSFER, true); // Retourne la réponse sous forme de chaîne
        
        $responsespotify2 = curl_exec($initcurl2);
        $data2 = json_decode($responsespotify2, true);
        curl_close($initcurl2);
        $kk = $data2["genres"][0];//var les plus importantes: $artist_id, $genrer, $artistname
        if($kk != NULL){
            $genrer = $kk;
        }
        else{
            $genrer = "inclassable";
        }
        return $artistname;
    }

    ?>
    <?php
    /*$request12345_REQouest = $bdd->prepare("UPDATE users SET connexions = :ippubliquedelapersonne where pseudo = :pseudo");
    $request12345_REQouest->bindParam(':pseudo', $_COOKIE["pseudo"], PDO::PARAM_STR);
    $request12345_REQouest->bindParam(':ippubliquedelapersonne', $_SERVER["REMOTE_ADDR"], PDO::PARAM_STR);
    $request12345_REQouest->execute();
    $reprequest12345_REQouest = $request12345_REQouest->fetch();

    $requests13432 = $bdd->prepare("SELECT email FROM users WHERE pseudo = '".$_COOKIE["pseudo"]."'");
    $requests13432->execute();
    $requestsuesqurequest = $requests13432->fetch();
    $user_id = hash("sha256", $requestsuesqurequest["email"]);
    $deviceId = session_id();
    echo "<script>console.log('Votre user_id est : " . $user_id . "');</script>";
    $sessionsFile = 'sessions.json';
    if (file_exists($sessionsFile)) {
        $sessions = json_decode(file_get_contents($sessionsFile), true);
    } else {
        $sessions = [];
    }
    
    // Vérifier le nombre de sessions actives pour cet utilisateur
    $activeSessions = 0;
    foreach ($sessions as $session) {
        if ($session['user_id'] === $userId) {
            $activeSessions++;
        }
    }
    
    // Vérifier si l'utilisateur est déjà connecté avec deux appareils
    if ($activeSessions >= 2) {
        echo "<script>console.log('Deux appareils sont déjà connectés au même compte.')</script>";
    } else {
        // Ajouter ou mettre à jour la session de l'utilisateur
        $sessions[] = [
            'user_id' => $userId,
            'device_id' => $deviceId,
            'created_at' => time(),
        ];
        file_put_contents($sessionsFile, json_encode($sessions));
        echo "Connexion réussie sur un nouvel appareil.";
    }*/
    ?>
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


    <script>
</script>
    <div class="menu" id="menu">
        <!--menu 30%-->
        <h2 id="titre">Menu</h2>
        <ul id="liste">
            <li id="home"><i class="fa-solid fa-house"></i><p id="accueildumenutitreaccueilhome">acceuil</p></li>
            <li id="playlist"><span class="material-symbols-outlined">queue_music</span><p id="playlisttitredumenuplaylist">playlist</p></li>
            <li id="plusecoutees"><i class="fa-solid fa-music"></i>les plus écoutées</li><script>document.getElementById("plusecoutees").addEventListener("click", function() { window.location.href = "/travaux.php"; });</script>
            <li id="likes"><i class="fa-solid fa-heart"></i><p id="jaimetitredumenujaime">j'aime</p></li>
            <?php
            if($privileges === "ADMIN"){
                echo "<li id='admin'><i class='fa-solid fa-wrench'></i>PANEL - ADMIN</li>";
            }/*
            if($privileges === "user"){
                echo "<li id='soutenir'><i class='fa-solid fa-heart'></i>soutenir le projet</li><script>document.getElementById('soutenir').addEventListener('click', function(){window.location.href = 'https://www.buymeacoffee.com/MusiGo'; });</script>";
            }*/
            ?>
        </ul>
    </div>
    <div class="header" id="header">
        <!--header 100% taille 10 a 20%-->
        <form action="" method="get" id="form_1">
            <input type="search" name="search" id="search" placeholder="Rechercher">
            <button type="submit" id="next"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
        <button id="deco">Déconnexion</button>
        <!--<form method="post" action="deconnexion.php">
    <button type="submit" name="logout">Se déconnecter</button>
</form>-->

        <div class="pseudo">
        <?php
        echo "<h3 id='pseudo'>" . $_SESSION['pseudo'] . "</h3>";
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
            $pseudo = $_SESSION['pseudo'];

            if ($pseudo) {
                $req2 = $bdd->prepare("SELECT likes_musique FROM users WHERE pseudo = :pseudo");
                $req2->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
                $req2->execute();
                $rep2 = $req2->fetch();

                // afficher le contenu pour déboguer
                echo "<script>console.log('likes_musique: " . $rep2['likes_musique'] . "');</script>";

                if ($rep2 && $rep2['likes_musique']) {
                    $mots2 = explode(", ", $rep2['likes_musique']);
                    $mot_total2 = count($mots2);
                    for ($i2 = 0; $i2 < $mot_total2; $i2++) {
                        if (!empty($mots2[$i2])) {//mtn chrch son image depuis le dossier
                            $path = base64_encode(spotifysearchgenre(base64_decode($mots2[$i2])))."/".base64_encode(spotifysearchartistname(base64_decode($mots2[$i2])))."/".$mots2[$i2];
                            echo "<div class='recherche2' id='recherche2".$i2."'><img src='MUSIQUES/" .$path. "/default.jpg' id='image_musique2'><br><p style='color: rgb(204, 197, 197);' id='texte_musique2' value=''>" . base64_decode($mots2[$i2]) . "</p></div>";
                            echo '<audio id="myAudio2'.$i2.'"></audio><script>playMusic2("MUSIQUES/'.$path.'", "myAudio2'.$i2.'")</script>';
                            echo '<script>document.addEventListener("DOMContentLoaded", function() { var playMusic = document.getElementById("play"); var pauseMusic = document.getElementById("pause"); if (playMusic && pauseMusic && document.getElementById("recherche2'.$i2.'") && document.getElementById("recherche2'.$i2.'")) { document.getElementById("recherche2'.$i2.'").addEventListener("click", function() { document.title = "'.base64_decode($mots2[$i2]).'"; document.cookie = "videotitle='.base64_decode($mots2[$i2]).'"; if(!document.getElementById("img_musique")){const img_image_12345image = document.createElement("img"); img_image_12345image.id="img_musique"; img_image_12345image.src="MUSIQUES/'.$path.'/default.jpg"; img_image_12345image.style = "display: block!important"; const paragraph_paratexte_12345 = document.createElement("p"); paragraph_paratexte_12345.textContent = "'.base64_decode($mots2[$i2]).'"; paragraph_paratexte_12345.id = "titre_musique"; paragraph_paratexte_12345.style = "display: block!important"; const playerDiv = document.querySelector(".player"); playerDiv.appendChild(img_image_12345image); playerDiv.appendChild(paragraph_paratexte_12345);} document.getElementById("myAudio2'.$i2.'").play(); playMusic.style.display = "none"; pauseMusic.style.display = "block"; pauseMusic.addEventListener("click", function() { document.getElementById("myAudio2'.$i2.'").pause(); pauseMusic.style.display = "none"; playMusic.style.display = "block"; }); playMusic.addEventListener("click", function() { document.getElementById("myAudio2'.$i2.'").play(); playMusic.style.display = "none"; pauseMusic.style.display = "block"; }); }); } else { console.error("One or more elements are missing in the DOM."); } });</script>';
                            //echo "<img src='" . $videotitle . "/default.jpg' id='img_musique' style='display: block!important;'><p id='titre_musique' style='display: block!important;'>" . $videotitle . "</p>"; 
                        }
                    }
                } 
            }
            break;
        case 'playlist':
            //rien pr l'instant
            echo "<script>document.getElementById('content').style = 'justify-content: flex-start;';</script>";
            $pseudo = $_SESSION['pseudo'];

            if ($pseudo) {
                $req2 = $bdd->prepare("SELECT playlist FROM users WHERE pseudo = :pseudo");
                $req2->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
                $req2->execute();
                $rep2 = $req2->fetch();

                // afficher le contenu pour déboguer
                echo "<script>console.log('playlist: " . $rep2['playlist'] . "');</script>";

                if ($rep2 && $rep2['playlist']) {
                    $mots2 = explode(", ", $rep2['playlist']);
                    $mot_total2 = count($mots2);
                    for ($i2 = 0; $i2 < $mot_total2; $i2++) {
                        if (!empty($mots2[$i2])) {//mtn chrch son image depuis le dossier
                            $path = base64_encode(spotifysearchgenre(base64_decode($mots2[$i2])))."/".base64_encode(spotifysearchartistname(base64_decode($mots2[$i2])))."/".$mots2[$i2];
                            echo "<div class='recherche2' id='recherche2".$i2."'><img src='MUSIQUES/" .$path. "/default.jpg' id='image_musique2'><br><p style='color: rgb(204, 197, 197);' id='texte_musique2' value=''>" . base64_decode($mots2[$i2]) . "</p></div>";
                            echo '<audio id="myAudio2'.$i2.'"></audio><script>playMusic2("MUSIQUES/'.$path.'", "myAudio2'.$i2.'")</script>';
                            echo '<script>document.addEventListener("DOMContentLoaded", function() { var playMusic = document.getElementById("play"); var pauseMusic = document.getElementById("pause"); if (playMusic && pauseMusic && document.getElementById("recherche2'.$i2.'") && document.getElementById("recherche2'.$i2.'")) { document.getElementById("recherche2'.$i2.'").addEventListener("click", function() { document.title = "'.base64_decode($mots2[$i2]).'"; document.cookie = "videotitle='.base64_decode($mots2[$i2]).'"; if(!document.getElementById("img_musique")){const img_image_12345image = document.createElement("img"); img_image_12345image.id="img_musique"; img_image_12345image.src="MUSIQUES/'.$path.'/default.jpg"; img_image_12345image.style = "display: block!important"; const paragraph_paratexte_12345 = document.createElement("p"); paragraph_paratexte_12345.textContent = "'.base64_decode($mots2[$i2]).'"; paragraph_paratexte_12345.id = "titre_musique"; paragraph_paratexte_12345.style = "display: block!important"; const playerDiv = document.querySelector(".player"); playerDiv.appendChild(img_image_12345image); playerDiv.appendChild(paragraph_paratexte_12345);} document.getElementById("myAudio2'.$i2.'").play(); playMusic.style.display = "none"; pauseMusic.style.display = "block"; pauseMusic.addEventListener("click", function() { document.getElementById("myAudio2'.$i2.'").pause(); pauseMusic.style.display = "none"; playMusic.style.display = "block"; }); playMusic.addEventListener("click", function() { document.getElementById("myAudio2'.$i2.'").play(); playMusic.style.display = "none"; pauseMusic.style.display = "block"; }); }); } else { console.error("One or more elements are missing in the DOM."); } });</script>';
                            //echo "<img src='" . $videotitle . "/default.jpg' id='img_musique' style='display: block!important;'><p id='titre_musique' style='display: block!important;'>" . $videotitle . "</p>"; 
                        }
                    }
                } 
            }
    }
}
    ?>
    <?php
$videoId = "";
$champ_recherche = $_GET["search"];
if($champ_recherche){
$genre = spotifysearchgenre($champ_recherche);
$artistname = spotifysearchartistname($champ_recherche);
unset($_COOKIE);
echo "<script>document.cookie = 'genre=".$genre."'</script>";
$mot_cle = "musique " . $champ_recherche;
$apiKey = 'ma super clef api';
$url = 'https://www.googleapis.com/youtube/v3/search?part=snippet&q=' . urlencode($mot_cle) . '&maxResults=' . '3' . '&key=' . $apiKey;
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_OPTIONS, CURLSSLOPT_NATIVE_CA);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
$requete_http = curl_exec($ch);
curl_close($ch);
$requete_ok = true;
if($requete_http == false){
    $requete_ok = false;
}
if ($requete_ok) {
    $donnee_json = json_decode($requete_http, true);
}
if($requete_ok && !array_key_exists('items', $donnee_json)){
    $requete_ok = false;
}
if($requete_ok && count($donnee_json['items']) == 0){
    $requete_ok = false;
}
if($requete_ok && !array_key_exists('id', $donnee_json['items'][0])){
    $requete_ok = false;
}
if($requete_ok && !array_key_exists('videoId', $donnee_json['items'][0]['id'])){
    $requete_ok = false;
}
if($requete_ok && $donnee_json['items'][0]['id']['videoId'] == ""){
    $requete_ok = false;
}

if (!$requete_ok) {
    echo "error";
    return;
}

$videoId = $donnee_json['items'][0]['id']['videoId'];
file_put_contents("file.json", $videoId);

$request = 'https://www.googleapis.com/youtube/v3/videos?id=' . $videoId . '&key=' . $apiKey . '&part=snippet';
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_OPTIONS, CURLSSLOPT_NATIVE_CA);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $request);
$suite = curl_exec($ch);
curl_close($ch);
if($suite !== false){
    $donnee = json_decode($suite);
    $videoTitle = $donnee->items[0]->snippet->title;
    file_put_contents("name2.json", $videoTitle);
}//$videoTitle = "OMR : CHATELET (Clip Officiel)";
echo "<script>document.cookie = 'pathreel=MUSIQUES/".base64_encode($genre)."/".base64_encode($artistname)."/".base64_encode($videoTitle)."'</script>";
if(file_exists("MUSIQUES/".base64_encode($genre)."/".base64_encode($artistname)."/".base64_encode($videoTitle))){
        echo '<script>var musique_titre_onglet = "'.$videoTitle.'";</script>';
        $req1 = $bdd->query("SELECT likes_musique FROM users WHERE pseudo = '$pseudo'");
        $rep1 = $req1->fetch();
        
        $mots1 = explode(", ", $rep1['likes_musique']);
        $mot_total1 = count($mots1);
        for ($i1 = 0; $i1 < $mot_total1; $i1++) {
            if ($mots1[$i1] === $videoTitle) {
                echo "<script>document.cookie = 'like=yes';</script>";
                break;
            }else{
                echo "<script>document.cookie = 'like=no';</script>";
            }
        }
    echo "<div id='recherche'><img src='MUSIQUES/" . base64_encode($genre)."/".base64_encode($artistname)."/".base64_encode($videoTitle) . "/default.jpg' id='image_musique'><br><p style='color: rgb(204, 197, 197);' id='texte_musique' value=''>" . $videoTitle . "</p></div>";
    echo '<audio id="myAudio"></audio><script>playMusic("MUSIQUES/'.base64_encode($genre)."/".base64_encode($artistname)."/".base64_encode($videoTitle).'")</script>';
    
    shell_exec("rm -r name2.json");
}else{
file_put_contents("musique.lst", $champ_recherche . "\n", FILE_APPEND);

$apiUrl = 'https://www.googleapis.com/youtube/v3/videos?part=snippet&id=' . $videoId . '&key=' . $apiKey;

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_OPTIONS, CURLSSLOPT_NATIVE_CA);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $apiUrl);
$response = curl_exec($ch);
curl_close($ch);

if ($response !== false) {
    $data = json_decode($response, true);
    $thumbnailUrl = $data['items'][0]['snippet']['thumbnails']['default']['url'];
    file_put_contents("data.json", $response);
    shell_exec("curl -O " . $thumbnailUrl);
} else {
    echo 'Erreur lors de la requête à l\'API YouTube';
}
$url_url = "https://www.youtube.com/api/timedtext?v=" . $videoId . "&asr_langs=ar&caps=asr&xorp=true&hl=fr&ip=0.0.0.0&ipbits=0&expire=1648958812&sparams=ip,ipbits,expire,v,asr_langs,caps,xorp&signature=27C8A2C8B5C4D4497C38432FAAD4DE4405B418C4.D74794E1125C6F9D896E3C52228F0E3DC1B2A5C4&key=yttt1&kind=asr&lang=ar&fmt=srv3";

// Récupérer les données JSON à partir de l'API YouTube
//$response_response = file_get_contents($url_url);
shell_exec("curl -O " . $url_url);
// Convertir les données JSON en tableau associatif
//$data_data = json_decode($response_response, true);

file_put_contents("data.data.json", $response_response);
shell_exec("python3 main.py");
shell_exec("mkdir -p MUSIQUES/".base64_encode($genre)."/".base64_encode($artistname)."/".base64_encode($videoTitle));
shell_exec('mv audio.mp3 "MUSIQUES/' . base64_encode($genre)."/".base64_encode($artistname)."/".base64_encode($videoTitle) . '"');
shell_exec("rm -r audio.mp3");
shell_exec('mv default.jpg "MUSIQUES/' . base64_encode($genre)."/".base64_encode($artistname)."/".base64_encode($videoTitle) . '"');
shell_exec("rm -r default.jpg");
shell_exec("rm -r name2.json");//IAM - Demain c'est loin (Clip officiel)
shell_exec("rm -r data.json");
shell_exec("rm -r data.data.json");

if(!file_exists("MUSIQUES/" . base64_encode($genre)."/".base64_encode($artistname)."/".base64_encode($videoTitle) . "/default.jpg")){
    echo "<script>alert('Une erreur est survenue lors de la lecture de cette musique! Choisis en une autre');</script>";
    file_put_contents("/var/www/discord/logs_err_musigo.out", "MUSIQUES/" . base64_encode($genre)."/".base64_encode($artistname)."/".base64_encode($videoTitle) . "/default.jpg; ", FILE_APPEND);
}
elseif(!file_exists("MUSIQUES/" . base64_encode($genre)."/".base64_encode($artistname)."/".base64_encode($videoTitle) . "/audio.mp3")){
    echo "<script>alert('Une erreur est survenue lors de la lecture de cette musique! Choisis en une autre');</script>";
    file_put_contents("/var/www/discord/logs_err_musigo.out", "MUSIQUES/" . base64_encode($genre)."/".base64_encode($artistname)."/".base64_encode($videoTitle) . "/audio.mp3; ", FILE_APPEND);
}

//shell_exec('mv paroles.txt "' . $videoTitle . '"');//sous titres déplacé mais peut etre pas supprimé
file_put_contents("musiques.txt", $videoTitle . "\n", FILE_APPEND);
echo "<div id='recherche'><img src='MUSIQUES/" . base64_encode($genre)."/".base64_encode($artistname)."/".base64_encode($videoTitle) . "/default.jpg' id='image_musique'><br><p style='color: rgb(204, 197, 197);' id='texte_musique' value=''>" . $videoTitle . "</p></div>";
echo '<audio id="myAudio"></audio><script>playMusic("MUSIQUES/'.base64_encode($genre)."/".base64_encode($artistname)."/".base64_encode($videoTitle).'")</script>';
echo "<script>location.reload();</script>";
//setcookie("musique", $videoTitle);
}
}//faire en sorte que le script fasse une requete a youtube afin de recuperer le titre de la video demandé, si le dossier qui se nomme par le titre de la video n'existe pas le creer avec le code si dessu sinon eviter de surcharger le serveur et d'epuiser mon quota de point pour l'api de youtube
?><!--reçevoir la video dans un cookie pour l'afficher-->
        <!--content 70%-->
        <!---->
        <div class="musique">
        </div><!--quand une recherche et effectué sa l'envoie avec l'api de youtube et sa me telecharge sur le serveur les premiers 10 ou 5 resultats, filtre: musique {titre demandé} pui sa le telecharge sur le serv afin d'en extraire l'image (miniature)-->
    </div>
    <div class="player" id="player">
        <!--player 100% taille 10 a 20%-->
        <!--<img src="logo de la musique en cours de lecture" alt="">-->
        <?php
        if($videoId){
            echo "<img src='MUSIQUES/" . base64_encode($genre)."/".base64_encode($artistname)."/".base64_encode($videoTitle) . "/default.jpg' id='img_musique'><p id='titre_musique'>" . $videoTitle . "</p>";
        }
        ?>
        <?php
    if($_COOKIE){
        extract($_COOKIE);
    if(isset($videotitle)){//si la page n'est pas interragie sa ne fonctionne pas, donc faire en sorte que des que le bouton play est qliqué si il ya des cookies sa nous lance a ou sa en était
        echo "<img src='MUSIQUES/" . base64_encode($genre)."/".base64_encode($artistname)."/".base64_encode($videoTitle) . "/default.jpg' id='img_musique' style='display: block!important;'><p id='titre_musique' style='display: block!important;'>" . $videotitle . "</p>";//si l'image et cliquer invoquer du js pour envoyer le cookie
        echo '<audio id="myAudio" currentTime="' . $musique . '"></audio><script>playMusic("MUSIQUES/'.base64_encode($genre)."/".base64_encode($artistname)."/".base64_encode($videoTitle).'")</script>';
    }
    }
    ?>
        <div class="button_music">
        <i class="fa-solid fa-backward" id="arriere"></i>
            <i class="fa-solid fa-play" id="play"></i>
            <i class="fa-solid fa-pause" id="pause"></i><!--le masque si l'audio n'est pas joué ou sur pause et afficher selui d'en haut avec du javascript-->
            <i class="fa-solid fa-forward" id="avantte"></i>
            <i class="fa-regular fa-heart" id="heart"></i><!--sinon mettre quand c'est liké le fa-solid-->
            <i class="fa-solid fa-plus" id="plus" style="cursor: pointer;"></i>
            <script>
                var clique = 0;
                var clique2 = 0;
            </script>
            <?php
            //mettre avec la fonction array_search le coeur sur iké ou unlike selon la valeure
            $req = $bdd->prepare("SELECT * FROM users WHERE pseudo = :pseudo");
            $req->execute(['pseudo' => $pseudo]);
            $rep = $req->fetch();
            
            $mots = explode(", ", $rep['playlist']);
            $arraynumber = array_search(base64_encode($videoTitle), $mots);
            //echo "<script>console.log('".base64_encode($videoTitle)."')</script>";
            if($mots[$arraynumber] == base64_encode($videoTitle)){
                echo "<script>document.getElementById('plus').className = 'fa-solid fa-check'</script>";
                echo "<script>clique = 1;</script>";
            }else{
                echo "<script>clique = 0;</script>";
            }


            //HEART PARTY


            $mots2345 = explode(", ", $rep['likes_musique']);
            $arraynumber12345 = array_search(base64_encode($videoTitle), $mots2345);
            //echo "<script>console.log('".base64_encode($videoTitle)."')</script>";
            echo "<script>console.log('tout:".$videoTitle."')</script>";
            if($mots2345[$arraynumber12345] == base64_encode($videoTitle)){
                echo "<script>document.getElementById('heart').className = 'fa-solid fa-heart'</script>";
                echo "<script>clique2 = 1;</script>";
            }else{
                echo "<script>clique2 = 0;</script>";
            }
            ?>
            <!--
            $req = $bdd->prepare("SELECT likes_musique FROM users WHERE pseudo = :pseudo");
            $req->execute(['pseudo' => $pseudo]);
            $rep = $req->fetch();
                    
            $mot123456s = explode(", ", $rep['likes_musique']);
            $nouveaux_titres123456789 = implode(", ", $mot123456s);
            $countage = count($nouveaux_titres123456789);
            while($countage >= 0){
                $countage--;
                if($nouveaux_titres123456789 == base64_encode($videoTitle)){
                    echo "
                    <script>
                    document.getElementById('heart').class = 'fa-solid fa-heart';
                    </script>
                    ";
                }
}-->


<script>//0 = unplaylist, 1 = playlist 0 par defaut mais on regarde quand meme si c'est dans la playlist ou pas
        var coeur1 = document.getElementById("plus");//a remplacer par plus
        coeur1.addEventListener("click", function() {
        //console.log("appui!");
        var titre_de_la_video = getCookie("videotitle");
        var pseudo = getCookie("pseudo");
        //var action = (coeur.className == "fa-solid fa-plus") ? "playlist" : "unplaylist";
        if (clique == 0){
            action1 = "playlist";
            coeur1.className = "fa-solid fa-check";
            clique = 1;



//////////////ON LE CACHE POUR LA PROD MAIS PROJET EN COURS///////////////



            /*document.getElementById("containerParamPlaylist").style.display = "flex";
            //document.getElementById("plusdeplaylist").style.display: "block";
            var menuee = document.getElementById("menu");
            var headerr = document.getElementById("header");
            var playerr = document.getElementById("player");
            var containerr = document.getElementById("content");
            menuee.style = "filter: blur(5px);";
            headerr.style = "filter: blur(5px);";
            playerr.style = "filter: blur(5px);";
            containerr.style = "filter: blur(5px);";//format dans al db: MaPlaylist: coucou, omr chatelet, ziak chasse-croise;
            //creation dynamique d'une deuxiemme div sur la droite et pour les affiche dans le temps a chaque clique on faira une fonction qui rgd dans la db tout se qui commence par ":" ou autre chose
            document.getElementById("plusdeplaylist").addEventListener("click", function(){
            var newDiv = document.createElement("div");
            newDiv.className="plusdeplaylist";
            document.getElementById("containerParamPlaylist").appendChild(newDiv);

            var inputtext = document.createElement("input");
            inputtext.type="text";
            inputtext.className = "inputext";
            inputtext.placeholder = "Nom de la playlist";
            newDiv.appendChild(inputtext);

        });
*/



//////////////ON LE CACHE POUR LA PROD MAIS PROJET EN COURS///////////////




        }else if(clique == 1){
            action1 = "unplaylist";
            coeur1.className = "fa-solid fa-plus";
            clique = 0;
        }
        // Mise à jour de la classe CSS du cœurcoeur.className = (action === "playlist") ? "fa-solid fa-check" : "fa-solid fa-plus";
        //coeur.className = (action === "playlist") ? "fa-solid fa-check" : "fa-regular fa-plus";
        
        // Envoi de la requête AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "index.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                console.log(xhr.responseText); // Affichage de la réponse du serveur pour debug
            }
        };//essayer d'url encoder tt sa
        xhr.send("action1=" + action1 + "&titre1=" + titre_de_la_video + "&pseudo=" + pseudo);
    });//au niveau de la requete sa fonctionne pas
</script>

<?php
if (isset($_POST["action1"])) {
    $action1 = $_POST['action1'];
    $titre1 = $_POST['titre1'];
    $pseudo = $_POST['pseudo'];

    if ($action1 == 'playlist') {
        $sql = "UPDATE users SET playlist = CONCAT(IFNULL(playlist, ''), '".base64_encode($titre1)."', ', ') WHERE pseudo = :pseudo";
    } else if ($action1 == 'unplaylist') {
        $req = $bdd->prepare("SELECT playlist FROM users WHERE pseudo = :pseudo");
        $req->execute(['pseudo' => $pseudo]);
        $rep = $req->fetch();
        $mots = explode(", ", $rep['playlist']);
        $mot_total = count($mots);
        echo "<script>console.log('$titre1')</script>";
        $endroitdelaliste = array_search(base64_encode($titre1), $mots);
        unset($mots[$endroitdelaliste]);
        $mots = array_values($mots);
        
        $nouveaux_titres = implode(", ", $mots);
        
        $update = $bdd->prepare("UPDATE users SET playlist = :playlist WHERE pseudo = :pseudo");
        $update->execute(['playlist' => $nouveaux_titres, 'pseudo' => $pseudo]);
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

<!--################################-->
<!--##########HEART PARTY###########-->
<!--################################-->
            <script>
                var coeur = document.getElementById("heart");
                coeur.style = "cursor: pointer;";
        coeur.addEventListener("click", function() {
        var titre_de_la_video = getCookie("videotitle");
        var pseudo = getCookie("pseudo");
        if (clique2 == 0){
            action = "like";
            coeur.className = "fa-solid fa-heart";
            clique2 = 1;
        }else if(clique2 == 1){
            action = "unlike";
            coeur.className = "fa-regular fa-heart";
            clique2 = 0;
        }
        
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
if (isset($_POST["action"])) {
    $action = $_POST['action'];
    $titre = $_POST['titre'];
    $pseudo = $_POST['pseudo'];

    if ($action == 'like') {
        $sql = "UPDATE users SET likes_musique = CONCAT(IFNULL(likes_musique, ''), '".base64_encode($titre)."', ', ') WHERE pseudo = :pseudo";
    } else if ($action == 'unlike') {
        $req = $bdd->prepare("SELECT likes_musique FROM users WHERE pseudo = :pseudo");
        $req->execute(['pseudo' => $pseudo]);
        $rep = $req->fetch();
        
        $mots = explode(", ", $rep['likes_musique']);
        $mot_total = count($mots);
        
        $endroitdelaliste2 = array_search(base64_encode($titre), $mots);
        unset($mots[$endroitdelaliste2]);
        
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
    <script src="index_main.js"></script>
</body>
</html>