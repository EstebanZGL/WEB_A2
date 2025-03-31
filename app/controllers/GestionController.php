<?php
require_once 'app/models/OffreModel.php';
require_once 'app/models/EntrepriseModel.php';
require_once 'app/models/EtudiantModel.php';
require_once 'app/models/PiloteModel.php';

class GestionController {
    private $offreModel;
    private $entrepriseModel;
    private $etudiantModel;
    private $piloteModel;
    private $itemsPerPage = 10; // Nombre d'éléments par page
    
    public function __construct() {
        // Vérifier si la session est déjà démarrée
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    // Méthode privée pour vérifier les autorisations
    private function checkGestionAuth() {
        // Vérifier si l'utilisateur est connecté et a les droits de gestion
        if (!isset($_SESSION['logged_in']) || $_SESSION['utilisateur'] < 1) {
            // Utiliser un chemin absolu pour la redirection
            header("Location: login");
            exit;
        }
        
        // Initialiser les modèles seulement quand on en a besoin
        if (!isset($this->offreModel)) {
            $this->offreModel = new OffreModel();
        }
        if (!isset($this->entrepriseModel)) {
            $this->entrepriseModel = new EntrepriseModel();
        }
        if (!isset($this->etudiantModel)) {
            $this->etudiantModel = new EtudiantModel();
        }
        if (!isset($this->piloteModel)) {
            $this->piloteModel = new PiloteModel();
        }
    }
    
    // Méthode privée pour vérifier les autorisations d'administrateur
    private function checkAdminAuth() {
        // Vérifier si l'utilisateur est connecté et a les droits d'administrateur
        if (!isset($_SESSION['logged_in']) || $_SESSION['utilisateur'] != 2) {
            // Utiliser un chemin absolu pour la redirection
            header("Location: login");
            exit;
        }
        
        // Initialiser les modèles seulement quand on en a besoin
        if (!isset($this->piloteModel)) {
            $this->piloteModel = new PiloteModel();
        }
    }
    
    public function index() {
        // Vérifier les autorisations avant d'accéder à cette méthode
        $this->checkGestionAuth();
        
        // Récupérer les paramètres de la requête
        $section = isset($_GET['section']) ? $_GET['section'] : 'offres';
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // S'assurer que la page est au moins 1
        if ($page < 1) {
            $page = 1;
        }
        
        // Calculer l'offset pour la pagination
        $offset = ($page - 1) * $this->itemsPerPage;
        
        // Variables pour stocker les données et la pagination
        $items = [];
        $totalItems = 0;
        $totalPages = 0;
        
        // Récupérer les données selon la section
        switch ($section) {
            case 'entreprises':
                $items = $this->entrepriseModel->getEntreprises($this->itemsPerPage, $offset);
                $totalItems = $this->entrepriseModel->countEntreprises();
                break;
                
            case 'etudiants':
                $items = $this->etudiantModel->getEtudiants($this->itemsPerPage, $offset);
                $totalItems = $this->etudiantModel->countEtudiants();
                break;
                
            case 'pilotes':
                // Vérifier si l'utilisateur est administrateur pour cette section
                if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur'] != 2) {
                    header("Location: gestion?section=offres");
                    exit;
                }
                $items = $this->piloteModel->getPilotes($this->itemsPerPage, $offset);
                $totalItems = $this->piloteModel->countPilotes();
                break;
                
            case 'offres':
            default:
                $items = $this->offreModel->getOffres($this->itemsPerPage, $offset);
                $totalItems = $this->offreModel->countOffres();
                $section = 'offres'; // Assurer que la section est définie correctement
                break;
        }
            
        // Calculer le nombre total de pages
        $totalPages = ceil($totalItems / $this->itemsPerPage);
        
        // Préparer les données pour la vue
        $viewData = [
            'section' => $section,
            'items' => $items,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $this->itemsPerPage,
            'isAdmin' => isset($_SESSION['utilisateur']) && $_SESSION['utilisateur'] == 2
        ];
        
        // Charger la vue de la page Gestion
        require 'app/views/gestion/gestion.php';
    }
    
    // [... Autres méthodes existantes pour les offres, entreprises et étudiants ...]
    
    // Méthodes pour les pilotes
    public function addPilote() {
        $this->checkAdminAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire pour créer un nouvel utilisateur
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $departement = trim($_POST['departement'] ?? '');
            $specialite = trim($_POST['specialite'] ?? '');
            // Créer un nouvel utilisateur si les champs nom et prénom sont remplis
            if (!empty($nom) && !empty($prenom) && !empty($email)) {
                // Inclure le modèle utilisateur
                require_once 'app/models/UserModel.php';
                $userModel = new UserModel();
                
                // Créer un mot de passe - soit celui fourni, soit un par défaut
                if (empty($password)) {
                    $password = password_hash('changeme', PASSWORD_DEFAULT);
                } else {
                    $password = password_hash($password, PASSWORD_DEFAULT);
                }
                
                // Créer l'utilisateur
                $utilisateur_id = $userModel->createUser($email, $password, $nom, $prenom);
                if ($utilisateur_id) {
                    // Créer le pilote
                    $piloteData = [
                        'utilisateur_id' => $utilisateur_id,
                        'departement' => $departement,
                        'specialite' => $specialite
                    ];
                    
                    $result = $this->piloteModel->createPilote($piloteData);
                    if ($result) {
                        header("Location: ../../gestion?section=pilotes&success=1");
                        exit;
                    } else {
                        // En cas d'échec, supprimer l'utilisateur créé
                        $userModel->deleteUser($utilisateur_id);
                        header("Location: ../../gestion?section=pilotes&error=1");
                        exit;
                    }
                } else {
                    header("Location: ../../gestion?section=pilotes&error=1");
                    exit;
                }
            } else {
                header("Location: ../../gestion?section=pilotes&error=1");
                exit;
            }
        } else {
            // Afficher le formulaire d'ajout de pilote
            require 'app/views/gestion/add_pilote.php';
        }
    }
    
    public function editPilote() {
        $this->checkAdminAuth();
        
        $id = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $piloteData = [
                'departement' => $_POST['departement'] ?? '',
                'specialite' => $_POST['specialite'] ?? ''
            ];
            
            // Mettre à jour le pilote dans la base de données
            $result = $this->piloteModel->updatePilote($id, $piloteData);
            
            if ($result) {
                header("Location: ../../gestion?section=pilotes&success=2");
            } else {
                header("Location: ../../gestion?section=pilotes&error=2");
            }
            exit;
        } else {
            // Récupérer le pilote à modifier
            $pilote = $this->piloteModel->getPiloteById($id);
            
            if (!$pilote) {
                header("Location: ../../gestion?section=pilotes&error=3");
                exit;
            }
            
            // Afficher le formulaire de modification avec les données du pilote
            require 'app/views/gestion/edit_pilote.php';
        }
    }
    
    public function deletePilote() {
        $this->checkAdminAuth();
        
        $id = $_GET['id'] ?? 0;
        
        // Supprimer le pilote de la base de données
        $result = $this->piloteModel->deletePilote($id);
        
        if ($result) {
            header("Location: ../../gestion?section=pilotes&success=3");
        } else {
            header("Location: ../../gestion?section=pilotes&error=4");
        }
        exit;
    }
    
    public function statsPilotes() {
        $this->checkAdminAuth();
        
        // Récupérer les statistiques des pilotes
        $stats = $this->piloteModel->getPiloteStats();
        
        // Afficher la page de statistiques
        require 'app/views/gestion/stats_pilotes.php';
    }
}
?>