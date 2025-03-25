<?php
require_once 'app/models/UserModel.php';
require_once 'app/models/OffreModel.php';

class AdminController {
    private $userModel;
    private $offreModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
        $this->offreModel = new OffreModel();
        
        // Vérifier si la session est déjà démarrée
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Vérifier si l'utilisateur est connecté et a les droits d'administrateur
        if (!isset($_SESSION['logged_in']) || $_SESSION['utilisateur'] != 2) {
            // Utiliser un chemin relatif simple pour la redirection
            header("Location: login");
            exit;
        }
    }
    
    public function index() {
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
        // Cette méthode peut être utilisée pour gérer les utilisateurs, les paramètres du site, etc.
        
        // Par exemple, récupérer tous les utilisateurs
        $users = $this->userModel->getAllUsers();
        
        // Charger la vue de gestion des utilisateurs
        require 'app/views/admin/manage.php';
    }
}
?>