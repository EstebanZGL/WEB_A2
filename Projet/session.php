<?php
session_start();
header('Content-Type: application/json');

$response = ['logged_in' => false];

if (isset($_SESSION['user_id'])) { // Vérifie si un ID utilisateur est stocké
    $response['logged_in'] = true;
    $response['utilisateur'] = $_SESSION['utilisateur']; //Type d'utilisateur
}

echo json_encode($response);
?>
