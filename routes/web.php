<?php

// Inclusion des contrôleurs nécessaires
require_once 'app/controllers/HomeController.php';
require_once 'app/controllers/LoginController.php';
require_once 'app/controllers/OffresController.php';
require_once 'app/controllers/AdminController.php';
require_once 'app/controllers/GestionController.php';
require_once 'app/controllers/LogoutController.php';
require_once 'app/controllers/WishlistController.php';

function route($uri) {
    // Initialisation des contrôleurs
    $homeController = new HomeController();
    $loginController = new LoginController();
    $offresController = new OffresController();
    $adminController = new AdminController();
    $gestionController = new GestionController();
    $logoutController = new LogoutController();
    $wishlistController = new WishlistController();

    if (strpos($uri, 'cesi-lebonplan/') === 0) {
        $uri = substr($uri, strlen('cesi-lebonplan/'));
    }

    // Pour le débogage - décommenter si nécessaire
    //echo "URI reçue par le routeur: '" . $uri . "'<br>";

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

        // Routes pour les offres
        case 'gestion/offres/add':
            // Ajouter une nouvelle offre
            $gestionController->addOffre();
            break;

        case 'gestion/offres/edit':
            // Modifier une offre existante
            $gestionController->editOffre();
            break;

        case 'gestion/offres/delete':
            // Supprimer une offre
            $gestionController->deleteOffre();
            break;

        case 'gestion/offres/stats':
            // Consulter les statistiques des offres
            $gestionController->statsOffres();
            break;

        // Routes pour les entreprises
        case 'gestion/entreprises/add':
            // Ajouter une nouvelle entreprise
            $gestionController->addEntreprise();
            break;

        case 'gestion/entreprises/edit':
            // Modifier une entreprise existante
            $gestionController->editEntreprise();
            break;

        case 'gestion/entreprises/delete':
            // Supprimer une entreprise
            $gestionController->deleteEntreprise();
            break;

        case 'gestion/entreprises/stats':
            // Consulter les statistiques des entreprises
            $gestionController->statsEntreprises();
            break;

        // Routes pour les étudiants
        case 'gestion/etudiants/add':
            // Ajouter un nouvel étudiant
            $gestionController->addEtudiant();
            break;

        case 'gestion/etudiants/edit':
            // Modifier un étudiant existant
            $gestionController->editEtudiant();
            break;

        case 'gestion/etudiants/delete':
            // Supprimer un étudiant
            $gestionController->deleteEtudiant();
            break;

        case 'gestion/etudiants/stats':
            // Consulter les statistiques des étudiants
            $gestionController->statsEtudiants();
            break;

        case 'admin':
            // Afficher la page administrateur
            $adminController->index();
            break;

        case 'admin/manage':
            // Gérer les tâches administratives (exemple)
            $adminController->manage();
            break;

        // Nouvelles routes pour la gestion des pilotes
        case 'admin/pilotes':
            // Afficher la liste des pilotes
            $adminController->pilotes();
            break;

        case 'admin/addPilote':
            // Ajouter un nouveau pilote
            $adminController->addPilote();
            break;
            
        case 'admin/editPilote':
            // Modifier un pilote existant
            $adminController->editPilote();
            break;
            
        case 'admin/deletePilote':
            // Supprimer un pilote
            $adminController->deletePilote();
            break;

        case 'admin/statsPilotes':
            // Consulter les statistiques des pilotes
            $adminController->statsPilotes();
            break;

        case 'logout':
            // Utiliser le contrôleur de déconnexion
            $logoutController->logout();
            break;

        case 'wishlist':
            // Afficher la wishlist de l'utilisateur
            $wishlistController->index();
            break;

        case 'wishlist/add':
            // Ajouter un élément à la wishlist
            $wishlistController->add();
            break;

        case 'wishlist/remove':
            // Supprimer un élément de la wishlist
            $wishlistController->remove();
            break;

        default:
        http_response_code(404);
        echo "Page non trouvée pour l'URI: '" . $uri . "'";
        echo "URI traitée: '" . $uri . "'<br>";
        break;
    }
}

// Ne pas appeler la fonction route ici car elle est déjà appelée dans index.php
// La ligne ci-dessous a été supprimée:
// route($uri);

?>
