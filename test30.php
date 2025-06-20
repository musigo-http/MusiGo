<form method="post">
    <input type="text" name="password" id="password">
    <button type="submit">submit</button>
</form>

<?php
if(isset($_POST["password"])){
    echo hash("sha256", $_POST["password"]);
}
?>