<?php
require_once 'app/models/OffreModel.php';

class OffresController {
    private $offreModel;
    
    public function __construct() {
        $this->offreModel = new OffreModel();
    }
    
    public function index() {
        // Charger la vue de la page des offres
        require 'app/views/offres/offres.php';
    }

    public function search() {
        // Récupérer les paramètres de recherche
        $jobTitle = $_GET['jobTitle'] ?? '';
        $location = $_GET['location'] ?? '';
        
        // Appeler le modèle pour effectuer la recherche
        $results = $this->offreModel->searchOffres($jobTitle, $location);
        
        // Retourner les résultats au format JSON
        header('Content-Type: application/json');
        echo json_encode($results);
    }
}
?>