<?php
function route() {
    $uri = $_SERVER['REQUEST_URI'];
    
    // Supprimer les paramètres de requête
    $uri = explode('?', $uri)[0];
    
    // Supprimer les barres obliques de début et de fin
    $uri = trim($uri, '/');
    
    // Si l'URI est vide, rediriger vers la page d'accueil
    if (empty($uri)) {
        $uri = 'home';
    }
    
    // Routes pour les offres
    if ($uri === 'offres') {
        require_once 'app/controllers/OffresController.php';
        $controller = new OffresController();
        $controller->index();
        return;
    }
    
    if ($uri === 'offres/search') {
        require_once 'app/controllers/OffresController.php';
        $controller = new OffresController();
        $controller->search();
        return;
    }
    
    if (preg_match('/^offres\/details\/(\d+)$/', $uri, $matches)) {
        require_once 'app/controllers/OffresController.php';
        $controller = new OffresController();
        $controller->details($matches[1]);
        return;
    }
    
    // Routes pour la wishlist
    if ($uri === 'wishlist') {
        require_once 'app/controllers/WishlistController.php';
        $controller = new WishlistController();
        $controller->index();
        return;
    }
    
    if ($uri === 'wishlist/add') {
        require_once 'app/controllers/WishlistController.php';
        $controller = new WishlistController();
        $controller->add();
        return;
    }
    
    if ($uri === 'wishlist/remove') {
        require_once 'app/controllers/WishlistController.php';
        $controller = new WishlistController();
        $controller->remove();
        return;
    }
    
    // Autres routes existantes...
    
    // Route par défaut pour la page d'accueil
    if ($uri === 'home') {
        require_once 'app/controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        return;
    }
    
    // Route pour la page de connexion
    if ($uri === 'login') {
        require_once 'app/controllers/LoginController.php';
        $controller = new LoginController();
        $controller->index();
        return;
    }
    
    // Route pour l'authentification
    if ($uri === 'login/authenticate') {
        require_once 'app/controllers/LoginController.php';
        $controller = new LoginController();
        $controller->authenticate();
        return;
    }
    
    // Route pour la déconnexion
    if ($uri === 'logout') {
        require_once 'app/controllers/LogoutController.php';
        $controller = new LogoutController();
        $controller->logout();
        return;
    }
    
    // Route pour la page de gestion
    if ($uri === 'gestion') {
        require_once 'app/controllers/GestionController.php';
        $controller = new GestionController();
        $controller->index();
        return;
    }
    
    // Route pour la page d'administration
    if ($uri === 'admin') {
        require_once 'app/controllers/AdminController.php';
        $controller = new AdminController();
        $controller->index();
        return;
    }

    // Si aucune route ne correspond, afficher une page 404
    header('HTTP/1.0 404 Not Found');
    echo '404 - Page non trouvée';
}
?>