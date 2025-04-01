<?php

class CandidatureModel {
    private $db;
    
    public function __construct() {
        require_once 'config/database.php';
        $this->db = getDbConnection();
    }
    
    /**
     * Récupère toutes les candidatures d'un étudiant
     */
    public function getCandidaturesByEtudiantId($etudiantId) {
        $query = "SELECT c.*, o.titre as offre_titre, o.type as offre_type, o.lieu as offre_lieu, 
                 o.remuneration, o.duree_stage, o.date_debut, o.date_fin, o.statut as offre_statut,
                 e.nom as entreprise_nom
                 FROM candidature c
                 JOIN offre_stage o ON c.offre_id = o.id
                 JOIN entreprise e ON o.entreprise_id = e.id
                 WHERE c.etudiant_id = :etudiant_id
                 ORDER BY c.date_candidature DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':etudiant_id', $etudiantId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère les informations de l'étudiant pour afficher en haut de la page de candidatures
     */
    public function getEtudiantInfo($etudiantId) {
        $query = "SELECT e.*, u.nom, u.prenom, u.email
                 FROM etudiant e
                 JOIN utilisateur u ON e.utilisateur_id = u.id
                 WHERE e.id = :etudiant_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':etudiant_id', $etudiantId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère les offres de la wishlist d'un étudiant
     */
    public function getWishlistByEtudiantId($etudiantId) {
        $query = "SELECT w.*, o.titre as offre_titre, o.type as offre_type, o.lieu as offre_lieu,
                 o.remuneration, o.duree_stage, o.date_debut, o.date_fin, o.statut as offre_statut,
                 e.nom as entreprise_nom
                 FROM wishlist w
                 JOIN offre_stage o ON w.offre_id = o.id
                 JOIN entreprise e ON o.entreprise_id = e.id
                 WHERE w.etudiant_id = :etudiant_id
                 ORDER BY w.date_ajout DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':etudiant_id', $etudiantId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Ajoute une candidature pour un étudiant
     */
    public function addCandidature($etudiantId, $offreId, $statut = 'En attente') {
        // Vérifier si la candidature existe déjà
        $checkQuery = "SELECT id FROM candidature 
                      WHERE etudiant_id = :etudiant_id AND offre_id = :offre_id";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->bindParam(':etudiant_id', $etudiantId, PDO::PARAM_INT);
        $checkStmt->bindParam(':offre_id', $offreId, PDO::PARAM_INT);
        $checkStmt->execute();
        
        if ($checkStmt->fetch()) {
            return false; // La candidature existe déjà
        }
        
        $query = "INSERT INTO candidature (etudiant_id, offre_id, statut, date_candidature) 
                 VALUES (:etudiant_id, :offre_id, :statut, NOW())";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':etudiant_id', $etudiantId, PDO::PARAM_INT);
        $stmt->bindParam(':offre_id', $offreId, PDO::PARAM_INT);
        $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    /**
     * Met à jour le statut d'une candidature
     */
    public function updateCandidatureStatus($candidatureId, $statut) {
        $query = "UPDATE candidature SET statut = :statut WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $candidatureId, PDO::PARAM_INT);
        $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    /**
     * Supprime une candidature
     */
    public function deleteCandidature($candidatureId) {
        $query = "DELETE FROM candidature WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $candidatureId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Compte le nombre de candidatures pour un étudiant
     */
    public function countCandidaturesByEtudiantId($etudiantId) {
        $query = "SELECT COUNT(*) as total FROM candidature WHERE etudiant_id = :etudiant_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':etudiant_id', $etudiantId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)$result['total'];
    }
}