<?php

class WishlistController {
    private $wishlistModel;
    
    public function __construct() {
        // Démarrer la session si ce n'est pas déjà fait
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        require_once 'app/models/WishlistModel.php';
        $this->wishlistModel = new WishlistModel();
    }

    
    public function add() {
        // Vérifier si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
            return;
        }
        
        // Vérifier si l'utilisateur est un étudiant
        // CORRECTION : utiliser la variable de session 'utilisateur' au lieu de vérifier 'user_type'
        if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur'] != 0) {
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
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
            return;
        }
        
        // Vérifier si l'utilisateur est un étudiant
        if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur'] != 0) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        // Récupérer l'ID de l'offre à supprimer (accepte à la fois 'item_id' et 'offre_id')
        $offreId = null;
        if (isset($_POST['item_id'])) {
            $offreId = $_POST['item_id'];
        } elseif (isset($_POST['offre_id'])) {
            $offreId = $_POST['offre_id'];
        }
        
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
        
        // Supprimer l'offre de la wishlist
        if ($this->wishlistModel->removeFromWishlist($etudiantId, $offreId)) {
            echo json_encode(['success' => true, 'message' => 'Offre retirée de votre wishlist']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Une erreur est survenue lors de la suppression']);
        }
    }
}