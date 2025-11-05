<?php
session_start();
require_once "../modele/connexion.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$identifiants['username'] = isset($_POST['username']) ? $_POST['username'] : null;
$identifiants['pass'] = isset($_POST['pass']) ? $_POST['pass'] : null;
$message = "";
$ip = $_SERVER["REMOTE_ADDR"];

if (isset($_POST['login'])) {
    $connexion = new Connexion();
    // On vérifie si l'utilisateur existe et si le mot de passe est correct.
    $connexion->execSQL("INSERT INTO ip (address ,timestamp)VALUES (:ip,CURRENT_TIMESTAMP)", [':ip' => $ip]); // Chaque tentative de connexion est enregistrée, même si on a dépassé les 3 max 
    $attempt = $connexion->execSQL("SELECT * FROM ip WHERE address=:ip AND timestamp > (now() - interval 10 minute)", [":ip" => $ip]);
    $numberAttempt = count($attempt);

    if ($numberAttempt > 10) {
        $message = "You exceeded the maximum of attempts available. Wait 10 minutes then try again.";
    } else {
        if ($connexion->existeUtilisateur($identifiants) && $connexion->verifMdp($identifiants)) {
            $log = $_SESSION['login'] . " connected (" . date('d/m/Y : H:i') . ")\n";
            file_put_contents("../data/.log", $log, FILE_APPEND);
            header("location: ./index.php"); // On renvoie à l'index
        } else {
            $log = "Connection attempt (" . date('d/m/Y : H:i') . ")\n";
            file_put_contents("../data/.log", $log, FILE_APPEND);
            $identifiants = ['username' => null, 'pass' => null]; // En cas d'erreur, on clear tous les champs (possibilité de modifier à un seul champ ou aucun)
            $message = "Incorrect login, try again. You have " . (10 - $numberAttempt) . " attempt" . (10 - $numberAttempt > 1 ? "s" : "") . " left.";
        }
    }
}

require_once "../vue/login.view.php";
