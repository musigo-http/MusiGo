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
var home = document.getElementById("likes");
home.addEventListener("click", function(){
    //set le cookie page sur like
    document.cookie = "page=likes";
    window.location.href = "/index.php";
    //afficher les likes via une requete sql
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
console.log(parseInt(getCookie("musique")));

run_music.addEventListener("click", function(){
    if(getCookie("musique") !== null){
    audio.currentTime = parseInt(getCookie("musique"));
    audio.play();
    console.log(audio.currentTime);
    }else{
        audio.play();
    }
});
stop_music.addEventListener("click", function(){
    audio.pause();
});
var seekBar = document.getElementById("seekBar");
var admin = document.getElementById("admin");
admin.addEventListener("click", function(){
    window.location.href = "/panel_admin.php";
});
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
        audio.play();
        image.style.display = "block";
        titre.style.display = "block";
        var titrevideo = videotitre123.textContent;
        document.cookie = "videotitle=" + titrevideo;
        if(getCookie("like") === "yes"){
        document.getElementById("heart").style = "display: block;";
        document.getElementById("heart").className = "fa-solid fa-heart";
    }else{
        document.getElementById("heart").style = "display: block;";
    }
});
}
audio.addEventListener("ended", function(){
    document.cookie = "0";
});