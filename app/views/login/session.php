<?php
// Définir l'encodage pour PHP
// Cette ligne est importante pour s'assurer que les caractères spéciaux et accents sont correctement traités
mb_internal_encoding('UTF-8');

// Démarrer la session si ce n'est pas déjà fait
// Vérifie si une session est déjà active avant d'en démarrer une nouvelle
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Préparer les données de session pour le JavaScript
// Ce tableau associatif sera converti en JSON et envoyé au client
$response = [
    // Détermine si l'utilisateur est connecté en vérifiant la variable de session
    'logged_in' => isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true,
    
    // Récupère le type d'utilisateur (0: Étudiant, 1: Pilote, 2: Admin) s'il existe
    'utilisateur' => isset($_SESSION['utilisateur']) ? $_SESSION['utilisateur'] : null,
    
    // Récupère l'identifiant unique de l'utilisateur s'il existe
    'user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null,
    
    // Récupère l'email de l'utilisateur s'il existe
    'email' => isset($_SESSION['email']) ? $_SESSION['email'] : null,
    
    // Récupère le prénom de l'utilisateur s'il existe, utilisé pour le message de bienvenue
    'prenom' => isset($_SESSION['prenom']) ? $_SESSION['prenom'] : null
];

// Définir l'encodage UTF-8 pour la réponse
// Crucial pour que le navigateur interprète correctement les caractères spéciaux
header('Content-Type: application/json; charset=utf-8');

// Convertir le tableau en JSON et le renvoyer au client
// JSON_UNESCAPED_UNICODE permet de ne pas échapper les caractères Unicode (comme les accents)
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>