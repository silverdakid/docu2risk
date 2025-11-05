<?php
require_once dirname(__DIR__, 2) . "/modele/classes/analysis.class.php";
require_once dirname(__DIR__, 2) . "/modele/classes/question.class.php";
/**
 * analysisToCSV
 * Convertis une analyse et ses questions/réponses en fichier CSV.
 * @param Analysis $analysis
 * @param Question[] $questions
 * @return int
 */
function analysisToCSV(Analysis $analysis, array $questions, string $path)
{
    // On crée le fichier .CSV et on l'ouvre en écriture :
    $csvFile = fopen($path, 'w');

    if (!$csvFile) {
        // die('Impossible to open the CSV File in Writing Mode.');
        return -1;
    }

    // Courte ligne introduisant les données :
    $introStr = "Analysis (#" . $analysis->getIdAnalysis() . ") of " . $analysis->getName() . " " . $analysis->getCountry() . " the " . $analysis->getDateAnalysis();

    fputcsv(
        $csvFile,
        [$introStr],
        ";"
    );

    // Vif du sujet :
    fputcsv($csvFile, ['Question', 'Points'], ";");
    foreach ($questions as $question) {
        fputcsv($csvFile, [$question->getQuestion(), $question->getQuestionPoint()], ";");
    }

    // Conclusion du CSV :
    fputcsv($csvFile, ["Final Score", $analysis->getScore()], ";");

    fclose($csvFile);

    return 0;
}
