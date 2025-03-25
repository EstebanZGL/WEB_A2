<?php
// Pour le débogage - décommenter pour voir les détails de l'URI
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// echo "URI originale: " . $_SERVER['REQUEST_URI'] . "<br>";

// Charger les fichiers nécessaires
require_once 'config/database.php';
require_once 'routes/web.php';

// Démarrage de l'application
$uri = trim($_SERVER['REQUEST_URI'], '/');

// Traitement de l'URI pour gérer les paramètres GET
if (strpos($uri, '?') !== false) {
    $uri = substr($uri, 0, strpos($uri, '?'));
}

// Pour le débogage - décommenter pour voir l'URI traitée
echo "URI traitée: '" . $uri . "'<br>";

exit;

// Appel du routeur avec l'URI traitée
//route($uri);
?>