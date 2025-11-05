<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['login'])) {
    header("location: ./login.php");
}

require_once "./util/apiPayload.php";

unset($_SESSION['endpoint']);
$userLName = isset($_SESSION['lastname']) ? ucfirst($_SESSION['lastname']) : 'N/A';
$userFName = isset($_SESSION['firstname']) ? ucfirst($_SESSION['firstname']) : 'N/A';
$userStatus = isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'User';

$filesInput = isset($_FILES['docInput']) ? $_FILES['docInput'] : null;
$arrayChecked = isset($_POST["checkbox"]) ? $_POST["checkbox"] : null;
$companyName = isset($_POST["companyName"]) ? $_POST["companyName"] : null;
$arrayFilesName = isset($_FILES["docInput"]["name"]) ? $_FILES["docInput"]["name"] : null;

$errors = 0;
$errorMsg = "";

// On récupère l'envoie à l'analyse :
if (isset($_POST['submitAnalysis'])) {
    // Gestion des erreurs
    // - Aucun fichier dans l'input :
    if (!isset($filesInput) || !isset($arrayFilesName))
        $errorMsg .= "No files detected.<br/>";
    // - Aucun fichier cochés :
    if (!isset($arrayChecked))
        $errorMsg .= "No files checked.<br/>";

    if (!isset($companyName))
        $errorMsg .= "No company name specified.<br/>";
    // - On vérifie l'extension des fichiers (à préciser en réunion)
    // Ou non : On envoie tous les fichiers envoyés et l'algo s'occupe de voir ce qu'il peut en faire.
    if (strlen($errorMsg) == 0) {
        require_once "./util/errorHandling.php";

        $arrayCheckedName = array_keys($arrayChecked);
        $i = 1; // On commence i à 1 car l'élément 0 sera tout le temps vide dû au système d'input modifé
        $countFiles = count($arrayFilesName);
        $intersectArr = array_intersect($arrayFilesName, $arrayCheckedName);

        // On enregistre les fichiers présents dans l'intersection des deux tableaux :
        foreach ($intersectArr as $fileSelected) {
            $filename = basename($fileSelected);
            $pathInfo = pathinfo($filename);
            $filenamestripped = $pathInfo['filename'];
            $filenamestripped = str_replace(" ", "_", $filenamestripped); // Pour pouvoir utiliser notre array type dans $_POST.

            $fullPath = 'C:/API/uploads/' .  $_POST["type$filenamestripped"] . strtoupper($_SESSION["login"]) . ".pdf";

            // Enregistrement des fichiers dans le répertoire de l'API :
            if (file_exists($fullPath)) unlink($fullPath);

            if (move_uploaded_file($_FILES['docInput']['tmp_name'][$i], $fullPath)) {
                chmod($fullPath, 755); // Mise à 0 des permissions du fichier uploadé
            } else {
                $indexErrorUpload = $_FILES["docInput"]["error"][$i];
                $errorMsg .= "ERROR : " . $phpFileUploadErrors[$indexErrorUpload] . "</br>";
            }

            $i++;
        }

        var_dump($_FILES);

        $_SESSION["tmpCName"] = $companyName;
        if (strlen($errorMsg) == 0) {
            $jsonToSend = json_encode(["name" => $companyName, "user_id" => strtoupper($_SESSION["login"])]);
            sendPayLoad($jsonToSend, "get_answers_docs", "endpoint-1");
            header("location: ./confirmAnalysis.php");
        }
    }
}
require_once "../vue/analysis.view.php";
