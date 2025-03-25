<?php
require_once 'config/database.php';

class UserModel {
    private $pdo;

    public function __construct() {
        // Utiliser la fonction getDbConnection pour obtenir la connexion PDO
        $this->pdo = getDbConnection();
    }

    public function getUserByEmail($email) {
        try {
            // Selon votre structure de base de données, les utilisateurs sont dans la table 'connexion'
            $stmt = $this->pdo->prepare("SELECT * FROM connexion WHERE email = :email");
            $stmt->execute([':email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'utilisateur par email: " . $e->getMessage());
            return null;
        }
    }

    public function getAllUsers() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM connexion ORDER BY id");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des utilisateurs: " . $e->getMessage());
            return [];
        }
    }

    public function createUser($email, $password, $userType) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $this->pdo->prepare("
                INSERT INTO connexion (email, mdp, utilisateur)
                VALUES (:email, :mdp, :utilisateur)
            ");
            
            $stmt->execute([
                ':email' => $email,
                ':mdp' => $hashedPassword,
                ':utilisateur' => $userType
            ]);
            
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    public function updateUser($id, $data) {
        try {
            $query = "UPDATE connexion SET ";
            $params = [':id' => $id];
            
            if (isset($data['email'])) {
                $query .= "email = :email, ";
                $params[':email'] = $data['email'];
            }
            
            if (isset($data['password']) && !empty($data['password'])) {
                $query .= "mdp = :mdp, ";
                $params[':mdp'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            
            if (isset($data['utilisateur'])) {
                $query .= "utilisateur = :utilisateur, ";
                $params[':utilisateur'] = $data['utilisateur'];
            }
            
            // Supprimer la virgule finale
            $query = rtrim($query, ", ");
            
            $query .= " WHERE id = :id";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    public function deleteUser($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM connexion WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }
}
?>