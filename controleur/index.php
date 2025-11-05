<?php
session_start();
require_once "../modele/classes/analysisDAO.class.php";
require_once "../modele/classes/userDAO.class.php";
require_once "../modele/classes/projectDAO.class.php";
require_once "./util/scoreToNotation.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['login'])) {
    header("location: ./login.php");
}

$val = isset($_GET['val']) ? $_GET['val'] : null;   // ID

$userLName = isset($_SESSION['lastname']) ? ucfirst($_SESSION['lastname']) : 'N/A';
$userFName = isset($_SESSION['firstname']) ? ucfirst($_SESSION['firstname']) : 'N/A';
$userStatus = isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'User';
$searchBar = "";
$tableAdmin = "";
$inc = "";
$tableUser = "";
$analysisDAO = new AnalysisDAO();
$userDAO = new UserDAO();
$projectDAO = new ProjectDAO();
// Si ADMIN alors on affiche INDEX ADMIN
if ($userStatus == "Admin") {

    $inc = '<script type="text/javascript" src="./util/dataFilter.js"></script>';
    $searchBar = '<input type="search" name="filter" placeholder="Type here to filter results" class="searchBar" id="filterAdmin">';
    $tbody = '';
    $tableAdmin = '<div class="tableContainer"> <table class="tableAdmin"><thead><tr>';
    $tableAdmin .= '<th>Analysis ID</th>';
    $tableAdmin .= '<th>Username ID</th>';
    $tableAdmin .= '<th>Company</th>';
    $tableAdmin .= '<th>Username</th>';
    $tableAdmin .= '<th>Name</th>';
    $tableAdmin .= '<th>Country</th>';
    $tableAdmin .= '<th>Date</th>';
    $tableAdmin .= '<th>Score</th>';
    $tableAdmin .= '<th>View</th>';
    $tableAdmin .= '</tr></thead><tbody id="listAdmin">';
    // Parcourir l'historique et ajouter:

    if (isset($val) && strlen($val) > 0) {
        $allAnalysis = $analysisDAO->getByFilter($val, null);
    } else $allAnalysis = $analysisDAO->getAll();

    foreach ($allAnalysis as $analysis) {
        $user = $userDAO->getById($analysis->getIdUser());

        $tbody .= '<tr><td>' . $analysis->getIdAnalysis() . '</td><td>' . $analysis->getIdUser() . '</td><td>' . $projectDAO->getById($user->getIdProject()) . '</td>';
        $tbody .= '<td>' . $user . '</td>';
        $tbody .= '<td>' . $analysis->getName() . '</td><td>' . $analysis->getCountry() . '</td><td>' . $analysis->getDateAnalysis() . '</td>' . scoreToNotation($analysis->getScore(), "td");
        $tbody .= '<td><a href="./displayAnalysis.php?val=' . $analysis->getIdAnalysis() . '" class="inputImage fleche">XXX</a></td></tr>';
    }
    // 
    $tableAdmin .=  $tbody . '</tbody></table></div>';

    // To-do : Ajouter scroll bar si trop de lignes
    // Prendre en compte searchBar (besoin de la BDD)
} else {
    $tableUser = '<a class="aButton fullWidth" href="./analysis.php">Start a new risk analysis</a>';
    $tableUser .= '<table class="tableHistory"><thead><tr><th>Analysis History</th><th>Score</th></tr></thead>';
    $tableUser .= '<tbody>';
    $allAnalysis = $analysisDAO->getAllByIdUser($_SESSION['id']);
    // On ne récupère que les 5 premières analyses 
    // To-do : Order by date Analyse 
    $allAnalysis = array_slice($allAnalysis, 0, 5);
    foreach ($allAnalysis as $analysis) {
        $tableUser .= '<tr><td>' . $analysis->getName() . '</td>' . scoreToNotation($analysis->getScore(), "td") . '</tr>';
    }
    $tableUser .= '</tbody></table>';
    $tableUser .= '<a class="aButton halfWidth right" href="./historyAnalysis.php">See more</a>';
}

if (isset($val)) {
    echo $tbody;
} else require_once "../vue/index.view.php";
unset($allAnalysis);
