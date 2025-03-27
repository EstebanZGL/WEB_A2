<?php

require_once 'config/database.php';

class WishlistModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Récupère tous les éléments de la wishlist d'un utilisateur
     * @param int $userId ID de l'utilisateur
     * @return array Liste des éléments de la wishlist
     */
    public function getWishlistByUserId($userId) {
        $this->db->query("
            SELECT os.*, e.nom as entreprise, 
            GROUP_CONCAT(c.nom SEPARATOR ', ') as competences
            FROM wishlist w
            JOIN etudiant et ON w.etudiant_id = et.id
            JOIN utilisateur u ON et.utilisateur_id = u.id
            JOIN offre_stage os ON w.offre_id = os.id
            JOIN entreprise e ON os.entreprise_id = e.id
            LEFT JOIN offre_competence oc ON os.id = oc.offre_id
            LEFT JOIN competence c ON oc.competence_id = c.id
            WHERE u.id = :user_id
            GROUP BY os.id
            ORDER BY w.date_ajout DESC
        ");
        
        $this->db->bind(':user_id', $userId);
        
        return $this->db->resultSet();
    }
    /**
     * Ajoute un élément à la wishlist
     * @param int $etudiantId ID de l'étudiant
     * @param int $offreId ID de l'offre à ajouter
     * @return bool Succès de l'opération
     */
    public function addToWishlist($etudiantId, $offreId) {
        // Vérifier si l'offre est déjà dans la wishlist
        $this->db->query("SELECT id FROM wishlist WHERE etudiant_id = :etudiant_id AND offre_id = :offre_id");
        $this->db->bind(':etudiant_id', $etudiantId);
        $this->db->bind(':offre_id', $offreId);
        
        $existing = $this->db->single();
        
        if ($existing) {
            return false; // L'offre est déjà dans la wishlist
        }
        
        // Ajouter l'offre à la wishlist
        $this->db->query("INSERT INTO wishlist (etudiant_id, offre_id, date_ajout) VALUES (:etudiant_id, :offre_id, NOW())");
        $this->db->bind(':etudiant_id', $etudiantId);
        $this->db->bind(':offre_id', $offreId);
        
        return $this->db->execute();
    }
    /**
     * Supprime un élément de la wishlist
     * @param int $etudiantId ID de l'étudiant
     * @param int $offreId ID de l'offre à supprimer
     * @return bool Succès de l'opération
     */
    public function removeFromWishlist($etudiantId, $offreId) {
        $this->db->query("DELETE FROM wishlist WHERE etudiant_id = :etudiant_id AND offre_id = :offre_id");
        $this->db->bind(':etudiant_id', $etudiantId);
        $this->db->bind(':offre_id', $offreId);
        
        return $this->db->execute();
    }

    /**
     * Récupère l'ID de l'étudiant à partir de l'ID de l'utilisateur
     * @param int $userId ID de l'utilisateur
     * @return int|null ID de l'étudiant ou null si non trouvé
     */
    public function getEtudiantIdByUserId($userId) {
        $this->db->query("SELECT id FROM etudiant WHERE utilisateur_id = :user_id");
        $this->db->bind(':user_id', $userId);
        
        $result = $this->db->single();
        return $result ? $result->id : null;
    }
}
