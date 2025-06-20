<meta name="robots" content="noindex">
<?php
session_start();
extract($_POST);
if(strlen($pseudo) > 5){
    echo "<script>alert('ton pseudo fait plus de 5 caractère, choisie un pseudo plus courts! max: 5 caractère!');</script>";
    echo "<script>window.location.href = '/inscription.php';</script>";
}else{
$datedujour = date("Y-m-d H:i:s");
$svname = "90.48.111.47";
$username = "root";
$password = "Mon super mot de passe";
try{
    $bdd = new PDO("mysql:host=$svname; unix_socket=/run/mysqld/mysqld.sock; dbname=users;", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo "ERREUR : ".$e->getMessage();
}
$requete2 = $bdd->prepare("SELECT * FROM users");
$requete2->execute();
while($reponse2 = $requete2->fetch()){
    //var_dump($reponse2['pseudo']);
    if($reponse2['pseudo'] === $pseudo){
        echo "<script>alert('ton pseudo est déja utilisé, trouve en un autre!');</script>";
        echo "<script>window.location.href = '/inscription.php';</script>";
        exit();
    }
}
//if($reponse2['pseudo'] === $pseudo){
    //echo "<script>alert('pseudo déja pris prend en un autre!');</script>";
    #echo "<script>window.location.href = '/inscription.php';</script>";
//}//else{
//}
if(isset($_POST['ok'])){
    extract($_POST);
    $password2 = hash('sha256', $password);
    $requete = $bdd->prepare("INSERT INTO users VALUES (0, '$email', '$password2', '$pseudo', '$datedujour', 'user', '', '', '', '', '')");
    $requete->execute();
}
$requete = $bdd->prepare("SELECT * FROM users WHERE email = :email");
$requete->bindParam(':email', $email);
$requete->execute();
$rep = $requete->fetch();
/*if($_POST["resterconnectername"] == "on"){
    echo '<script>
        document.cookie = "pseudononhashe='.$pseudo.'; path=/; expires=Fri, 31 Dec 9999 23:59:59 GMT";
        document.cookie = "pseudohashed='.$password2.'; path=/; expires=Fri, 31 Dec 9999 23:59:59 GMT";
    </script>';
}*/
$_SESSION['user_id'] = $rep['id'];
$_SESSION['pseudo'] = $rep['pseudo'];
$_SESSION['privileges'] = $rep['privileges'];
header("Location: /index.php");
exit();
}
?>