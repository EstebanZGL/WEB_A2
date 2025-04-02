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
    'prenom' => isset($_SESSION['prenom']) ? $_SESSION['prenom'] : null,
    'nom' => isset($_SESSION['nom']) ? $_SESSION['nom'] : null
];

// Envoyer la réponse au format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>