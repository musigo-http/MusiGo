document.addEventListener("DOMContentLoaded", function() {
    var playMusic = document.getElementById("play");
    var pauseMusic = document.getElementById("pause");

    if (playMusic && pauseMusic && document.getElementById("recherche2'.$i2.'") && document.getElementById("recherche2'.$i2.'")) {
        document.getElementById("recherche2'.$i2.'").addEventListener("click", function() {
            document.getElementById("myAudio2'.$i2.'").play();
            playMusic.style.display = "none";
            pauseMusic.style.display = "block";
            pauseMusic.addEventListener("click", function() {
                document.getElementById("myAudio2'.$i2.'").pause();
                pauseMusic.style.display = "none";
                playMusic.style.display = "block";
            });
            playMusic.addEventListener("click", function() {
                document.getElementById("myAudio2'.$i2.'").play();
                playMusic.style.display = "none";
                pauseMusic.style.display = "block";
            });
        });
    } else {
        console.error("One or more elements are missing in the DOM.");
    }
});






/*
//quand je clique sa arrete tout les autres elements audio
            //moi je suis le nombre $i2
            //if $i2 n'est pas egale a zero donc se n'est pas le premier
            //regarder le nombre de musique likées avec la var php
            if("'.$i2.'" === "0"){
                //mettre pause a tout les autres elements audio boucler jusque a max var php
                var incrementation_i = 0;
                while(incrementation_i !== parseInt("'.$mot_total2.'")){
                    document.getElementById("myAudio2"+incrementation_i).pause();
                    incrementation_i++;
                }
            }else{
                //retirer de 0 jsq a $i2 et de $i2 jsq a max
                var incrementation_o = 0;
                while(incrementation_o !== parseInt("'.$i2.'") - 1){//le -1 a verifier
                    document.getElementById("myAudio2"+incrementation_o).pause();
                    incrementation_o++;
                }
                if("'.$i2.'" !== "'.$mot_total2.'"){
                var incrementation_s = parseInt("'.$i2.'") + 1;
                while(incrementation_s !== parseInt("'.$mot_total2.'")){
                    document.getElementById("myAudio2"+incrementation_s).pause();
                    incrementation_s++;
                }
                }
            }
*/