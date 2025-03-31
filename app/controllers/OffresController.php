<?php
class OffresController {
    private $offreModel;
    
    public function __construct() {
        require_once 'app/models/OffreModel.php';
        $this->offreModel = new OffreModel();
    }
    
    public function index() {
        include 'app/views/offres/offres.php';
    }

    public function search() {
        // Récupérer les paramètres de recherche
        $jobTitle = isset($_GET['jobTitle']) ? $_GET['jobTitle'] : '';
        $location = isset($_GET['location']) ? $_GET['location'] : '';
        $filters = isset($_GET['filters']) ? $_GET['filters'] : [];
        
        $searchParams = [
            'jobTitle' => $jobTitle,
            'location' => $location,
            'filters' => $filters
        ];
        
        // Effectuer la recherche
        $offres = $this->offreModel->searchOffres($searchParams);
        
        // Retourner les résultats au format JSON
        header('Content-Type: application/json');
        echo json_encode($offres);
    }
    
    public function cities() {
        // Récupérer la liste des villes disponibles
        $cities = $this->offreModel->getAvailableCities();
        
        // Retourner les résultats au format JSON
        header('Content-Type: application/json');
        echo json_encode($cities);
    }
    
    public function details($id = null) {
        if ($id === null) {
            header('Location: /offres');
            exit;
        }
        
        $offre = $this->offreModel->getOffreById($id);
        
        if (!$offre) {
            header('Location: /offres');
            exit;
        }
        
        include 'app/views/offres/details.php';
    }
}