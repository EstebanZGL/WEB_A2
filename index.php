<?php

// Charger les fichiers nécessaires
require_once 'config/database.php';
require_once 'routes/web.php';

// Démarrage de l'application
$uri = trim($_SERVER['REQUEST_URI'], '/');

// Supprimer le préfixe "cesi-lebonplan" de l'URI
$basePath = 'cesi-lebonplan';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
    $uri = trim($uri, '/');
}

// Traitement de l'URI pour gérer les paramètres GET
if (strpos($uri, '?') !== false) {
    $uri = substr($uri, 0, strpos($uri, '?'));
}

// Appel du routeur avec l'URI traitée
route($uri);
?>