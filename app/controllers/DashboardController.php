<?php

class DashboardController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
    }
    
    public function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
    }
    
    public function index() {
        $this->checkAuth();
        
        $userData = [];
        $userType = $_SESSION['user_type'] ?? '';
        
        switch($userType) {
            case 'etudiant':
                $userData = $this->getStudentDashboardData();
                break;
            case 'pilote':
                $userData = $this->getPiloteDashboardData();
                break;
            case 'admin':
                $userData = $this->getAdminDashboardData();
                break;
            default:
                header('Location: /login');
                exit();
        }
        
        require 'app/views/dashboard/dashboard.php';
    }
    
    private function getStudentDashboardData() {
        return [
            'title' => 'Tableau de bord Étudiant',
            'stats' => [
                'candidatures' => 0, // À implémenter avec le vrai nombre
                'wishlist' => 0,     // À implémenter avec le vrai nombre
                'messages' => 0,      // À implémenter avec le vrai nombre
            ]
        ];
    }
    
    private function getPiloteDashboardData() {
        return [
            'title' => 'Tableau de bord Pilote',
            'stats' => [
                'etudiants' => 0,    // À implémenter avec le vrai nombre
                'entreprises' => 0,   // À implémenter avec le vrai nombre
                'offres' => 0,        // À implémenter avec le vrai nombre
            ]
        ];
    }
    
    private function getAdminDashboardData() {
        return [
            'title' => 'Tableau de bord Administrateur',
            'stats' => [
                'utilisateurs' => 0,  // À implémenter avec le vrai nombre
                'pilotes' => 0,       // À implémenter avec le vrai nombre
                'entreprises' => 0,    // À implémenter avec le vrai nombre
            ]
        ];
    }
}