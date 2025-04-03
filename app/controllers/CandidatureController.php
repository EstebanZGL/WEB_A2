<?php

require_once 'app/models/CandidatureModel.php';

class CandidatureController {
    private $candidatureModel;
    
    public function __construct() {
        $this->candidatureModel = new CandidatureModel();
        
        // Démarrer la session si elle n'est pas déjà démarrée
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Vérifie si l'utilisateur est connecté en tant qu'étudiant
     */
    private function isLoggedInAsStudent() {
        return isset($_SESSION['logged_in']) && 
               $_SESSION['logged_in'] === true && 
               isset($_SESSION['utilisateur']) && 
               $_SESSION['utilisateur'] == 0; // 0 = étudiant
    }
    
    /**
     * Vérifie si l'étudiant a déjà postulé à cette offre
     */
    public function hasAlreadyApplied($offreId) {
        if (!$this->isLoggedInAsStudent() || !isset($_SESSION['user_id'])) {
            return false;
        }
        
        $etudiantId = $this->candidatureModel->getEtudiantIdByUserId($_SESSION['user_id']);
        if (!$etudiantId) {
            return false;
        }
        
        return $this->candidatureModel->hasApplied($etudiantId, $offreId);
    }
    
    /**
     * Récupère les détails d'une offre par son ID
     */
    public function getOffreDetails($offreId) {
        require_once 'app/models/OffreModel.php';
        $offreModel = new OffreModel();
        return $offreModel->getOffreById($offreId);
    }
    
    /**
     * Traite la soumission d'une candidature
     */
    public function submitCandidature($offreId) {
        // Vérifier que l'utilisateur est connecté en tant qu'étudiant
        if (!$this->isLoggedInAsStudent()) {
            return [
                'success' => false,
                'message' => 'Vous devez être connecté en tant qu\'étudiant pour postuler.'
            ];
        }
        
        // Récupérer l'ID de l'étudiant
        $etudiantId = $this->candidatureModel->getEtudiantIdByUserId($_SESSION['user_id']);
        if (!$etudiantId) {
            return [
                'success' => false,
                'message' => 'Profil étudiant non trouvé.'
            ];
        }
        
        // Vérifier si l'étudiant a déjà postulé
        if ($this->candidatureModel->hasApplied($etudiantId, $offreId)) {
            return [
                'success' => false,
                'message' => 'Vous avez déjà postulé à cette offre.'
            ];
        }
        
        // Vérifier si un fichier a été téléchargé
        if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'message' => 'Erreur lors du téléchargement du CV. Veuillez réessayer.'
            ];
        }
        
        // Vérifier le type de fichier
        $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $fileType = $_FILES['cv']['type'];
        
        if (!in_array($fileType, $allowedTypes)) {
            return [
                'success' => false,
                'message' => 'Format de fichier non autorisé. Veuillez télécharger un fichier PDF ou Word.'
            ];
        }
        
        // Limiter la taille du fichier (5 MB)
        if ($_FILES['cv']['size'] > 5 * 1024 * 1024) {
            return [
                'success' => false,
                'message' => 'Le fichier est trop volumineux. Taille maximale: 5 MB.'
            ];
        }
        
        // Créer un nom unique pour le fichier
        $fileName = 'etudiant_' . $etudiantId . '_offre_' . $offreId . '_' . date('Ymd_His') . '_' . pathinfo($_FILES['cv']['name'], PATHINFO_FILENAME);
        
        // Ajouter l'extension appropriée basée sur le type MIME
        if ($fileType === 'application/pdf') {
            $fileName .= '.pdf';
        } else if ($fileType === 'application/msword') {
            $fileName .= '.doc';
        } else {
            $fileName .= '.docx';
        }
        
        // Définir le chemin de destination
        $destination = '/uploads/' . $fileName;
        
        // Déplacer le fichier téléchargé vers le dossier uploads
        if (!move_uploaded_file($_FILES['cv']['tmp_name'], $destination)) {
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement du fichier. Veuillez réessayer.'
            ];
        }
        
        // Récupérer la lettre de motivation
        $lettreMotivation = isset($_POST['lettre_motivation']) ? trim($_POST['lettre_motivation']) : '';
        
        // Sauvegarder les informations de candidature dans la base de données
        $result = $this->candidatureModel->createCandidature($etudiantId, $offreId, $destination, $lettreMotivation);
        
        if ($result) {
            return [
                'success' => true,
                'message' => 'Votre candidature a été envoyée avec succès. Vous serez notifié de la suite donnée à votre demande.'
            ];
        } else {
            // En cas d'échec, supprimer le fichier téléchargé
            if (file_exists($destination)) {
                unlink($destination);
            }
            
            return [
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'enregistrement de votre candidature. Veuillez réessayer.'
            ];
        }
    }
}