<?php
// proxy-curl.php

// 1. Vérifie si le paramètre "file" est fourni
if (!isset($_GET['file'])) {
    http_response_code(400);
    exit('Paramètre "file" manquant.');
}

$url = $_GET['file'];

// 2. Filtrage de sécurité basique
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    exit('URL invalide.');
}

// 3. Initialisation de cURL
$ch = curl_init($url);

// 4. Transfert direct vers la sortie
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HEADER => true,
    CURLOPT_TIMEOUT => 20,
]);

$response = curl_exec($ch);

if ($response === false) {
    http_response_code(500);
    exit('Erreur lors du chargement du flux : ' . curl_error($ch));
}

// 5. Séparation en-têtes / corps
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $headerSize);
$body = substr($response, $headerSize);

// 6. Récupération du type MIME
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

// 7. Fermeture
curl_close($ch);

// 8. Envoi du bon Content-Type
if ($contentType) {
    header("Content-Type: $contentType");
} else {
    header("Content-Type: application/vnd.apple.mpegurl");
}

// 9. Désactivation du cache
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// 10. Affiche le corps de la réponse
echo $body;
?>