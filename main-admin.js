function getCookie(nomCookie) {
    let cookies = document.cookie; // On obtient la chaîne de tous les cookies
    let listeCookies = cookies.split('; '); // On sépare les cookies individuels
    let cookieTrouve = null; // Variable pour stocker le cookie trouvé
    for(let i = 0; i < listeCookies.length; i++) {
      let unCookie = listeCookies[i].split('='); // On sépare le nom et la valeur
      if(unCookie[0] === nomCookie) {
        cookieTrouve = unCookie[1]; // Stocke la valeur du cookie trouvé
        break; // Sort de la boucle car le cookie recherché a été trouvé
      }
    }
    return cookieTrouve; // Renvoie la valeur du cookie trouvé
}


var id_der = parseInt(getCookie("dernier_id"));
i = 0;
while(i !== id_der){
    i++;
    var nyan_cat = getCookie("id_users" + i);
    var script = document.createElement('script');
    var script2 = document.createElement('script');

    // Ajouter du code JavaScript à ce script
    script.innerHTML = "document.getElementById('" + nyan_cat + "').addEventListener('click', function(){document.cookie = 'commande=UPDATE `users` SET `privileges` = \"ADMIN\" WHERE `users`.`pseudo` = \""+nyan_cat+"\"'});";
    script2.innerHTML = "document.getElementById('" + nyan_cat + "').addEventListener('click', function(){document.cookie = 'commande2=UPDATE `users` SET `pseudo` = \""+nyan_cat+" - ADMIN\" WHERE `users`.`pseudo` = \""+nyan_cat+"\"';window.location.href = \"/refresh_admin.php\";});";
    //UPDATE `users` SET `pseudo` = 'API - ADMIN' WHERE `users`.`pseudo` = 'API'; de API a API - ADMIN
    document.body.appendChild(script);
    document.body.appendChild(script2);
}