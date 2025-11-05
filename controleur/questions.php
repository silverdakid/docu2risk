<?php
session_start();
require_once "../modele/classes/questionDAO.class.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['login'])) {
    header("location: ./login.php");
}

$userLName = isset($_SESSION['lastname']) ? ucfirst($_SESSION['lastname']) : 'N/A';
$userFName = isset($_SESSION['firstname']) ? ucfirst($_SESSION['firstname']) : 'N/A';
$userStatus = isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'User';

$tbody = "";
$questionDAO = new QuestionDAO();
$allQuestion = $questionDAO->getAll();

foreach ($allQuestion as $question) {
    $tbody .= '<tr><td>'.$question->getQuestion().'</td><td>'.$question->getQuestionPoint().'</td></tr>';
}

require_once "../vue/questions.view.php";


?>