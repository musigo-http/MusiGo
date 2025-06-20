<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The music does not exist on the server</title>
</head>
<body>
    <h1>⏳</h1><br>
    <h1>The music does not exist on the server, please wait while it is installed</h1>
</body>
</html>

<?php
if($_GET){
    $search = $_GET["search"];
    //file_get_contents("http://tonsite.com/index.php?search=" . urlencode($search));
    header("Location: https://musigo.duckdns.org/index.php?search=".urlencode($search));
    exit();
}
?>