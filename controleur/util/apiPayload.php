<?php
function sendPayload($jsonSent, $endpointName, $responseFileName)
{
    // Envoie de la requête POST :    
    $url = 'http://127.0.0.1:5000/' . $endpointName;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonSent);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    // Enregistrement de la réponse :
    file_put_contents("../data/template/$responseFileName.json", $result);
}

?>