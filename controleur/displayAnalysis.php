<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['login'])) header("location: ./login.php");
$val = isset($_GET['val']) ? $_GET['val'] : null;   // ANALYSE ID
if (!isset($val)) header("location: ./index.php");

$downloadURL = "download.php?val=analysis-data.csv";
$userLName = isset($_SESSION['lastname']) ? ucfirst($_SESSION['lastname']) : 'N/A';
$userFName = isset($_SESSION['firstname']) ? ucfirst($_SESSION['firstname']) : 'N/A';
$userStatus = isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'User';

require_once("../modele/classes/analysisDAO.class.php");
require_once("../modele/classes/questionDAO.class.php");

$questionDAO = new QuestionDAO();
$analysisDAO = new AnalysisDAO();
$analyse = $analysisDAO->getById($val);

$companyName = $analyse->getName();
$score = $analyse->getScore();
$notation = (($score > 29 ? "HIGH" : ($score > 19 ? "MEDIUM" : "LOW")));
$country = $analyse->getCountry();
$date = $analyse->getDateAnalysis();
$questionList = $questionDAO->getAnalysisQuestions($val);
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
foreach ($questionList as $question) {
    $name = $question->getQuestion();
    $note = $question->getQuestionPoint();
    $questionListHTML .= "<tr><td>$name</td><td>$note</td></tr>";
}

$downloadURL .= "&id=" . $val;
$backButton = "Back";
$backUrl = (strtolower($_SESSION["role"]) == "admin" ? "./index.php" : (strtolower($_SESSION["role"]) == "projectleader" ? "./members.php" : "./historyAnalysis.php"));

require_once "../vue/resultAnalysis.view.php";
