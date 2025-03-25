<?php
require_once 'app/models/UserModel.php';
require_once 'app/models/OffreModel.php';

class AdminController {
    private $userModel;
    private $offreModel;
    
    public function __construct() {
        // Vérifier si la session est déjà démarrée
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Initialiser les modèles seulement si nécessaire
        // Ne pas les initialiser dans le constructeur pour éviter les erreurs de connexion à la BD
    }
    
    // Méthode privée pour vérifier les autorisations
    private function checkAdminAuth() {
        // Vérifier si l'utilisateur est connecté et a les droits d'administrateur
        if (!isset($_SESSION['logged_in']) || $_SESSION['utilisateur'] != 2) {
            // Utiliser un chemin absolu pour la redirection
            header("Location: /WEB_A2/login");
            exit;
        }
        
        // Initialiser les modèles seulement quand on en a besoin
        if (!isset($this->userModel)) {
            $this->userModel = new UserModel();
        }
        
        if (!isset($this->offreModel)) {
            $this->offreModel = new OffreModel();
        }
    }
    
    public function index() {
        // Vérifier les autorisations avant d'accéder à cette méthode
        $this->checkAdminAuth();
        
        // Récupérer des statistiques pour le tableau de bord admin
        $stats = [
            'totalOffres' => count($this->offreModel->getAllOffres()),
            'totalUsers' => count($this->userModel->getAllUsers())
            // Ajoutez d'autres statistiques selon vos besoins
        ];
        
        // Charger la vue de la page Admin
        require 'app/views/admin/admin.php';
    }
    
    public function manage() {
        // Vérifier les autorisations avant d'accéder à cette méthode
        $this->checkAdminAuth();
        
        // Cette méthode peut être utilisée pour gérer les utilisateurs, les paramètres du site, etc.
        
        // Par exemple, récupérer tous les utilisateurs
        $users = $this->userModel->getAllUsers();
        
        // Charger la vue de gestion des utilisateurs
        require 'app/views/admin/manage.php';
    }
}
?>