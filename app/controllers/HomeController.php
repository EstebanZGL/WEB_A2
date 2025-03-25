<?php

class HomeController {
    public function index() {
        // Charger la vue de la page d'accueil
        require 'app/views/home/home.php';
    }
}
?>