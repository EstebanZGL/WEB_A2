<?php
class OffresController {
    private $offreModel;
    
    public function __construct() {
        require_once 'app/models/OffreModel.php';
        $this->offreModel = new OffreModel();
    }
    
    public function index() {
        // Récupérer toutes les offres pour l'affichage initial
        $offres = $this->offreModel->getAllOffres();
        
        // Passer les offres à la vue
        $data['offres'] = $offres;
        include 'app/views/offres/offres.php';
    }

    public function search() {
        // Récupérer les paramètres de recherche de manière simple
        $jobTitle = isset($_GET['jobTitle']) ? $_GET['jobTitle'] : '';
        $location = isset($_GET['location']) ? $_GET['location'] : '';
        // Effectuer la recherche
        $offres = $this->offreModel->searchOffres([
            'jobTitle' => $jobTitle,
            'location' => $location
        ]);
        // Retourner les résultats au format JSON
        header('Content-Type: application/json');
        echo json_encode($offres);
    }
    
    public function cities() {
        $cities = $this->offreModel->getAvailableCities();
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
    
    public function featured() {
        $featuredOffres = $this->offreModel->getFeaturedOffres(4);
        header('Content-Type: application/json');
        echo json_encode($featuredOffres);
    }
}