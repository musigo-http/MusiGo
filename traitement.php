<?php
extract($_POST);
if(strlen($pseudo) > 5){
    echo "<script>alert('ton pseudo fait plus de 5 caractère, choisie un pseudo plus courts! max: 5 caractère!');</script>";
    echo "<script>window.location.href = '/inscription.php';</script>";
}else{
$datedujour = date("Y-m-d H:i:s");
$svname = "localhost";
$username = "root";
$password = "Mat.at89";
try{
    $bdd = new PDO("mysql:host=$svname; unix_socket=/tmp/mysql.sock; dbname=utilisateurs;", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo "ERREUR : ".$e->getMessage();
}
$requete2 = $bdd->prepare("SELECT * FROM users");
$requete2->execute();
while($reponse2 = $requete2->fetch()){
    var_dump($reponse2['pseudo']);
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
    $requete = $bdd->prepare("INSERT INTO users VALUES (0, '$email', '$password', '$pseudo', '$datedujour', 'user', '', '', '')");
    $requete->execute();
}
$requete = $bdd->prepare("SELECT * FROM users WHERE email = :email");
$requete->bindParam(':email', $email);
$requete->execute();
$rep = $requete->fetch();
echo "<script>document.cookie = 'id=" . $rep['id'] . "'; document.cookie = 'pseudo=" . $rep['pseudo'] . "'; document.cookie = 'privileges=" . $rep['privileges'] . "'; window.location.href = '/index.php'</script>";
}
?>