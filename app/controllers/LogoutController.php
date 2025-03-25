<?php
session_start();

class LogoutController {
    public function logout() {
        // Détruire la session
        session_destroy();

        // Rediriger vers la page de connexion avec un chemin relatif
        // pour être cohérent avec les autres redirections
        header("Location: login");
        exit();
    }
}
?>