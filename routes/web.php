<?php

// Inclusion des contrôleurs nécessaires
require_once 'app/controllers/HomeController.php';
require_once 'app/controllers/LoginController.php';
require_once 'app/controllers/OffresController.php';
require_once 'app/controllers/AdminController.php';
require_once 'app/controllers/GestionController.php';
require_once 'app/controllers/LogoutController.php';
require_once 'app/controllers/WishlistController.php';
require_once 'app/controllers/DashboardController.php';

function route($uri, $action) {
    // Initialisation des contrôleurs
    $homeController = new HomeController();
    $loginController = new LoginController();
    $offresController = new OffresController();
    $adminController = new AdminController();
    $gestionController = new GestionController();
    $logoutController = new LogoutController();
    $wishlistController = new WishlistController();
    $dashboardController = new DashboardController();

    if (strpos($uri, 'cesi-lebonplan/') === 0) {
        $uri = substr($uri, strlen('cesi-lebonplan/'));
    }

    // Pour le débogage - décommenter si nécessaire
    //echo "URI reçue par le routeur: '" . $uri . "'<br>";

    // Gestion des différentes routes
    switch ($action) {
        case 'HomeController@index':
            // Afficher la page d'accueil
            $homeController->index();
            break;

        case 'LoginController@index':
            // Afficher la page de connexion
            $loginController->index();
            break;

        case 'LoginController@authenticate':
            // Traiter la connexion
            $loginController->authenticate();
            break;

        case 'OffresController@index':
            // Afficher la page des offres
            $offresController->index();
            break;

        case 'OffresController@search':
            // Traiter la recherche et les filtres pour les offres
            $offresController->search();
            break;

        case 'GestionController@index':
            // Afficher la page de gestion
            $gestionController->index();
            break;

        // Routes pour les offres
        case 'GestionController@addOffre':
            // Ajouter une nouvelle offre
            $gestionController->addOffre();
            break;

        case 'GestionController@editOffre':
            // Modifier une offre existante
            $gestionController->editOffre();
            break;

        case 'GestionController@deleteOffre':
            // Supprimer une offre
            $gestionController->deleteOffre();
            break;

        case 'GestionController@statsOffres':
            // Consulter les statistiques des offres
            $gestionController->statsOffres();
            break;

        // Routes pour les entreprises
        case 'GestionController@addEntreprise':
            // Ajouter une nouvelle entreprise
            $gestionController->addEntreprise();
            break;

        case 'GestionController@editEntreprise':
            // Modifier une entreprise existante
            $gestionController->editEntreprise();
            break;

        case 'GestionController@deleteEntreprise':
            // Supprimer une entreprise
            $gestionController->deleteEntreprise();
            break;

        case 'GestionController@statsEntreprises':
            // Consulter les statistiques des entreprises
            $gestionController->statsEntreprises();
            break;

        // Routes pour les étudiants
        case 'GestionController@addEtudiant':
            // Ajouter un nouvel étudiant
            $gestionController->addEtudiant();
            break;

        case 'GestionController@editEtudiant':
            // Modifier un étudiant existant
            $gestionController->editEtudiant();
            break;

        case 'GestionController@deleteEtudiant':
            // Supprimer un étudiant
            $gestionController->deleteEtudiant();
            break;

        case 'GestionController@statsEtudiants':
            // Consulter les statistiques des étudiants
            $gestionController->statsEtudiants();
            break;
            
        // Routes pour les pilotes dans la section gestion
        case 'GestionController@addPilote':
            // Ajouter un nouveau pilote
            $gestionController->addPilote();
            break;

        case 'GestionController@editPilote':
            // Modifier un pilote existant
            $gestionController->editPilote();
            break;

        case 'GestionController@deletePilote':
            // Supprimer un pilote
            $gestionController->deletePilote();
            break;

        case 'GestionController@statsPilotes':
            // Consulter les statistiques des pilotes
            $gestionController->statsPilotes();
            break;

        case 'AdminController@index':
            // Afficher la page administrateur
            $adminController->index();
            break;

        case 'AdminController@manage':
            // Gérer les tâches administratives (exemple)
            $adminController->manage();
            break;

        // Nouvelles routes pour la gestion des pilotes
        case 'AdminController@pilotes':
            // Afficher la liste des pilotes
            $adminController->pilotes();
            break;

        case 'AdminController@addPilote':
            // Ajouter un nouveau pilote
            $adminController->addPilote();
            break;
            
        case 'AdminController@editPilote':
            // Modifier un pilote existant
            $adminController->editPilote();
            break;
            
        case 'AdminController@deletePilote':
            // Supprimer un pilote
            $adminController->deletePilote();
            break;

        case 'AdminController@statsPilotes':
            // Consulter les statistiques des pilotes
            $adminController->statsPilotes();
            break;

        case 'LogoutController@logout':
            // Utiliser le contrôleur de déconnexion
            $logoutController->logout();
            break;

        case 'WishlistController@index':
            // Afficher la wishlist de l'utilisateur
            $wishlistController->index();
            break;

        case 'WishlistController@add':
            // Ajouter un élément à la wishlist
            $wishlistController->add();
            break;

        case 'WishlistController@remove':
            // Supprimer un élément de la wishlist
            $wishlistController->remove();
            break;

        case 'DashboardController@index':
            // Afficher le dashboard
            $dashboardController->index();
            break;

        default:
            http_response_code(404);
            echo "Page non trouvée pour l'URI: '" . $uri . "'";
            echo "URI traitée: '" . $uri . "'<br>";
            break;
    }
}

// Routes existantes
route('/', 'HomeController@index');
route('/login', 'LoginController@index');
route('/logout', 'LogoutController@logout');
route('/offres', 'OffresController@index');
route('/wishlist', 'WishlistController@index');

// Nouvelle route pour le dashboard
route('/dashboard', 'DashboardController@index');

// Routes admin
route('/admin', 'AdminController@index');
route('/admin/pilotes', 'AdminController@pilotes');

// Routes gestion
route('/gestion', 'GestionController@index');
