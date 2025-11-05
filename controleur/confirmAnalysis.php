<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['login'])) {
    header("location: ./login.php");
}
require_once "./util/apiPayload.php";

$userLName = isset($_SESSION['lastname']) ? ucfirst($_SESSION['lastname']) : 'N/A';
$userFName = isset($_SESSION['firstname']) ? ucfirst($_SESSION['firstname']) : 'N/A';
$userStatus = isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'User';
$dynamicHTML = "";
$fileDownloadHTML = "";

// On génère la liste des fichiers utilisées avec leur lien de téléchargement :
// P.S.: Le traitement des fichiers n'est pas optimal pour plusieurs utilisateurs.
$directory = 'C:/API/uploads';
$files = array_diff(scandir($directory), array('..', '.', 'backup'));
foreach ($files as $fileDownloadable) {
    $pathParts = pathinfo($fileDownloadable);
    $filename = strtolower($pathParts['filename']);
    if (str_contains($filename, $_SESSION["login"])) {
        if ($filename !== $_SESSION["login"]) {
            $fileDownloadHTML .= "<p>$fileDownloadable</p><a class='inputImage download' style='display: block !important;' href='download.php?val=$fileDownloadable'></a>";
        }
    }
}
// ---------


if (isset($_GET["endpoint"]) || isset($_SESSION["endpoint"])) {
    $_SESSION["endpoint"] = 2;
    $endpoint = "endpoint-3";
    $jsonAnswers = json_decode(file_get_contents("../data/template/endpoint-2.json"), true);
    $endpointName = "get_points";
    $answerPathFileName = "answers-2-edited";
    $location = "./resultAnalysis.php";
} else {
    $answerPathFileName = "answers-edited";
    $endpoint = "endpoint-2";
    $jsonAnswers = json_decode(file_get_contents("../data/template/endpoint-1.json"), true);
    $endpointName = "get_answers_scraping";
    $location = "./confirmAnalysis.php?endpoint=2";
}
// La liste des documents présents :
$docArray = (array_keys($jsonAnswers));
$i = 0; // Pour suivre le document actuel
$j = 0; // Pour suivre la question actuelle
$z = 0; // Pour suivre la question parmi toutes les questions (à des fins de sélection)
$w = 0; // Pour suivre la réponse parmi les réponses possibles à une question
// On parcourt le JSON :
foreach ($jsonAnswers as $document => $question) {
    if (gettype($question) != 'string') {
        foreach ($question as $parameters) {
            $func = "";
            // On récupère la réponse :
            $answer = $parameters["answer"]; // String
            $scraping = isset($parameters["scraping"]) ? $parameters['scraping'] : false; // Boolean
            $type = $parameters["type"]; // Radio/Dropdown/Text
            $name = $parameters["name"];
            $answers = $parameters["answers"]; // Array
            $class = "searchBar question-input"; // Classe de base en CSS

            if ($scraping === true && $answer === "N/A") {
                $class .= " expected"; // Permets au JS de savoir que cette input ne peut être nul.
                $checkable = ''; // On ôte le bouton de validation initial.
            } else if ($answer !== "N/A") {
                $class .= " ia"; // La question a été répondue par l'IA
                $checkable = ''; // On ôte le bouton de validation initial.
            } else {
                $class .= " optional";
                // Si la réponse n'est pas requise pour le scraping alors on permets la validation sans édition :
                $checkable = '<div class="editInputDiv">
                <button type="button" value="' . $z . '" onclick="confirmEdit(this, `' . $z . '`, ' . (count($answers) == 2 ? "'enableRadio'" : "'enableSelect'") . ');" class="editInput editInputCheck">
                </button>
            </div>';
            }

            $dynamicHTML .= "<label>$name</label>"; // Intitulé de la question
            // <input type="text" id="0" name="0" value="France" class="searchBar question-input expected" disabled="">
            // On différentie deux types d'inputs, text et les autres :
            if ($type === "text") {
                $func = "enableEdit(this, `$z`)";
                // On crée l'input :
                $dynamicHTML .= "<input type='text' id='$z' name='$z' value='" . ($answer !== 'N/A' ? $answer : '') . "' class='$class fullWidth' disabled/>";
            } else {
                $dynamicHTML .= '<div class="' . $class . '">';
                if ($type == "radio") {
                    $answer = strtoupper($answer);
                    $func = "enableRadio(this, `" . $z . "`)";
                    // Si les réponses sont au nombre de deux :
                    foreach (["YES", "NO"] as $answerpossible) {
                        // To-do : Si réponse = Expected -> ajouter required aux inputs radio
                        $answerpossibleSyntax = ucfirst($answerpossible);
                        $dynamicHTML .= '<label for="' . $i . "/" . $w . '">' . $answerpossibleSyntax . '</label>';
                        $dynamicHTML .= '<input type="radio" value="' . $answerpossibleSyntax . '" name="' . $z . '" id="' . $z . '" disabled ' . ($answerpossibleSyntax == $answer ? "checked" : "") . '/>';
                        $w++;
                    }
                } else {
                    $politicalRiskArray = array("VERY-LOW", "LOW", "MEDIUM", "SENSITIVE", "HIGH", "VERY-HIGH");
                    $func = "enableSelect(this, `" . $z . "`)";
                    $dynamicHTML .= '<select id="' . $z . '" name="' . $z . '" disabled>';
                    foreach ($answers as $answerdrop) {
                        if (str_contains($name, "political risk")) {
                            $answerdropoption = $politicalRiskArray[$answerdrop - 1];
                        } else $answerdropoption = $answerdrop;

                        $dynamicHTML .= '<option value=' . $answerdrop . ' ' . ($answerdrop == $answer ? 'selected="selected"' : "") . ' >' . $answerdropoption . '</option>';
                    }
                    $dynamicHTML .= '</select>';
                }
                $dynamicHTML .= '</div>';
            }

            // On ajoute les boutons d'éditions :

            $dynamicHTML .= '<div id="div' . $z . '" class="containerEditAnswer">' . $checkable . '
            <div class="editInputDiv">
                <button type="button" value="' . $z . '" onclick="' . $func . '" class="editInput editInputEdit">
                </button>
            </div>
        </div>';
            $j++;
            $z++;
        }
    }

    $i++;
    $j = 0;
}

if ($i == 0) {
    $dynamicHTML = '<h1 class="red">Error 404 : No data, please try again or contact the administrator !</h1>';
}

if (isset($_POST["submitForm"])) {
    // To-do : Vérifier que tous les champs requis ont une réponse :
    $counter = 0;

    array_walk_recursive($jsonAnswers, function (&$value, $key) use (&$counter) {
        // Si le niveau parcouru corresponds à celui d'une réponse de question :
        if ($key == "answer") {
            // On récupère la réponse de notre formulaire :
            $val = isset($_POST[$counter]) ? $_POST[$counter] : "N/A";
            // Que l'on assigne
            $value = $val;
            // Puis on incrémente le parcours
            $counter++;
        };
    }, $counter);

    $jsonEdited = json_encode($jsonAnswers);

    if (isset($_SESSION["endpoint"])) {
        $jsonPast = file_get_contents("../data/template/answers-edited.json");

        $jsonEdited = json_encode(array_merge(json_decode($jsonPast, true), json_decode($jsonEdited, true)));

        file_put_contents("../data/template/merged.json", $jsonEdited);
    }


    file_put_contents("../data/template/$answerPathFileName.json", $jsonEdited);

    sendPayLoad($jsonEdited, $endpointName, $endpoint);

    header("location: $location");
}



require_once "../vue/confirmAnalysis.view.php";
