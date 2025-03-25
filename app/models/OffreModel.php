<?php
require_once 'config/database.php';

class OffreModel {
    private $pdo;

    public function __construct() {
        // Utiliser la fonction getDbConnection pour obtenir la connexion PDO
        $this->pdo = getDbConnection();
    }

    public function getAllOffres() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM offres ORDER BY date_offre DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des offres: " . $e->getMessage());
            return [];
        }
    }

    public function searchOffres($jobTitle = '', $location = '') {
        try {
            $query = "SELECT * FROM offres WHERE 1=1";
            $params = [];

            // Ajouter des conditions de recherche si elles sont fournies
            if (!empty($jobTitle)) {
                $query .= " AND (titre LIKE :jobTitle OR description LIKE :jobTitle OR competences LIKE :jobTitle)";
                $params[':jobTitle'] = "%$jobTitle%";
            }

            if (!empty($location)) {
                $query .= " AND entreprise LIKE :location";
                $params[':location'] = "%$location%";
            }

            // Trier par date décroissante
            $query .= " ORDER BY date_offre DESC";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la recherche d'offres: " . $e->getMessage());
            return [];
        }
    }

    public function getOffreById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM offres WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'offre: " . $e->getMessage());
            return null;
        }
    }

    public function createOffre($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO offres (entreprise, titre, description, competences, remuneration, date_offre, nb_postulants)
                VALUES (:entreprise, :titre, :description, :competences, :remuneration, :date_offre, :nb_postulants)
            ");
            
            $stmt->execute([
                ':entreprise' => $data['entreprise'],
                ':titre' => $data['titre'],
                ':description' => $data['description'],
                ':competences' => $data['competences'],
                ':remuneration' => $data['remuneration'],
                ':date_offre' => $data['date_offre'] ?? date('Y-m-d'),
                ':nb_postulants' => $data['nb_postulants'] ?? 0
            ]);
            
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de l'offre: " . $e->getMessage());
            return false;
        }
    }

    public function updateOffre($id, $data) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE offres SET 
                entreprise = :entreprise,
                titre = :titre,
                description = :description,
                competences = :competences,
                remuneration = :remuneration,
                date_offre = :date_offre,
                nb_postulants = :nb_postulants
                WHERE id = :id
            ");
            
            $stmt->execute([
                ':id' => $id,
                ':entreprise' => $data['entreprise'],
                ':titre' => $data['titre'],
                ':description' => $data['description'],
                ':competences' => $data['competences'],
                ':remuneration' => $data['remuneration'],
                ':date_offre' => $data['date_offre'],
                ':nb_postulants' => $data['nb_postulants']
            ]);
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de l'offre: " . $e->getMessage());
            return false;
        }
    }

    public function deleteOffre($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM offres WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de l'offre: " . $e->getMessage());
            return false;
        }
    }
}
?>