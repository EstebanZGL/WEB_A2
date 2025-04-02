<?php
// Démarrer la session si ce n'est pas déjà fait
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Préparer les données de session pour le JavaScript
$response = [
    'logged_in' => isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true,
    'utilisateur' => isset($_SESSION['utilisateur']) ? $_SESSION['utilisateur'] : null,
    'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null,
    'email' => isset($_SESSION['email']) ? $_SESSION['email'] : null,
    'prenom' => isset($_SESSION['prenom']) ? htmlspecialchars_decode($_SESSION['prenom'], ENT_QUOTES) : null
];

// Définir l'encodage UTF-8 pour la réponse
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>