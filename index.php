<?php
// Point d'entrée de l'application/// Chargement des fichiers nécessaires
require_once 'config/database.php';
require_once 'routes/web.php';

// Démarrage de l'application
$uri = trim($_SERVER['REQUEST_URI'], '/');

/// Supprimer le préfixe "Project_MVC" de l'URI
$basePath = 'Project_MVC';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
    $uri = trim($uri, '/');
}

// Traitement de l'URI pour gérer les paramètres GET
if (strpos($uri, '?') !== false) {
    $uri = substr($uri, 0, strpos($uri, '?'));
}

// Décommenter pour déboguer si nécessaire
// echo "URI finale: " . $uri . "<br>";
route($uri);

?>