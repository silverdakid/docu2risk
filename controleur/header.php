<?php
session_start();
require_once "../modele/connexion.php";

if (isset($_POST['disconnect'])) {
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]);
    }
    $log = "Disconnection ".$_SESSION['login']." (".date('d/m/Y : H:i').")\n";  ////////// Log
    file_put_contents("../data/.log",$log, FILE_APPEND);
    session_unset();
    session_destroy();
    header("location: ./login.php");
} else if(isset($_POST['settings'])) {
    header("location: ./settings.php");
}


?>