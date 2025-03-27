<?php

class WishlistController {
    private $wishlistModel;
    
    public function __construct() {
        require_once 'app/models/WishlistModel.php';
        $this->wishlistModel = new WishlistModel();
    }
    
    public function index() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: login');
            exit;
        }
        
        // Vérifier si l'utilisateur est un étudiant
        if ($_SESSION['user_type'] != 0) {
            header('Location: home');
            exit;
        }
        
        // Récupérer les éléments de la wishlist
        $wishlistItems = $this->wishlistModel->getWishlistByUserId($_SESSION['user_id']);
        
        // Inclure la vue
        include 'app/views/wishlist/wishlist.php';
    }
    
    public function add() {
        // Vérifier si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
            return;
        }
        
        // Vérifier si l'utilisateur est un étudiant
        if ($_SESSION['user_type'] != 0) {
            echo json_encode(['success' => false, 'message' => 'Seuls les étudiants peuvent utiliser cette fonctionnalité']);
            return;
        }
        
        // Récupérer l'ID de l'offre à ajouter
        $offreId = isset($_POST['item_id']) ? $_POST['item_id'] : null;
        
        if (!$offreId) {
            echo json_encode(['success' => false, 'message' => 'ID de l\'offre manquant']);
            return;
        }
        
        // Récupérer l'ID de l'étudiant
        $etudiantId = $this->wishlistModel->getEtudiantIdByUserId($_SESSION['user_id']);
        
        if (!$etudiantId) {
            echo json_encode(['success' => false, 'message' => 'Profil étudiant non trouvé']);
            return;
        }
        
        // Ajouter l'offre à la wishlist
        if ($this->wishlistModel->addToWishlist($etudiantId, $offreId)) {
            echo json_encode(['success' => true, 'message' => 'Offre ajoutée à la wishlist']);
        } else {
            echo json_encode(['success' => false, 'message' => 'L\'offre est déjà dans votre wishlist ou une erreur est survenue']);
        }
    }
    
    public function remove() {
        // Vérifier si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $_SESSION['status_message'] = 'Méthode non autorisée';
            $_SESSION['status_type'] = 'status-error';
            header('Location: /wishlist');
            exit;
        }
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['status_message'] = 'Utilisateur non connecté';
            $_SESSION['status_type'] = 'status-error';
            header('Location: /login');
            exit;
        }
        
        // Vérifier si l'utilisateur est un étudiant
        if ($_SESSION['user_type'] != 0) {
            $_SESSION['status_message'] = 'Accès non autorisé';
            $_SESSION['status_type'] = 'status-error';
            header('Location: /home');
            exit;
        }
        
        // Récupérer l'ID de l'offre à supprimer
        $offreId = isset($_POST['item_id']) ? $_POST['item_id'] : null;
        
        if (!$offreId) {
            $_SESSION['status_message'] = 'ID de l\'offre manquant';
            $_SESSION['status_type'] = 'status-error';
            header('Location: /wishlist');
            exit;
        }
        
        // Récupérer l'ID de l'étudiant
        $etudiantId = $this->wishlistModel->getEtudiantIdByUserId($_SESSION['user_id']);
        
        if (!$etudiantId) {
            $_SESSION['status_message'] = 'Profil étudiant non trouvé';
            $_SESSION['status_type'] = 'status-error';
            header('Location: /wishlist');
            exit;
        }
        
        // Supprimer l'offre de la wishlist
        if ($this->wishlistModel->removeFromWishlist($etudiantId, $offreId)) {
            $_SESSION['status_message'] = 'Offre retirée de votre wishlist';
            $_SESSION['status_type'] = 'status-success';
        } else {
            $_SESSION['status_message'] = 'Une erreur est survenue lors de la suppression';
            $_SESSION['status_type'] = 'status-error';
        }
        
        header('Location: /wishlist');
        exit;
    }
}