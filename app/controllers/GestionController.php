<?php
require_once 'app/models/OffreModel.php';

class GestionController {
    private $offreModel;
    
    public function __construct() {
        // Vérifier si la session est déjà démarrée
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Ne pas initialiser le modèle ou vérifier les autorisations dans le constructeur
    }
    
    // Méthode privée pour vérifier les autorisations
    private function checkGestionAuth() {
        // Vérifier si l'utilisateur est connecté et a les droits de gestion
        if (!isset($_SESSION['logged_in']) || $_SESSION['utilisateur'] < 1) {
            // Utiliser un chemin absolu pour la redirection
            header("Location: /cesi-lebonplan/login");
            exit;
        }
        
        // Initialiser le modèle seulement quand on en a besoin
        if (!isset($this->offreModel)) {
            $this->offreModel = new OffreModel();
        }
    }
    
    public function index() {
        // Vérifier les autorisations avant d'accéder à cette méthode
        $this->checkGestionAuth();
        
        // Récupérer toutes les offres pour les afficher dans la page de gestion
        $offres = $this->offreModel->getAllOffres();
        
        // Charger la vue de la page Gestion
        require 'app/views/gestion/gestion.php';
    }
    
    public function add() {
        // Vérifier les autorisations avant d'accéder à cette méthode
        $this->checkGestionAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $offreData = [
                'entreprise' => $_POST['entreprise'] ?? '',
                'titre' => $_POST['titre'] ?? '',
                'description' => $_POST['description'] ?? '',
                'competences' => $_POST['competences'] ?? '',
                'remuneration' => $_POST['remuneration'] ?? 0,
                'date_offre' => date('Y-m-d'),
                'nb_postulants' => 0
            ];
            
            // Valider les données (à implémenter selon vos besoins)
            
            // Ajouter l'offre dans la base de données
            $result = $this->offreModel->createOffre($offreData);
            
            if ($result) {
                // Rediriger vers la page de gestion avec un message de succès
                // Utiliser un chemin absolu
                header("Location: /cesi-lebonplan/gestion?success=1");
            } else {
                // Rediriger vers la page de gestion avec un message d'erreur
                // Utiliser un chemin absolu
                header("Location: /cesi-lebonplan/gestion?error=1");
            }
            exit;
        } else {
            // Afficher le formulaire d'ajout d'offre
            require 'app/views/gestion/add_offre.php';
        }
    }
    
    public function edit() {
        // Vérifier les autorisations avant d'accéder à cette méthode
        $this->checkGestionAuth();
        
        $id = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $offreData = [
                'entreprise' => $_POST['entreprise'] ?? '',
                'titre' => $_POST['titre'] ?? '',
                'description' => $_POST['description'] ?? '',
                'competences' => $_POST['competences'] ?? '',
                'remuneration' => $_POST['remuneration'] ?? 0,
                'date_offre' => $_POST['date_offre'] ?? date('Y-m-d'),
                'nb_postulants' => $_POST['nb_postulants'] ?? 0
            ];
            
            // Mettre à jour l'offre dans la base de données
            $result = $this->offreModel->updateOffre($id, $offreData);
            
            if ($result) {
                // Rediriger vers la page de gestion avec un message de succès
                // Utiliser un chemin absolu
                header("Location: /cesi-lebonplan/gestion?success=2");
            } else {
                // Rediriger vers la page de gestion avec un message d'erreur
                // Utiliser un chemin absolu
                header("Location: /cesi-lebonplan/gestion?error=2");
            }
            exit;
        } else {
            // Récupérer l'offre à modifier
            $offre = $this->offreModel->getOffreById($id);
            
            if (!$offre) {
                // Rediriger vers la page de gestion si l'offre n'existe pas
                // Utiliser un chemin absolu
                header("Location: /cesi-lebonplan/gestion?error=3");
                exit;
            }
            
            // Afficher le formulaire de modification avec les données de l'offre
            require 'app/views/gestion/edit_offre.php';
        }
    }
    
    public function delete() {
        // Vérifier les autorisations avant d'accéder à cette méthode
        $this->checkGestionAuth();
        
        $id = $_GET['id'] ?? 0;
        
        // Supprimer l'offre de la base de données
        $result = $this->offreModel->deleteOffre($id);
        
        if ($result) {
            // Rediriger vers la page de gestion avec un message de succès
            // Utiliser un chemin absolu
            header("Location: /cesi-lebonplan/gestion?success=3");
        } else {
            // Rediriger vers la page de gestion avec un message d'erreur
            // Utiliser un chemin absolu
            header("Location: /cesi-lebonplan/gestion?error=4");
        }
        exit;
    }
}
?>