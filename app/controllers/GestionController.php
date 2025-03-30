<?php
require_once 'app/models/OffreModel.php';
require_once 'app/models/EntrepriseModel.php';
require_once 'app/models/EtudiantModel.php';

class GestionController {
    private $offreModel;
    private $entrepriseModel;
    private $etudiantModel;
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
            'itemsPerPage' => $this->itemsPerPage
        ];
        
        // Charger la vue de la page Gestion
        require 'app/views/gestion/gestion.php';
    }
    
    // Méthodes pour les offres
    public function addOffre() {
        $this->checkGestionAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $offreData = [
                'entreprise_id' => $_POST['entreprise_id'] ?? 0,
                'createur_id' => $_SESSION['user_id'] ?? 0, // Utiliser l'ID de l'utilisateur connecté
                'titre' => $_POST['titre'] ?? '',
                'description' => $_POST['description'] ?? '',
                'remuneration' => $_POST['remuneration'] ?? 0,
                'date_debut' => $_POST['date_debut'] ?? null,
                'date_fin' => $_POST['date_fin'] ?? null,
                'date_publication' => date('Y-m-d'),
                'statut' => $_POST['statut'] ?? 'ACTIVE',
                'duree_stage' => $_POST['duree_stage'] ?? 0
            ];
            
            // Ajouter l'offre dans la base de données
            $result = $this->offreModel->createOffre($offreData);
            
            if ($result) {
                header("Location: gestion?section=offres&success=1");
            } else {
                header("Location: gestion?section=offres&error=1");
            }
            exit;
        } else {
            // Récupérer la liste des entreprises pour le formulaire
            $entreprises = $this->entrepriseModel->getEntreprisesForSelect();
            
            // Afficher le formulaire d'ajout d'offre
            require 'app/views/gestion/add_offre.php';
        }
    }
    
    public function editOffre() {
        $this->checkGestionAuth();
        
        $id = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $offreData = [
                'entreprise_id' => $_POST['entreprise_id'] ?? 0,
                'titre' => $_POST['titre'] ?? '',
                'description' => $_POST['description'] ?? '',
                'remuneration' => $_POST['remuneration'] ?? 0,
                'date_debut' => $_POST['date_debut'] ?? null,
                'date_fin' => $_POST['date_fin'] ?? null,
                'statut' => $_POST['statut'] ?? 'ACTIVE',
                'duree_stage' => $_POST['duree_stage'] ?? 0
            ];
            
            // Mettre à jour l'offre dans la base de données
            $result = $this->offreModel->updateOffre($id, $offreData);
            
            if ($result) {
                header("Location: gestion?section=offres&success=2");
            } else {
                header("Location: gestion?section=offres&error=2");
            }
            exit;
        } else {
            // Récupérer l'offre à modifier
            $offre = $this->offreModel->getOffreById($id);
            
            if (!$offre) {
                header("Location: gestion?section=offres&error=3");
                exit;
            }
            
            // Récupérer la liste des entreprises pour le formulaire
            $entreprises = $this->entrepriseModel->getEntreprisesForSelect();
            
            // Afficher le formulaire de modification avec les données de l'offre
            require 'app/views/gestion/edit_offre.php';
        }
    }
    
    public function deleteOffre() {
        $this->checkGestionAuth();
        
        $id = $_GET['id'] ?? 0;
        
        // Supprimer l'offre de la base de données
        $result = $this->offreModel->deleteOffre($id);
        
        if ($result) {
            header("Location: gestion?section=offres&success=3");
        } else {
            header("Location: gestion?section=offres&error=4");
        }
        exit;
    }
    
    public function statsOffres() {
        $this->checkGestionAuth();
        
        // Récupérer les statistiques des offres
        $stats = $this->offreModel->getOffreStats();
        
        // Afficher la page de statistiques
        require 'app/views/gestion/stats_offres.php';
    }
    
    // Méthodes pour les entreprises
    public function addEntreprise() {
        $this->checkGestionAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $entrepriseData = [
                'createur_id' => $_SESSION['user_id'] ?? 0,
                'nom' => $_POST['nom'] ?? '',
                'description' => $_POST['description'] ?? '',
                'email_contact' => $_POST['email_contact'] ?? '',
                'telephone_contact' => $_POST['telephone_contact'] ?? '',
                'adresse' => $_POST['adresse'] ?? '',
                'lien_site' => $_POST['lien_site'] ?? '',
                'date_creation' => date('Y-m-d H:i:s')
            ];
            
            // Ajouter l'entreprise dans la base de données
            $result = $this->entrepriseModel->createEntreprise($entrepriseData);
            
            if ($result) {
                header("Location: gestion?section=entreprises&success=1");
            } else {
                header("Location: gestion?section=entreprises&error=1");
            }
            exit;
        } else {
            // Afficher le formulaire d'ajout d'entreprise
            require 'app/views/gestion/add_entreprise.php';
        }
    }
    
    public function editEntreprise() {
        $this->checkGestionAuth();
        
        $id = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $entrepriseData = [
                'nom' => $_POST['nom'] ?? '',
                'description' => $_POST['description'] ?? '',
                'email_contact' => $_POST['email_contact'] ?? '',
                'telephone_contact' => $_POST['telephone_contact'] ?? '',
                'adresse' => $_POST['adresse'] ?? '',
                'lien_site' => $_POST['lien_site'] ?? ''
            ];
            
            // Mettre à jour l'entreprise dans la base de données
            $result = $this->entrepriseModel->updateEntreprise($id, $entrepriseData);
            
            if ($result) {
                header("Location: gestion?section=entreprises&success=2");
            } else {
                header("Location: gestion?section=entreprises&error=2");
            }
            exit;
        } else {
            // Récupérer l'entreprise à modifier
            $entreprise = $this->entrepriseModel->getEntrepriseById($id);
            
            if (!$entreprise) {
                header("Location: gestion?section=entreprises&error=3");
                exit;
            }
            
            // Afficher le formulaire de modification avec les données de l'entreprise
            require 'app/views/gestion/edit_entreprise.php';
        }
    }
    
    public function deleteEntreprise() {
        $this->checkGestionAuth();
        
        $id = $_GET['id'] ?? 0;
        
        // Supprimer l'entreprise de la base de données
        $result = $this->entrepriseModel->deleteEntreprise($id);
        
        if ($result) {
            header("Location: gestion?section=entreprises&success=3");
        } else {
            header("Location: gestion?section=entreprises&error=4");
        }
        exit;
    }
    
    public function statsEntreprises() {
        $this->checkGestionAuth();
        
        // Récupérer les statistiques des entreprises
        $stats = $this->entrepriseModel->getEntrepriseStats();
        
        // Afficher la page de statistiques
        require 'app/views/gestion/stats_entreprises.php';
    }
    
    // Méthodes pour les étudiants
    public function addEtudiant() {
        $this->checkGestionAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire pour créer un nouvel utilisateur
            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';
            $email = $_POST['email'] ?? '';
            
            // Créer un nouvel utilisateur si les champs nom et prénom sont remplis
            $utilisateur_id = 0;
            if (!empty($nom) && !empty($prenom) && !empty($email)) {
                // Inclure le modèle utilisateur
                require_once 'app/models/UserModel.php';
                $userModel = new UserModel();
                
                // Créer un mot de passe temporaire (à changer lors de la première connexion)
                $password = password_hash('changeme', PASSWORD_DEFAULT);
                
                // Créer l'utilisateur et récupérer son ID
                $userType = 0; // 0 pour étudiant
                $utilisateur_id = $userModel->createUser($email, $password, $userType);
                
                // Mettre à jour les informations de l'utilisateur
                if ($utilisateur_id) {
                    $userModel->updateUser($utilisateur_id, [
                        'nom' => $nom,
                        'prenom' => $prenom
                    ]);
                }
            }
            
            // Récupérer les autres données du formulaire pour l'étudiant
            $etudiantData = [
                'utilisateur_id' => $utilisateur_id,
                'promotion' => $_POST['promotion'] ?? '',
                'formation' => $_POST['formation'] ?? '',
                'offre_id' => !empty($_POST['offre_id']) ? $_POST['offre_id'] : null
            ];
            
            // Ajouter l'étudiant dans la base de données
            $result = $this->etudiantModel->createEtudiant($etudiantData);
            
            if ($result) {
                header("Location: ../../gestion?section=etudiants&success=1");
            } else {
                header("Location: ../../gestion?section=etudiants&error=1");
            }
            exit;
        } else {
            // Récupérer la liste des utilisateurs et des offres pour le formulaire
            $utilisateurs = $this->etudiantModel->getUtilisateursForSelect();
            $offres = $this->offreModel->getOffresForSelect();
            
            // Afficher le formulaire d'ajout d'étudiant
            require 'app/views/gestion/add_etudiant.php';
        }
    }
    
    public function editEtudiant() {
        $this->checkGestionAuth();
        
        $id = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $etudiantData = [
                'promotion' => $_POST['promotion'] ?? '',
                'formation' => $_POST['formation'] ?? '',
                'offre_id' => $_POST['offre_id'] ?? null
            ];
            
            // Mettre à jour l'étudiant dans la base de données
            $result = $this->etudiantModel->updateEtudiant($id, $etudiantData);
            
            if ($result) {
                header("Location: gestion?section=etudiants&success=2");
            } else {
                header("Location: gestion?section=etudiants&error=2");
            }
            exit;
        } else {
            // Récupérer l'étudiant à modifier
            $etudiant = $this->etudiantModel->getEtudiantById($id);
            
            if (!$etudiant) {
                header("Location: gestion?section=etudiants&error=3");
                exit;
            }
            
            // Récupérer la liste des offres pour le formulaire
            $offres = $this->offreModel->getOffresForSelect();
            
            // Afficher le formulaire de modification avec les données de l'étudiant
            require 'app/views/gestion/edit_etudiant.php';
        }
    }
    
    public function deleteEtudiant() {
        $this->checkGestionAuth();
        
        $id = $_GET['id'] ?? 0;
        
        // Supprimer l'étudiant de la base de données
        $result = $this->etudiantModel->deleteEtudiant($id);
        
        if ($result) {
            header("Location: gestion?section=etudiants&success=3");
        } else {
            header("Location: gestion?section=etudiants&error=4");
        }
        exit;
    }
    
    public function statsEtudiants() {
        $this->checkGestionAuth();
        
        // Récupérer les statistiques des étudiants
        $stats = $this->etudiantModel->getEtudiantStats();
        
        // Afficher la page de statistiques
        require 'app/views/gestion/stats_etudiants.php';
    }
}
?>