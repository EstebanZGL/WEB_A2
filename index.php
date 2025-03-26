<?php
// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Charger les fichiers nécessaires
require_once 'config/database.php';
require_once 'routes/web.php';

// Démarrage de l'application
$uri = trim($_SERVER['REQUEST_URI'], '/');

// Afficher l'URI pour le débogage
echo "URI originale: " . $uri . "<br>";

// Traitement de l'URI pour gérer les paramètres GET
if (strpos($uri, '?') !== false) {
    $uri = substr($uri, 0, strpos($uri, '?'));
}

echo "URI traitée: " . $uri . "<br>";

// Appel du routeur avec l'URI traitée
//route($uri);
?>