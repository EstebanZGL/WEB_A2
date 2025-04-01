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
    
    // Méthodes pour les offres
    public function addOffre() {
        $this->checkGestionAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $offreData = [
                'titre' => $_POST['titre'] ?? '',
                'description' => $_POST['description'] ?? '',
                'entreprise_id' => $_POST['entreprise_id'] ?? null,
                'date_debut' => $_POST['date_debut'] ?? null,
                'duree_stage' => $_POST['duree_stage'] ?? 0,
                'remuneration' => $_POST['remuneration'] ?? 0,
                'statut' => $_POST['statut'] ?? 'Disponible',
                'competences' => $_POST['competences'] ?? '',
                'date_publication' => date('Y-m-d H:i:s')
            ];
            
            // Créer l'offre dans la base de données
            $result = $this->offreModel->createOffre($offreData);
            
            if ($result) {
                header("Location: ../../gestion?section=offres&success=1");
            } else {
                header("Location: ../../gestion?section=offres&error=1");
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
                'titre' => $_POST['titre'] ?? '',
                'description' => $_POST['description'] ?? '',
                'entreprise_id' => $_POST['entreprise_id'] ?? null,
                'date_debut' => $_POST['date_debut'] ?? null,
                'duree_stage' => $_POST['duree_stage'] ?? 0,
                'remuneration' => $_POST['remuneration'] ?? 0,
                'statut' => $_POST['statut'] ?? 'Disponible',
                'competences' => $_POST['competences'] ?? ''
            ];
            
            // Mettre à jour l'offre dans la base de données
            $result = $this->offreModel->updateOffre($id, $offreData);
            
            if ($result) {
                header("Location: ../../gestion?section=offres&success=2");
            } else {
                header("Location: ../../gestion?section=offres&error=2");
            }
            exit;
        } else {
            // Récupérer l'offre à modifier
            $offre = $this->offreModel->getOffreById($id);
            
            if (!$offre) {
                header("Location: ../../gestion?section=offres&error=3");
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
            header("Location: ../../gestion?section=offres&success=3");
        } else {
            header("Location: ../../gestion?section=offres&error=4");
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
                'nom' => $_POST['nom'] ?? '',
                'description' => $_POST['description'] ?? '',
                'secteur_activite' => $_POST['secteur_activite'] ?? '',
                'adresse' => $_POST['adresse'] ?? '',
                'code_postal' => $_POST['code_postal'] ?? '',
                'ville' => $_POST['ville'] ?? '',
                'pays' => $_POST['pays'] ?? 'France',
                'telephone_contact' => $_POST['telephone_contact'] ?? '',
                'email_contact' => $_POST['email_contact'] ?? '',
                'site_web' => $_POST['site_web'] ?? '',
                'date_creation' => date('Y-m-d')
            ];
            
            // Créer l'entreprise dans la base de données
            $result = $this->entrepriseModel->createEntreprise($entrepriseData);
            
            if ($result) {
                header("Location: ../../gestion?section=entreprises&success=1");
            } else {
                header("Location: ../../gestion?section=entreprises&error=1");
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
                'secteur_activite' => $_POST['secteur_activite'] ?? '',
                'adresse' => $_POST['adresse'] ?? '',
                'code_postal' => $_POST['code_postal'] ?? '',
                'ville' => $_POST['ville'] ?? '',
                'pays' => $_POST['pays'] ?? 'France',
                'telephone_contact' => $_POST['telephone_contact'] ?? '',
                'email_contact' => $_POST['email_contact'] ?? '',
                'site_web' => $_POST['site_web'] ?? ''
            ];
            
            // Mettre à jour l'entreprise dans la base de données
            $result = $this->entrepriseModel->updateEntreprise($id, $entrepriseData);
            
            if ($result) {
                header("Location: ../../gestion?section=entreprises&success=2");
            } else {
                header("Location: ../../gestion?section=entreprises&error=2");
            }
            exit;
        } else {
            // Récupérer l'entreprise à modifier
            $entreprise = $this->entrepriseModel->getEntrepriseById($id);
            
            if (!$entreprise) {
                header("Location: ../../gestion?section=entreprises&error=3");
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
            header("Location: ../../gestion?section=entreprises&success=3");
        } else {
            header("Location: ../../gestion?section=entreprises&error=4");
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
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $promotion = trim($_POST['promotion'] ?? '');
            $formation = trim($_POST['formation'] ?? '');
            $offre_id = !empty($_POST['offre_id']) ? $_POST['offre_id'] : null;
            
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
                
                // Utiliser directement createEtudiant au lieu de createUser pour éviter la duplication
                $utilisateur_id = $userModel->createEtudiant($email, $password, $nom, $prenom, $promotion, $formation, $offre_id);
                
                if ($utilisateur_id) {
                    header("Location: ../../gestion?section=etudiants&success=1");
                    exit;
                } else {
                    header("Location: ../../gestion?section=etudiants&error=1");
                    exit;
                }
            } else {
                header("Location: ../../gestion?section=etudiants&error=1");
                exit;
            }
        } else {
            // Récupérer la liste des offres pour le formulaire
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
                'offre_id' => !empty($_POST['offre_id']) ? $_POST['offre_id'] : null
            ];
            
            // Mettre à jour l'étudiant dans la base de données
            $result = $this->etudiantModel->updateEtudiant($id, $etudiantData);
            
            if ($result) {
                header("Location: ../../gestion?section=etudiants&success=2");
            } else {
                header("Location: ../../gestion?section=etudiants&error=2");
            }
            exit;
        } else {
            // Récupérer l'étudiant à modifier
            $etudiant = $this->etudiantModel->getEtudiantById($id);
            
            if (!$etudiant) {
                header("Location: ../../gestion?section=etudiants&error=3");
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
            header("Location: ../../gestion?section=etudiants&success=3");
        } else {
            header("Location: ../../gestion?section=etudiants&error=4");
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
                
                // Créer directement le pilote avec la méthode createPilote
                $utilisateur_id = $userModel->createPilote($email, $password, $nom, $prenom, $departement, $specialite);
                
                if ($utilisateur_id) {
                    header("Location: ../../gestion?section=pilotes&success=1");
                    exit;
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