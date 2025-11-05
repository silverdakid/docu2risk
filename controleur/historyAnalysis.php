<?php
session_start();
require_once "./util/scoreToNotation.php";
require_once "../modele/classes/analysisDAO.class.php";
require_once "../modele/classes/userDAO.class.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['login'])) {
    header("location: ./login.php");
}

$val = isset($_GET['val']) ? $_GET['val'] : null;   // ID
$idToView = isset($_SESSION['tmpIdView']) ? $_SESSION['tmpIdView'] : $_SESSION['id'];
$title = '';
$userLName = isset($_SESSION['lastname']) ? ucfirst($_SESSION['lastname']) : 'N/A';
$userFName = isset($_SESSION['firstname']) ? ucfirst($_SESSION['firstname']) : 'N/A';
$userStatus = isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'User';
$analysisDAO = new AnalysisDAO();
$userDAO = new UserDAO();

$allAnalysis;
if (isset($val) && strlen($val) > 0) {
    $allAnalysis = $analysisDAO->getByFilter($val, $idToView);
} else $allAnalysis = $analysisDAO->getAllByIdUser($idToView);

$tbody = '';
$plHtml = '';
$retour = '';
// Un project leader a besoin de savoir ces champs :
if ($userStatus == 'Projectleader') $plHtml = '<th>Analysis ID</th><th>Username ID</th><th>Username</th>';

foreach ($allAnalysis as $analysis) {
    if (isset($analysis)) {
        if ($userStatus == 'Projectleader') {
            $userID = $analysis->getIdUser();
            $tbody .= '<tr><td>' . $analysis->getIdAnalysis() . '</td>';
            $tbody .= '<td>' . $userID . '</td>';
            $tbody .= '<td>' . $userDAO->getById($userID) . '</td>';
        }
        $tbody .= '<td>' . $analysis->getName() . '</td>';
        $tbody .= '<td>' . $analysis->getCountry() . '</td>';
        $tbody .= '<td>' . $analysis->getDateAnalysis() . '</td>';
        $tbody .=  scoreToNotation($analysis->getScore(), "td");
        $tbody .= '<td><a href="./displayAnalysis.php?val=' . $analysis->getIdAnalysis() . '"  name="view" class="inputImage fleche">XXXX</a></td></tr>';
    }
}

if ($idToView != $_SESSION['id']) {
    $title = '<p class="title headPos">History of ' . $userDAO->getById($idToView) . '</p>';
    $retour = '<a class="aButton settingsButton" style="justify-self: left !important;" href="./members.php">Back</a>';
} else $retour = '<a class="aButton settingsButton" style="justify-self: left !important;" href="./index.php">Home</a>';
// Astuce permettant d'éviter la duplication de code, on réutilise ce fichier.

// if(isset($_POST['retour'])) {
//     unset($_SESSION['tmpIdView']);
//     header('location: ./members.php');
// }

if (isset($val)) {
    echo $tbody;
} else require_once "../vue/historyAnalysis.view.php";
unset($allAnalysis);
