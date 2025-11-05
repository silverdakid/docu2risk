<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['login'])) {
    header("location: ./login.php");
}

$userLName = isset($_SESSION['lastname']) ? ucfirst($_SESSION['lastname']) : 'N/A';
$userFName = isset($_SESSION['firstname']) ? ucfirst($_SESSION['firstname']) : 'N/A';
$userStatus = isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'User';
// Si un individu a accès au fichier de stockage des sessions sur le serveur alors il a accès au mdp hashé. (S'il a accès à ça, alors avoir le mdp hashé est le cadet de nos soucis...)
$oldPass = isset($_SESSION['pass']) ? $_SESSION['pass'] : null;
$errorMsg = "";
if (isset($_POST["submitForm"])) {

    if ($_POST["oldPass"] != $oldPass) $errorMsg .= "Old password is incorrect.<br/>";
    if ($_POST["newPass1"] != $_POST["newPass2"]) $errorMsg .= "Password don't match.<br/>";
    if (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]{2})(?=.*?[#?!@$%^&*-]{2}).{12,}/", $_POST["newPass1"]))  $errorMsg .= "New password must respect the rules.";

    if(strlen($errorMsg) == 0) 
    {
        $newPass = password_hash($_POST["newPass1"], PASSWORD_DEFAULT);
        require_once('../modele/classes/userDAO.class.php');
        $userDAO = new UserDAO();
        $userDAO->updateValue($_SESSION['id'], $newPass, "password");
        $_SESSION["pass"] = $_POST["newPass1"];
    }
}

require_once "../vue/editPass.view.php";
