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
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
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
                if (!empty($search)) {
                    $items = $this->entrepriseModel->searchEntreprises($search, $this->itemsPerPage, $offset);
                    $totalItems = $this->entrepriseModel->countEntreprisesSearch($search);
                } else {
                    $items = $this->entrepriseModel->getEntreprises($this->itemsPerPage, $offset);
                    $totalItems = $this->entrepriseModel->countEntreprises();
                }
                break;
                
            case 'etudiants':
                if (!empty($search)) {
                    $items = $this->etudiantModel->searchEtudiants($search, $this->itemsPerPage, $offset);
                    $totalItems = $this->etudiantModel->countEtudiantsSearch($search);
                } else {
                    $items = $this->etudiantModel->getEtudiants($this->itemsPerPage, $offset);
                    $totalItems = $this->etudiantModel->countEtudiants();
                }
                break;
                
            case 'pilotes':
                // Vérifier si l'utilisateur est administrateur pour cette section
                if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur'] != 2) {
                    header("Location: gestion?section=offres");
                    exit;
                }
                if (!empty($search)) {
                    $items = $this->piloteModel->searchPilotes($search, $this->itemsPerPage, $offset);
                    $totalItems = $this->piloteModel->countPilotesSearch($search);
                } else {
                    $items = $this->piloteModel->getPilotes($this->itemsPerPage, $offset);
                    $totalItems = $this->piloteModel->countPilotes();
                }
                break;
                
            case 'offres':
            default:
                if (!empty($search)) {
                    $items = $this->offreModel->searchOffresAdmin($search, $this->itemsPerPage, $offset);
                    $totalItems = $this->offreModel->countOffresSearch($search);
                } else {
                    $items = $this->offreModel->getOffres($this->itemsPerPage, $offset);
                    $totalItems = $this->offreModel->countOffres();
                }
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
            'isAdmin' => isset($_SESSION['utilisateur']) && $_SESSION['utilisateur'] == 2,
            'search' => $search
        ];
        
        // Charger la vue de la page Gestion
        require 'app/views/gestion/gestion.php';
    }
    
    public function addOffre() {
        $this->checkGestionAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $offreData = [
                'titre' => $_POST['titre'] ?? '',
                'description' => $_POST['description'] ?? '',
                'entreprise_id' => $_POST['entreprise_id'] ?? null,
                'date_debut' => $_POST['date_debut'] ?? null,
                'date_fin' => $_POST['date_fin'] ?? null,
                'duree_stage' => $_POST['duree_stage'] ?? 0,
                'remuneration' => $_POST['remuneration'] ?? 0,
                'statut' => $_POST['statut'] ?? 'ACTIVE',
                'type' => $_POST['type'] ?? null,
                'lieu' => $_POST['lieu'] ?? null,
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
                'createur_id' => $_POST['createur_id'] ?? null, 
                'date_debut' => $_POST['date_debut'] ?? null,
                'date_fin' => $_POST['date_fin'] ?? null,
                'duree_stage' => $_POST['duree_stage'] ?? 0,
                'remuneration' => $_POST['remuneration'] ?? 0,
                'statut' => $_POST['statut'] ?? 'ACTIVE',
                'type' => $_POST['type'] ?? null,
                'lieu' => $_POST['lieu'] ?? null
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
            $ville = trim($_POST['departement'] ?? '');
            $specialite = trim($_POST['specialite'] ?? '');
            
            error_log("Tentative de création d'un pilote: $nom $prenom, $email, ville: $ville, specialité: $specialite");
            
            // Créer un nouvel utilisateur si les champs nom et prénom sont remplis
            if (!empty($nom) && !empty($prenom) && !empty($email)) {
                // Inclure le modèle utilisateur
                require_once 'app/models/UserModel.php';
                $userModel = new UserModel();
                
                // Vérifier d'abord si l'email existe déjà
                $existingUser = $userModel->getUserByEmail($email);
                if ($existingUser) {
                    error_log("Email déjà utilisé: $email");
                    $error = "L'email $email est déjà utilisé. Veuillez en choisir un autre.";
                    require 'app/views/gestion/add_pilote.php';
                    exit;
                }
                
                $password = password_hash($password, PASSWORD_DEFAULT);
                                
                // Créer directement le pilote avec la méthode createPilote
                $utilisateur_id = $userModel->createPilote($email, $password, $nom, $prenom, $ville, $specialite);
                
                if ($utilisateur_id) {
                    error_log("Pilote créé avec succès: ID=$utilisateur_id");
                    header("Location: ../../gestion?section=pilotes&success=1");
                    exit;
                } else {
                    error_log("Échec de la création du pilote");
                    // Afficher le formulaire avec un message d'erreur
                    $error = "Impossible de créer le pilote. Veuillez vérifier les informations saisies.";
                    require 'app/views/gestion/add_pilote.php';
                    exit;
                }
            } else {
                error_log("Données de formulaire incomplètes pour la création du pilote");
                $error = "Veuillez remplir tous les champs obligatoires.";
                require 'app/views/gestion/add_pilote.php';
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

    // Ajoutez cette méthode à votre classe GestionController

public function candidaturesEtudiant() {
    // Vérifier si l'utilisateur est connecté et a les droits
    $this->checkGestionAuth();
    
    // Récupérer l'ID de l'étudiant
    $etudiantId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if (!$etudiantId) {
        // Rediriger vers la liste des étudiants si aucun ID n'est fourni
        header('Location: /gestion?section=etudiants&error=3');
        exit;
    }
    
    // Charger les modèles nécessaires
    require_once 'app/models/CandidatureModel.php';
    $candidatureModel = new CandidatureModel();
    
    // Récupérer les informations de l'étudiant
    $etudiant = $candidatureModel->getEtudiantInfo($etudiantId);
    
    if (!$etudiant) {
        // Rediriger si l'étudiant n'existe pas
        header('Location: /gestion?section=etudiants&error=3');
        exit;
    }
    
    // Récupérer les candidatures de l'étudiant
    $candidatures = $candidatureModel->getCandidaturesByEtudiantId($etudiantId);
    
    // Récupérer les offres dans la wishlist de l'étudiant
    $wishlist = $candidatureModel->getWishlistByEtudiantId($etudiantId);
    
    // Charger la vue
    require_once 'app/views/gestion/candidatures_etudiant.php';
}

// Ajoutez cette méthode pour gérer l'ajout d'une candidature
public function addCandidature() {
    // Vérifier si l'utilisateur est connecté et a les droits
    $this->checkGestionAuth();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $etudiantId = isset($_POST['etudiant_id']) ? (int)$_POST['etudiant_id'] : 0;
        $offreId = isset($_POST['offre_id']) ? (int)$_POST['offre_id'] : 0;
        $statut = isset($_POST['statut']) ? $_POST['statut'] : 'En attente';
        
        if (!$etudiantId || !$offreId) {
            header('Location: /gestion/etudiants/candidatures?id=' . $etudiantId . '&error=1');
            exit;
        }
        
        require_once 'app/models/CandidatureModel.php';
        $candidatureModel = new CandidatureModel();
        
        if ($candidatureModel->addCandidature($etudiantId, $offreId, $statut)) {
            header('Location: /gestion/etudiants/candidatures?id=' . $etudiantId . '&success=1');
        } else {
            header('Location: /gestion/etudiants/candidatures?id=' . $etudiantId . '&error=1');
        }
        exit;
    }
    
    // Si ce n'est pas une requête POST, rediriger vers la liste des étudiants
    header('Location: /gestion?section=etudiants');
    exit;
}

// Ajoutez cette méthode pour gérer la mise à jour du statut d'une candidature
/**
 * Met à jour le statut d'une candidature via AJAX
 */
public function updateCandidatureStatus()
{
    // Vérifier si la requête est bien une requête AJAX
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
        exit;
    }
    
    // Vérifier si l'utilisateur est connecté et a les droits
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'pilote') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Accès refusé. Vous devez être connecté en tant que pilote.']);
        exit;
    }
    
    // Récupérer les données de la requête
    $candidatureId = isset($_POST['candidature_id']) ? intval($_POST['candidature_id']) : 0;
    $statut = isset($_POST['status']) ? $_POST['status'] : '';
    
    // Valider les données
    if ($candidatureId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID de candidature invalide']);
        exit;
    }
    
    // Vérifier que le statut est valide
    $statutsValides = ['EN_ATTENTE', 'ACCEPTEE', 'REFUSEE'];
    if (!in_array($statut, $statutsValides)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Statut invalide']);
        exit;
    }
    
    try {
        // Mise à jour du statut dans la base de données
        $candidatureModel = new \App\Models\CandidatureModel();
        $success = $candidatureModel->updateStatus($candidatureId, $statut);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Statut mis à jour avec succès']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Échec de la mise à jour du statut']);
        }
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()]);
    }
    
    exit;
}


// Ajoutez cette méthode pour gérer la suppression d'une candidature
public function deleteCandidature() {
    // Vérifier si l'utilisateur est connecté et a les droits
    $this->checkGestionAuth();
    
    $candidatureId = isset($_GET['candidature_id']) ? (int)$_GET['candidature_id'] : 0;
    $etudiantId = isset($_GET['etudiant_id']) ? (int)$_GET['etudiant_id'] : 0;
    
    if (!$candidatureId || !$etudiantId) {
        header('Location: /gestion/etudiants/candidatures?id=' . $etudiantId . '&error=3');
        exit;
    }
    
    require_once 'app/models/CandidatureModel.php';
    $candidatureModel = new CandidatureModel();
    
    if ($candidatureModel->deleteCandidature($candidatureId)) {
        header('Location: /gestion/etudiants/candidatures?id=' . $etudiantId . '&success=3');
    } else {
        header('Location: /gestion/etudiants/candidatures?id=' . $etudiantId . '&error=4');
    }
    exit;
}


}
?>