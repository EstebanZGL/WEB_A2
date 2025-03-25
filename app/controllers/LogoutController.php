<?php
// Démarrer la session seulement si elle n'est pas déjà active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class LogoutController {
    public function logout() {
        // Détruire la session
        session_destroy();

        // Rediriger vers la page de connexion avec un chemin absolu
        header("Location: home");
        exit();
    }
}
?>