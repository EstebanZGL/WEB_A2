<?php

// Inclusion des contrôleurs nécessaires
require_once 'app/controllers/HomeController.php';
require_once 'app/controllers/LoginController.php';
require_once 'app/controllers/OffresController.php';
require_once 'app/controllers/AdminController.php';
require_once 'app/controllers/GestionController.php';
require_once 'app/controllers/LogoutController.php';

function route($uri) {
    // Initialisation des contrôleurs
    $homeController = new HomeController();
    $loginController = new LoginController();
    $offresController = new OffresController();
    $adminController = new AdminController();
    $gestionController = new GestionController();
    $logoutController = new LogoutController();

    // Pour le débogage - décommenter si nécessaire
    // echo "URI reçue par le routeur: '" . $uri . "'<br>";

    // Gestion des différentes routes
    switch ($uri) {
        case '':
            // Afficher la page d'accueil
            $homeController->index();
            break;

        case 'home':
            // Afficher la page d'accueil
            $homeController->index();
            break;

        case 'login':
            // Afficher la page de connexion
            $loginController->index();
            break;

        case 'login/authenticate':
            // Traiter la connexion
            $loginController->authenticate();
            break;

        case 'offres':
            // Afficher la page des offres
            $offresController->index();
            break;

        case 'offres/search':
            // Traiter la recherche et les filtres pour les offres
            $offresController->search();
            break;

        case 'gestion':
            // Afficher la page de gestion
            $gestionController->index();
            break;

        case 'gestion/add':
            // Ajouter une nouvelle offre ou tâche dans la section gestion
            $gestionController->add();
            break;

        case 'gestion/edit':
            // Modifier une offre ou tâche existante
            $gestionController->edit();
            break;

        case 'gestion/delete':
            // Supprimer une offre ou tâche
            $gestionController->delete();
            break;

        case 'admin':
            // Afficher la page administrateur
            $adminController->index();
            break;

        case 'admin/manage':
            // Gérer les tâches administratives (exemple)
            $adminController->manage();
            break;

        case 'logout':
            // Utiliser le contrôleur de déconnexion
            $logoutController->logout();
            break;

        default:
            // Page non trouvée
            echo "URI traitée: '" . $uri . "'<br>";
            /*
            http_response_code(404);
            echo "Page non trouvée pour l'URI: '" . $uri . "'";
            */
            break;
    }
}

// Ne pas appeler la fonction route ici car elle est déjà appelée dans index.php
// La ligne ci-dessous a été supprimée:
// route($uri);

?>