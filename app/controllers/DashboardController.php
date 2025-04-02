<?php

class DashboardController {
    private $db;
    private $candidatureModel;
    private $wishlistModel;
    
    public function __construct() {
        require_once 'config/database.php';
        require_once 'app/models/CandidatureModel.php';
        require_once 'app/models/WishlistModel.php';
        
        $this->db = getDbConnection();
        $this->candidatureModel = new CandidatureModel();
        $this->wishlistModel = new WishlistModel();
    }
    
    /**
     * Vérifie si l'utilisateur est connecté en tant qu'étudiant
     */
    private function checkEtudiantAuth() {
        // Vérifier si la session est déjà démarrée
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['utilisateur']) || $_SESSION['utilisateur'] != 0) {
            header('Location: login');
            exit;
        }
    }
    
    /**
     * Affiche le tableau de bord de l'étudiant
     */
    public function index() {
        $this->checkEtudiantAuth();
        
        $userId = $_SESSION['user_id'];
        
        // Récupérer l'ID de l'étudiant
        $etudiantId = $this->candidatureModel->getEtudiantIdByUserId($userId);
        
        if (!$etudiantId) {
            // Rediriger vers la page de connexion si l'ID étudiant n'est pas trouvé
            header('Location: login');
            exit;
        }
        
        // Récupérer les candidatures de l'étudiant
        $candidatures = $this->candidatureModel->getCandidaturesByEtudiantId($etudiantId);
        
        // Récupérer la wishlist de l'étudiant
        $wishlist = $this->candidatureModel->getWishlistByEtudiantId($etudiantId);
        
        // Calculer les statistiques des candidatures
        $stats = [
            'total' => count($candidatures),
            'en_attente' => 0,
            'acceptees' => 0,
            'refusees' => 0
        ];
        
        foreach ($candidatures as $candidature) {
            switch (strtoupper($candidature['statut'])) {
                case 'EN_ATTENTE':
                    $stats['en_attente']++;
                    break;
                case 'ACCEPTEE':
                    $stats['acceptees']++;
                    break;
                case 'REFUSEE':
                    $stats['refusees']++;
                    break;
            }
        }
        
        // Charger la vue du dashboard
        require_once 'app/views/dashboard/dashboard.php';
    }
}