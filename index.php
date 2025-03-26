<?php
// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Charger les fichiers nécessaires
require_once 'config/database.php';
require_once 'routes/web.php';

// Démarrage de l'application
$uri = $_SERVER['REQUEST_URI'];
echo "URI brute: " . $uri . "<br>";

// Supprimer le slash initial
$uri = ltrim($uri, '/');
echo "URI après ltrim: " . $uri . "<br>";

// Traitement de l'URI pour gérer les paramètres GET
if (strpos($uri, '?') !== false) {
    $uri = substr($uri, 0, strpos($uri, '?'));
}
echo "URI finale: " . $uri . "<br>";

// Appel du routeur avec l'URI traitée
route($uri);
?>