<?php

class CandidatureModel {
    private $db;

    public function __construct() {
        require_once 'config/database.php';
        $this->db = getDbConnection();
    }

    /**
     * Récupère les informations d'un étudiant par son ID
     */
    public function getEtudiantInfo($id) {
        try {
            $query = "SELECT e.*, u.nom, u.prenom, u.email
                      FROM etudiant e
                      JOIN utilisateur u ON e.utilisateur_id = u.id
                      WHERE e.id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des informations de l'étudiant: " . $e->getMessage());
            return null;
        }
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
     * Récupère les candidatures d'un étudiant par son ID
     */
    public function getCandidaturesByEtudiantId($etudiantId) {
        try {
            $query = "SELECT c.*, 
                      o.titre as offre_titre, 
                      o.type as offre_type, 
                      o.lieu as offre_lieu,
                      e.nom as entreprise_nom 
                      FROM candidature c
                      JOIN offre_stage o ON c.offre_id = o.id
                      JOIN entreprise e ON o.entreprise_id = e.id
                      WHERE c.etudiant_id = :etudiant_id
                      ORDER BY c.date_candidature DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['etudiant_id' => $etudiantId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des candidatures: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère la wishlist d'un étudiant par son ID
     */
    public function getWishlistByEtudiantId($etudiantId) {
        try {
            $query = "SELECT w.*,
                      o.titre as offre_titre, 
                      o.type as offre_type, 
                      o.lieu as offre_lieu,
                      o.statut as offre_statut,
                      e.nom as entreprise_nom 
                      FROM wishlist w
                      JOIN offre_stage o ON w.offre_id = o.id
                      JOIN entreprise e ON o.entreprise_id = e.id
                      WHERE w.etudiant_id = :etudiant_id
                      ORDER BY w.date_ajout DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['etudiant_id' => $etudiantId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de la wishlist: " . $e->getMessage());
            return [];
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
public function createCandidature($etudiantId, $offreId, $cvPath = null, $lettreMotivation = null) {
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
 * Ajoute une candidature (méthode utilisée par l'interface d'administration)
 */
public function addCandidature($etudiantId, $offreId, $statut = 'EN_ATTENTE') {
    try {
        $stmt = $this->db->prepare(
            "INSERT INTO candidature (etudiant_id, offre_id, date_candidature, statut) 
            VALUES (?, ?, NOW(), ?)"
        );
        
        return $stmt->execute([$etudiantId, $offreId, $statut]);
    } catch (PDOException $e) {
        error_log("Erreur lors de l'ajout de la candidature: " . $e->getMessage());
        return false;
    }
}

 
    /**
     * Supprime une candidature
     */
    public function deleteCandidature($candidatureId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM candidature WHERE id = ?");
            return $stmt->execute([$candidatureId]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de la candidature: " . $e->getMessage());
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

    public function updateStatus($candidatureId, $statut)
    {
        try {
            $stmt = $this->db->prepare("UPDATE candidature SET statut = ? WHERE id = ?");
            return $stmt->execute([$statut, $candidatureId]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour du statut de candidature: " . $e->getMessage());
            return false;
        }
    }
} 