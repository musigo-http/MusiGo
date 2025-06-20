var pseudonyme = document.getElementById("pseudo");
var pseudonymeTexte = pseudonyme.innerText;

if(pseudonymeTexte.length > 8){
    var premierPartie = pseudonymeTexte.substring(0, 13); // Les 8 premiers caractères
    var deuxiemePartie = pseudonymeTexte.substring(13); // Le reste des caractères après le 8ème

    // Créer un nouveau texte avec la balise <br> insérée entre les deux parties
    var nouveauTexte = premierPartie + '<br>' + deuxiemePartie;

    // Mettre à jour le texte du pseudonyme avec le nouveau texte contenant la balise <br>
    pseudonyme.innerHTML = nouveauTexte;
}
var deco_btn = document.getElementById("deco");
deco_btn.addEventListener("click", function(){
        const cookies = document.cookie.split("; ");
        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i];
            const eqPos = cookie.indexOf("=");
            const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
            document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
        }
    window.location.href = "/connexion.php";
});
var home = document.getElementById("home");
home.addEventListener("click", function(){
    document.cookie = "page=home";
    window.location.href = "/index.php";//changer selon le nom de domaine ou ip voir meme port qui a été mis pour la configuration du serveur
    //set le cookie page sur home
});
var home2 = document.getElementById("likes");
home2.addEventListener("click", function(){
    //set le cookie page sur like
    document.cookie = "page=likes";
    window.location.href = "/index.php";
    //afficher les likes via une requete sql
});
var home3 = document.getElementById("playlist");
home3.addEventListener("click", function(){
    document.cookie = "page=playlist";
    window.location.href = "/index.php";
});


//document.getElementById("likes").addEventListener("click", function(){
//    document.cookie = "section=likes";
    
//});
var audio2 = document.querySelectorAll("audio");
var audio = document.getElementById("myAudio");
var musique = document.getElementById("recherche");
var run_music = document.getElementById("play");
var stop_music = document.getElementById("pause");
var image = document.getElementById("img_musique");
var titre = document.getElementById("titre_musique");
var wait = document.getElementById("next");
var videotitre123 = document.getElementById("texte_musique");

function getCookie(nomCookie) {
    let cookies = document.cookie;
    let listeCookies = cookies.split("; ");
    let cookieTrouve = null;
    for(let i = 0; i < listeCookies.length; i++) {
      let unCookie = listeCookies[i].split("=");
      if(unCookie[0] === nomCookie) {
        cookieTrouve = unCookie[1];
        break;
      }
    }
    return cookieTrouve;
  }
  if(document.getElementById("croix")){
  document.getElementById("croix").addEventListener('mouseover', function() {
    document.getElementById("croix").className = "fa-solid fa-circle-xmark";
});

// Événement mouseout
document.getElementById("croix").addEventListener('mouseout', function() {
    document.getElementById("croix").className = "fa-regular fa-circle-xmark";
});
document.getElementById("croix").addEventListener("click", function(){
    var menuee = document.getElementById("menu");
        var headerr = document.getElementById("header");
        var playerr = document.getElementById("player");
        var containerr = document.getElementById("content");
        menuee.style = "filter: none;";
        headerr.style = "filter: none;";
        playerr.style = "filter: none;";
        containerr.style = "filter: none;";
        document.getElementById("containerParamPlaylist").style.display = "none";
});}
document.getElementById("arriere").addEventListener("click", function(){
    alert("fonction pas encore disponible!");
});
document.getElementById("avantte").addEventListener("click", function(){
    autre_musique_aleatoire_du_meme_style();
});
//pr tte les medias sessions pour les btns physiques les notifs etc
if ('mediaSession' in navigator) {
    /*navigator.mediaSession.setActionHandler('previoustrack', function() {
        musique_precedente(); // à créer toi-même si tu veux le support aussi
    });*/
    navigator.mediaSession.setActionHandler('nexttrack', function() {
        autre_musique_aleatoire_du_meme_style();
    });
    navigator.mediaSession.setActionHandler('play', function() {
        audio.play();
    });

    navigator.mediaSession.setActionHandler('pause', function() {
        audio.pause();
    });
}
console.log(parseInt(getCookie("musique")));
run_music.addEventListener("click", function(){
    if(document.title !== "MusiGo"){
    if(getCookie("musique") !== null){
    audio.currentTime = parseInt(getCookie("musique"));
    audio.play();
    console.log(audio.currentTime);
    }else{
        audio.play();
    }}
});
stop_music.addEventListener("click", function(){
    audio.pause();
});
var seekBar = document.getElementById("seekBar");
if(getCookie("privileges") == "ADMIN"){
var admin = document.getElementById("admin");
admin.addEventListener("click", function(){
    window.location.href = "/panel_admin.php";
});}
audio.addEventListener("timeupdate", function() {
    var newPosition = (audio.currentTime / audio.duration) * 100;
    seekBar.value = newPosition;
    document.cookie = "musique=" + audio.currentTime;
});

seekBar.addEventListener("change",function(){
    setTimeout(()=>{
        audio.currentTime = (seekBar.value / 100) * audio.duration;  
    })
});

audio.addEventListener("play", function() {
    console.log(getCookie("musique"));
    console.log(audio.currentTime);
    run_music.style.display = "none";
    stop_music.style.display = "block";
});

audio.addEventListener("pause", function() {
    console.log("cookie: " + getCookie("musique"));
    console.log(audio.currentTime);
    run_music.style.display = "block";
    stop_music.style.display = "none";
});
if(musique){
    musique.addEventListener("click", function(){
        document.title = musique_titre_onglet;
        audio.play();
        image.style.display = "block";
        titre.style.display = "block";
        var titrevideo = videotitre123.textContent;
        document.cookie = "videotitle=" + titrevideo;
        document.getElementById("plus").style = "display: block;";
        document.getElementById("heart").style = "display: block;";
        /*if(getCookie("like") === "yes"){
        document.getElementById("heart").style = "display: block;";
        document.getElementById("heart").className = "fa-solid fa-heart";
    }else{
        document.getElementById("heart").style = "display: block;";
    }*/
});
}
function autre_musique_aleatoire_du_meme_style() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            try {
                var data = JSON.parse(xhr.responseText);

                console.log("🎵 Nouveau path reçu :", data.path);

                var audio = document.getElementById("myAudio");
                if (!audio) {
                    audio = document.createElement("audio");
                    audio.id = "myAudio";
                    document.body.appendChild(audio);
                }

                playMusic(data.path);
                audio.load();
                audio.oncanplaythrough = () => {
                    audio.play();

                };
                    document.getElementById("image_musique").src = data.path+"/default.jpg?cache=" + new Date().getTime();
                    document.getElementById("img_musique").src = data.path+"/default.jpg?cache=" + new Date().getTime();
                    var split = data.path.split("/");
                    document.getElementById("titre_musique").textContent = atob(split[3]);
                    document.getElementById("texte_musique").textContent = atob(split[3]);
                    document.title = atob(split[3]);
                    console.log("valeure de titre musique: "+document.getElementById("titre_musique").atob(textContent));

                // Optionnel : si tu as une fonction perso pour gérer le lecteur
                /*if (typeof playMusic === "function") {
                    playMusic(data.path);*/
               // }

            } catch (e) {
                console.error("Erreur dans la réponse JSON :", e);
                console.log("Réponse brute :", xhr.responseText);
            }
        }
    };

    xhr.send("musiquealeatoire=suivant");
}

audio.addEventListener("ended", function(){
    document.cookie = "0";
    autre_musique_aleatoire_du_meme_style();
});