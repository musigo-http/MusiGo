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