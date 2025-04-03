<?php

// Inclusion des contrôleurs nécessaires
require_once 'app/controllers/HomeController.php';
require_once 'app/controllers/LoginController.php';
require_once 'app/controllers/OffresController.php';
require_once 'app/controllers/AdminController.php';
require_once 'app/controllers/GestionController.php';
require_once 'app/controllers/LogoutController.php';
require_once 'app/controllers/WishlistController.php';
require_once 'app/controllers/CandidatureController.php';
require_once 'app/controllers/DashboardController.php'; 

function route($uri) {
    $homeController = new HomeController();
    $loginController = new LoginController();
    $offresController = new OffresController();
    $adminController = new AdminController();
    $gestionController = new GestionController();
    $logoutController = new LogoutController();
    $wishlistController = new WishlistController();
    $candidatureController = new CandidatureController();
    $dashboardController = new DashboardController(); 

    if (strpos($uri, 'cesi-lebonplan/') === 0) {
        $uri = substr($uri, strlen('cesi-lebonplan/'));
    }

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
            
        case 'offres/cities':
            // Récupérer la liste des villes disponibles
            $offresController->cities();
            break;
            
        case 'offres/featured':
            // Récupérer les offres à la une
            $offresController->featured();
            break;

        case 'dashboard': // Nouvelle route pour le dashboard étudiant
            // Afficher le tableau de bord de l'étudiant
            $dashboardController->index();
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
            
        // Nouvelles routes pour les candidatures d'étudiants
        case 'gestion/etudiants/candidatures':
            // Afficher les candidatures d'un étudiant
            $gestionController->candidaturesEtudiant();
            break;
            
        case 'gestion/etudiants/candidatures/add':
            // Ajouter une candidature
            $gestionController->addCandidature();
            break;
            
        case 'gestion/etudiants/candidatures/update-status':
            // Mettre à jour le statut d'une candidature
            $gestionController->updateCandidatureStatus();
            break;
            
        case 'gestion/etudiants/candidatures/delete':
            // Supprimer une candidature
            $gestionController->deleteCandidature();
            break;
            
        // Routes pour les pilotes dans la section gestion
        case 'gestion/pilotes/add':
            // Ajouter un nouveau pilote
            $gestionController->addPilote();
            break;

        case 'gestion/pilotes/edit':
            // Modifier un pilote existant
            $gestionController->editPilote();
            break;

        case 'gestion/pilotes/delete':
            // Supprimer un pilote
            $gestionController->deletePilote();
            break;

        case 'logout':
            // Utiliser le contrôleur de déconnexion
            $logoutController->logout();
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
            // Gestion des détails des offres avec un pattern comme "offres/details/123"
            if (preg_match('/^offres\/details\/(\d+)$/', $uri, $matches)) {
                $id = $matches[1]; // Récupère l'ID de l'offre
                $offresController->details($id);
            } else {
                http_response_code(404);
                echo "Page non trouvée pour l'URI: '" . $uri . "'";
                echo "URI traitée: '" . $uri . "'<br>";
            }
            break;
    }
}