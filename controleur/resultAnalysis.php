<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['login'])) {
    header("location: ./login.php");
}

$jsonScore = json_decode(file_get_contents("../data/template/endpoint-3.json"), true);
$downloadURL = "download.php?val=analysis-data.csv";
$userLName = isset($_SESSION['lastname']) ? ucfirst($_SESSION['lastname']) : 'N/A';
$userFName = isset($_SESSION['firstname']) ? ucfirst($_SESSION['firstname']) : 'N/A';
$userStatus = isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'User';
$companyName = isset($_SESSION["tmpCName"]) ? $_SESSION["tmpCName"] : null;

if (!isset($companyName)) header("location: ./index.php");

$score = $jsonScore['score'];
$notation = $jsonScore["notation"];
$country = $jsonScore["infos"]["Country"];
$date = $jsonScore["infos"]["Date"];
$questionList = $jsonScore["points_details"];
$questionListHTML = "";
$colorHex = "24FF00";
if ($score > 29) {
    $color = "red";
    $colorHex = "ff0000";
} else if ($score > 19) {
    $color = "orange";
    $colorHex = "ffbb00";
} else {
    $color = "green";
}

foreach ($questionList as $document => $question) {
    $i = 0;
    foreach ($question as $note) {
        $note = (isset($note) ? $note : 0);
        $name = array_keys($question)[$i];
        $questionListHTML .= "<tr><td>$name</td>";
        $questionListHTML .= "<td>$note</td></tr>";
        $i++;
    }
}

$backButton = "Home";
$backUrl = "./index.php";
// Nous l'ajoutons désormais à l'historique :

require_once "../modele/classes/analysisDAO.class.php";
require_once "../modele/classes/analysis.class.php";
$analysisDAO = new AnalysisDAO();

// Créer une Analysis, avec ces données :
$dateNow = date('Y-m-d H:i:s');
$newAnalysis = new Analysis(
    0,
    $_SESSION["id"],
    $_SESSION['tmpCName'],
    $jsonScore['infos']['Country'],
    $dateNow,
    "N/A",
    // $jsonScore['infos']['Date'],
    // date('Y-m-d H:i:s'),
    "",
    "Banking",
    0
);

// Faire le tableau des questions réponses :

$tmpArrayName = array();
$tmpArrayScore = array();
// Boucler parmi les questions-réponses :
foreach ($jsonScore['points_details'] as $document => $question) {
    foreach (array_keys($question) as $answer) {
        $tmpArrayName[] = $answer;
    }

    foreach (array_values($question) as $answer) {
        // Pour prendre en charge l'écart-type on peut se baser ici sur les NULL
        // Ou gérer celui depuis l'API
        $tmpArrayScore[] = isset($answer) ? $answer : 0;
    }
}

// On renvoie au DAO qui s'occupera de l'insertion de ces données :
$idAnalysis = $analysisDAO->insert($newAnalysis, $tmpArrayName, $tmpArrayScore);

$downloadURL .= "&id=" . $idAnalysis;
require_once "../vue/resultAnalysis.view.php";
