<?php
// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Charger les fichiers nécessaires
require_once 'config/database.php';
require_once 'routes/web.php';

// Démarrage de l'application
$uri = $_SERVER['REQUEST_URI'];
//echo "URI brute: " . $uri . "<br>";

// Supprimer le slash initial et tout ce qui précède le chemin réel
$uri = ltrim($uri, '/');

// Si nous sommes sur le domaine principal, l'URI est déjà correcte
// Si nous sommes dans un sous-répertoire, nous devons extraire le chemin relatif
$scriptName = $_SERVER['SCRIPT_NAME'];
$scriptDir = dirname($scriptName);
$scriptDir = ($scriptDir == '/') ? '' : $scriptDir;

if (!empty($scriptDir) && strpos($uri, ltrim($scriptDir, '/')) === 0) {
    $uri = substr($uri, strlen(ltrim($scriptDir, '/')));
}



// Traitement de l'URI pour gérer les paramètres GET
if (strpos($uri, '?') !== false) {
    $uri = substr($uri, 0, strpos($uri, '?'));
}

// Supprimer les slashes au début et à la fin
$uri = trim($uri, '/');


// Appel du routeur avec l'URI traitée
route($uri);
?>