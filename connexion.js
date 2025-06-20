document.getElementById("oeuilOuvert").addEventListener("click", function(){
    document.getElementById("oeuilOuvert").style.display = "none";
    document.getElementById("oeuilFerme").style.display = "block";
    document.getElementById("password").type = "text";
});

document.getElementById("oeuilFerme").addEventListener("click", function(){
    document.getElementById("oeuilOuvert").style.display = "block";
    document.getElementById("oeuilFerme").style.display = "none";
    document.getElementById("password").type = "password";
});
/*
verif = false;
document.getElementById("connecter").addEventListener("input", function(){
    if(document.getElementById("connecter").checked){
        verif = true;
        document.getElementById("persistcookie").textContent = "true";
    }else{
        verif = false;
        document.getElementById("persistcookie").textContent = "false";
    }
});*/