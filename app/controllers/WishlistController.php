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
    
    public function index() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            // Si la requête est AJAX, renvoyer une réponse JSON
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
                return;
            } else {
                header('Location: login');
                exit;
            }
        }
        
        // Vérifier si l'utilisateur est un étudiant (type 0)
        // CORRECTION : utiliser la variable de session 'utilisateur' au lieu de vérifier 'user_type'
        if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur'] != 0) {
            // Si la requête est AJAX, renvoyer une réponse JSON
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
                return;
            } else {
                header('Location: home');
                exit;
            }
        }
        
        // Récupérer les éléments de la wishlist
        $wishlistItems = $this->wishlistModel->getWishlistByUserId($_SESSION['user_id']);
        
        // Si la requête est AJAX, renvoyer les données au format JSON
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode($wishlistItems);
            return;
        }
        
        // Sinon, inclure la vue
        include 'app/views/wishlist/wishlist.php';
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
            // Si la requête est AJAX, renvoyer une réponse JSON
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
                return;
            } else {
                $_SESSION['status_message'] = 'Méthode non autorisée';
                $_SESSION['status_type'] = 'status-error';
                header('Location: /wishlist');
                exit;
            }
        }
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
                return;
            } else {
                $_SESSION['status_message'] = 'Utilisateur non connecté';
                $_SESSION['status_type'] = 'status-error';
                header('Location: /login');
                exit;
            }
        }
        
        // Vérifier si l'utilisateur est un étudiant
        if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur'] != 0) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
                return;
            } else {
                $_SESSION['status_message'] = 'Accès non autorisé';
                $_SESSION['status_type'] = 'status-error';
                header('Location: /home');
                exit;
            }
        }
        
        // Récupérer l'ID de l'offre à supprimer (accepter à la fois item_id et offre_id)
        $offreId = isset($_POST['item_id']) ? $_POST['item_id'] : (isset($_POST['offre_id']) ? $_POST['offre_id'] : null);
        
        if (!$offreId) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['success' => false, 'message' => 'ID de l\'offre manquant']);
                return;
            } else {
                $_SESSION['status_message'] = 'ID de l\'offre manquant';
                $_SESSION['status_type'] = 'status-error';
                header('Location: /wishlist');
                exit;
            }
        }
        
        // Récupérer l'ID de l'étudiant
        $etudiantId = $this->wishlistModel->getEtudiantIdByUserId($_SESSION['user_id']);
        
        if (!$etudiantId) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['success' => false, 'message' => 'Profil étudiant non trouvé']);
                return;
            } else {
                $_SESSION['status_message'] = 'Profil étudiant non trouvé';
                $_SESSION['status_type'] = 'status-error';
                header('Location: /wishlist');
                exit;
            }
        }
        
        // Supprimer l'offre de la wishlist
        $result = $this->wishlistModel->removeFromWishlist($etudiantId, $offreId);
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Offre retirée de votre wishlist']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Une erreur est survenue lors de la suppression']);
            }
            return;
        } else {
            if ($result) {
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
}