<?php

class CandidatureModel {
    private $db;

    public function __construct() {
        require_once 'config/database.php';
        $this->db = getDbConnection();
    }

    /**
     * Récupère l'ID de l'étudiant basé sur l'ID de l'utilisateur
     */
    public function getEtudiantIdByUserId($userId) {
        try {
            $stmt = $this->db->prepare("SELECT id FROM etudiant WHERE utilisateur_id = ?");
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? $result['id'] : null;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'ID étudiant: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Vérifie si l'étudiant a déjà postulé à cette offre
     */
    public function hasApplied($etudiantId, $offreId) {
        try {
            $stmt = $this->db->prepare("SELECT id FROM candidature WHERE etudiant_id = ? AND offre_id = ?");
            $stmt->execute([$etudiantId, $offreId]);
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification de candidature existante: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Crée une nouvelle candidature
     */
    public function createCandidature($etudiantId, $offreId, $cvPath, $lettreMotivation) {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO candidature (etudiant_id, offre_id, date_candidature, cv_path, lettre_motivation, statut) 
                VALUES (?, ?, NOW(), ?, ?, 'EN_ATTENTE')"
            );
            
            return $stmt->execute([$etudiantId, $offreId, $cvPath, $lettreMotivation]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de la candidature: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère les offres disponibles pour candidature
     */
    public function getAvailableOffres() {
        try {
            $stmt = $this->db->prepare(
                "SELECT o.*, e.nom as entreprise 
                FROM offre_stage o 
                JOIN entreprise e ON o.entreprise_id = e.id 
                WHERE o.statut = 'ACTIVE'"
            );
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des offres disponibles: " . $e->getMessage());
            return [];
        }
    }
}