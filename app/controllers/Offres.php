<?php

class Offres extends Controller {
    private $offreModel;
    
    public function __construct() {
        $this->offreModel = $this->model('OffreModel');
    }
    
    public function index() {
        $this->view('offres/offres');
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
    
    public function details($id = null) {
        if ($id === null) {
            redirect('offres');
        }
        
        $offre = $this->offreModel->getOffreById($id);
        
        if (!$offre) {
            redirect('offres');
        }
        
        $data = [
            'offre' => $offre
        ];
        
        $this->view('offres/details', $data);
    }
}