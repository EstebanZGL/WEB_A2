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
        $jobTitle = isset($_GET['jobTitle']) ? trim($_GET['jobTitle']) : '';
        $location = isset($_GET['location']) ? trim($_GET['location']) : '';
        $filters = isset($_GET['filters']) ? $_GET['filters'] : [];
        
        // Validation de la ville si elle est fournie
        if (!empty($location)) {
            $availableCities = $this->offreModel->getAvailableCities();
            if (!in_array($location, $availableCities)) {
                header('Content-Type: application/json');
                echo json_encode([
                    'error' => true,
                    'message' => 'Ville non valide',
                    'availableCities' => $availableCities
                ]);
                return;
            }
        }
    
        $searchParams = [
            'jobTitle' => $jobTitle,
            'location' => $location,
            'filters' => $filters
        ];
        
        // Effectuer la recherche
        $offres = $this->offreModel->searchOffres($searchParams);
        // Retourner les résultats au format JSON
        header('Content-Type: application/json');
        echo json_encode([
            'error' => false,
            'results' => $offres,
            'totalResults' => count($offres),
            'appliedFilters' => [
                'location' => $location,
                'jobTitle' => $jobTitle,
                'otherFilters' => $filters
            ]
        ]);
    }
    
    public function cities() {
        // Récupérer la liste des villes disponibles
        $cities = $this->offreModel->getAvailableCities();
        
        // Ajouter des métadonnées utiles
        $response = [
            'error' => false,
            'cities' => $cities,
            'total' => count($cities),
            'timestamp' => time()
        ];
        
        // Retourner les résultats au format JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    // Nouvelle méthode pour rechercher les villes par terme
    public function searchCities() {
        $term = isset($_GET['term']) ? trim($_GET['term']) : '';
        
        if (empty($term)) {
            $cities = $this->offreModel->getAvailableCities();
        } else {
            $cities = $this->offreModel->searchCities($term);
}
        
        header('Content-Type: application/json');
        echo json_encode([
            'error' => false,
            'results' => $cities,
            'searchTerm' => $term,
            'total' => count($cities)
        ]);
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
        // Récupérer les offres les plus récentes (max 4)
        $featuredOffres = $this->offreModel->getFeaturedOffres(4);
        
        // Retourner les résultats au format JSON
        header('Content-Type: application/json');
        echo json_encode($featuredOffres);
    }
}