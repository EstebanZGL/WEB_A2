<?php

require_once 'app/models/WishlistModel.php';

class WishlistController {
    private $wishlistModel;

    public function __construct() {
        // Démarrer la session si elle n'est pas déjà active
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->wishlistModel = new WishlistModel();
    }

    // Vérifier si l'utilisateur est connecté
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
    }

    // Afficher la wishlist de l'utilisateur
    public function index() {
        $this->checkAuth();
        
        $userId = $_SESSION['user_id'];
        $wishlistItems = $this->wishlistModel->getWishlistItems($userId);
        
        require_once 'app/views/wishlist/wishlist.php';
    }

    // Ajouter un élément à la wishlist
    public function add() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
            $userId = $_SESSION['user_id'];
            $itemId = $_POST['item_id'];
            
            $success = $this->wishlistModel->addToWishlist($userId, $itemId);
            
            if ($success) {
                // Rediriger avec un message de succès
                header('Location: /wishlist?status=added');
            } else {
                // Rediriger avec un message d'erreur
                header('Location: /wishlist?status=error');
            }
        } else {
            // Redirection si la méthode n'est pas POST ou si item_id n'est pas défini
            header('Location: /offres');
        }
        exit();
    }

    // Supprimer un élément de la wishlist
    public function remove() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
            $userId = $_SESSION['user_id'];
            $itemId = $_POST['item_id'];
            
            $success = $this->wishlistModel->removeFromWishlist($userId, $itemId);
            
            if ($success) {
                // Rediriger avec un message de succès
                header('Location: /wishlist?status=removed');
            } else {
                // Rediriger avec un message d'erreur
                header('Location: /wishlist?status=error');
            }
        } else {
            // Redirection si la méthode n'est pas POST ou si item_id n'est pas défini
            header('Location: /wishlist');
        }
        exit();
    }
}
?>