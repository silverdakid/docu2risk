<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("location: ./login.php");
}

$val = isset($_GET['val']) ? $_GET['val'] : null;   // FICHIER_NAME

$filePath = "C:/API/uploads/$val";

if ($val == "analysis-data.csv") {
    // On génère le CSV de l'utilisateur pour l'analyse d'identifiant $id :
    $id = isset($_GET['id']) ? $_GET['id'] : null;   // ID ANALYSE

    // On récupère l'analyse :
    require_once("../modele/classes/analysisDAO.class.php");
    require_once("../modele/classes/questionDAO.class.php");
    $analysisDAO = new AnalysisDAO();
    $questionDAO = new QuestionDAO();
    $analyse = $analysisDAO->getById($id);
    if ($analyse->getIdUser() != $_SESSION["id"]) return "Aucun droit pour ce fichier pour cet utilisateur.";
    $questions = $questionDAO->getAnalysisQuestions($id);

    $fileName = "Analysis" . $analyse->getIdAnalysis() . "_DataCSV.csv";
    $path = "C:/API/uploads/" . $fileName;

    require_once("./util/convertCSV.php");
    if (analysisToCSV($analyse, $questions, $path) == 0) getFile($path);
} else {
    if (str_contains($filePath, $_SESSION["id"])) getFile($filePath);
    else return "Aucun droit pour ce fichier pour cet utilisateur.";
}

function getFile($filePath)
{
    if (file_exists($filePath)) {
        $fileName = basename($filePath);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
    } else {
        echo 'Le fichier n\'existe pas.';
    }
}
