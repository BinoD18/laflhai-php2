<?php
header('Content-Type: application/json');
//header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // <-- Ajouté
// Fonction : tente de convertir HTTP → HTTPS si possible
/*function httpsAvailable($url) {
    if (stripos($url, 'http://') !== 0) return $url; // déjà HTTPS ou autre

    $httpsUrl = preg_replace('/^http:/i', 'https:', $url);
    $headers = @get_headers($httpsUrl);
    if ($headers && strpos($headers[0], '200') !== false) {
        return $httpsUrl;
    }
    return $url; // on garde l’original si HTTPS ne répond pas
}*/

//$lines = file('sport.m3u');
//$lines = file('afriq.m3u');
//$lines = file('sportfrworld.m3u');
//$lines = file('MAX-AFRIK.m3u');
//$lines = file('sport2026.m3u');
//$lines = file('sport123.m3u');
//$lines = file('fr.m3u');
$lines = file('AFQ.m3u');
//$lines = file('playlist.m3u');
//$lines = file('adult.m3u');
//$lines = file('iptv.m3u');
//$lines = file('bouquet.m3u');
//$lines = file('tests.m3u');
//$lines = file('africa.m3u');
//$lines = file('africavip.m3u');
$channels = [];

for ($i = 0; $i < count($lines); $i++) {
    if (strpos($lines[$i], '#EXTINF') === 0) {
        $line = $lines[$i];

        // Nom de la chaîne
        preg_match('/,(.*)/', $line, $nameMatch);
        $name = $nameMatch[1] ?? 'Inconnu';

        // Groupe (catégorie)
        preg_match('/group-title="([^"]+)"/', $line, $groupMatch);
        $group = $groupMatch[1] ?? 'Autres';

        // Logo
        preg_match('/tvg-logo="([^"]+)"/', $line, $logoMatch);
        $logo = $logoMatch[1] ?? '';

        // URL de la vidéo (ligne suivante)
        $url = trim($lines[$i + 1]);

        // Conversion HTTP → HTTPS si possible
       // $url = httpsAvailable($url);

        // Validation et construction de la liste
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            // Option A : Lecture directe
             $finalUrl = $url;

            // Option B : Passer par proxy (recommandé si tu veux cacher/reforward)
            //$finalUrl = "proxy.php?file=" . urlencode($url);

            $channels[] = [
                "name" => $name,
                "url" => $finalUrl,
                "group" => $group,
                "logo" => $logo
            ];
        }
    }
}

echo json_encode($channels, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>