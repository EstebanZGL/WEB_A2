<?php
require_once 'app/models/UserModel.php';
require_once 'app/models/OffreModel.php';
require_once 'app/models/PiloteModel.php';

class AdminController {
    private $userModel;
    private $offreModel;
    private $piloteModel;
    private $itemsPerPage = 10; // Nombre d'éléments par page
    
    public function __construct() {
        // Vérifier si la session est déjà démarrée
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Initialiser les modèles seulement si nécessaire
        // Ne pas les initialiser dans le constructeur pour éviter les erreurs de connexion à la BD
    }
    
    // Méthode privée pour vérifier les autorisations
    private function checkAdminAuth() {
        // Vérifier si l'utilisateur est connecté et a les droits d'administrateur
        if (!isset($_SESSION['logged_in']) || $_SESSION['utilisateur'] != 2) {
            // Utiliser un chemin absolu pour la redirection
            header("Location: login");
            exit;
        }
        
        // Initialiser les modèles seulement quand on en a besoin
        if (!isset($this->userModel)) {
            $this->userModel = new UserModel();
        }
        
        if (!isset($this->offreModel)) {
            $this->offreModel = new OffreModel();
        }
        
        if (!isset($this->piloteModel)) {
            $this->piloteModel = new PiloteModel();
        }
    }
    
    public function index() {
        // Vérifier les autorisations avant d'accéder à cette méthode
        $this->checkAdminAuth();
        
        // Récupérer des statistiques pour le tableau de bord admin
        $stats = [
            'totalOffres' => count($this->offreModel->getAllOffres()),
            'totalUsers' => count($this->userModel->getAllUsers()),
            'totalPilotes' => $this->piloteModel->countPilotes()
            // Ajoutez d'autres statistiques selon vos besoins
        ];
        
        // Charger la vue de la page Admin
        require 'app/views/admin/admin.php';
    }
    
    public function manage() {
        // Vérifier les autorisations avant d'accéder à cette méthode
        $this->checkAdminAuth();
        
        // Cette méthode peut être utilisée pour gérer les utilisateurs, les paramètres du site, etc.
        
        // Par exemple, récupérer tous les utilisateurs
        $users = $this->userModel->getAllUsers();
        
        // Charger la vue de gestion des utilisateurs
        require 'app/views/admin/manage.php';
    }
    
    public function pilotes() {
        // Vérifier les autorisations avant d'accéder à cette méthode
        $this->checkAdminAuth();
        
        // Récupérer les paramètres de la requête
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // S'assurer que la page est au moins 1
        if ($page < 1) {
            $page = 1;
        }
        
        // Calculer l'offset pour la pagination
        $offset = ($page - 1) * $this->itemsPerPage;
        
        // Récupérer les pilotes avec pagination
        $pilotes = $this->piloteModel->getPilotes($this->itemsPerPage, $offset);
        $totalPilotes = $this->piloteModel->countPilotes();
        
        // Calculer le nombre total de pages
        $totalPages = ceil($totalPilotes / $this->itemsPerPage);
        
        // Préparer les données pour la vue
        $viewData = [
            'pilotes' => $pilotes,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalPilotes,
            'itemsPerPage' => $this->itemsPerPage
        ];
        
        // Charger la vue de gestion des pilotes
        require 'app/views/admin/pilotes.php';
    }
    
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
                // Créer un mot de passe - soit celui fourni, soit un par défaut
                if (empty($password)) {
                    $password = password_hash('changeme', PASSWORD_DEFAULT);
                } else {
                    $password = password_hash($password, PASSWORD_DEFAULT);
                }
                
                // Créer l'utilisateur
                $utilisateur_id = $this->userModel->createUser($email, $password, $nom, $prenom);
                
                if ($utilisateur_id) {
                    // Créer le pilote
                    $piloteData = [
                        'utilisateur_id' => $utilisateur_id,
                        'departement' => $departement,
                        'specialite' => $specialite
                    ];
                    
                    $result = $this->piloteModel->createPilote($piloteData);
                    
                    if ($result) {
                        header("Location: ../../admin/pilotes?success=1");
                        exit;
                    } else {
                        // En cas d'échec, supprimer l'utilisateur créé
                        $this->userModel->deleteUser($utilisateur_id);
                        header("Location: ../../admin/pilotes?error=1");
                        exit;
                    }
                } else {
                    header("Location: ../../admin/pilotes?error=1");
                    exit;
                }
            } else {
                header("Location: ../../admin/pilotes?error=1");
                exit;
            }
        } else {
            // Afficher le formulaire d'ajout de pilote
            require 'app/views/admin/add_pilote.php';
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
                header("Location: ../../admin/pilotes?success=2");
            } else {
                header("Location: ../../admin/pilotes?error=2");
            }
            exit;
        } else {
            // Récupérer le pilote à modifier
            $pilote = $this->piloteModel->getPiloteById($id);
            
            if (!$pilote) {
                header("Location: ../../admin/pilotes?error=3");
                exit;
            }
            
            // Afficher le formulaire de modification avec les données du pilote
            require 'app/views/admin/edit_pilote.php';
        }
    }
    
    public function deletePilote() {
        $this->checkAdminAuth();
        
        $id = $_GET['id'] ?? 0;
        
        // Supprimer le pilote de la base de données
        $result = $this->piloteModel->deletePilote($id);
        
        if ($result) {
            header("Location: ../../admin/pilotes?success=3");
        } else {
            header("Location: ../../admin/pilotes?error=4");
        }
        exit;
    }
    
    public function statsPilotes() {
        $this->checkAdminAuth();
        
        // Récupérer les statistiques des pilotes
        $stats = $this->piloteModel->getPiloteStats();
        
        // Afficher la page de statistiques
        require 'app/views/admin/stats_pilotes.php';
    }
}
?>