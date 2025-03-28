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
            // Utiliser la table 'utilisateur' au lieu de 'connexion'
            $stmt = $this->pdo->prepare("
                SELECT u.*, 
                    CASE 
                        WHEN a.id IS NOT NULL THEN 2 
                        WHEN p.id IS NOT NULL THEN 1 
                        WHEN e.id IS NOT NULL THEN 0 
                        ELSE NULL 
                    END as utilisateur
                FROM utilisateur u
                LEFT JOIN administrateur a ON u.id = a.utilisateur_id
                LEFT JOIN pilote p ON u.id = p.utilisateur_id
                LEFT JOIN etudiant e ON u.id = e.utilisateur_id
                WHERE u.email = :email
            ");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Renommer 'mot_de_passe' en 'mdp' pour la compatibilité avec le code existant
            if ($user) {
                $user['mdp'] = $user['mot_de_passe'];
                unset($user['mot_de_passe']);
            }
            
            return $user;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'utilisateur par email: " . $e->getMessage());
            return null;
        }
    }

    public function getAllUsers() {
        try {
            // Utiliser la table 'utilisateur' au lieu de 'connexion'
            $stmt = $this->pdo->query("
                SELECT u.*, 
                    CASE 
                        WHEN a.id IS NOT NULL THEN 2 
                        WHEN p.id IS NOT NULL THEN 1 
                        WHEN e.id IS NOT NULL THEN 0 
                        ELSE NULL 
                    END as utilisateur
                FROM utilisateur u
                LEFT JOIN administrateur a ON u.id = a.utilisateur_id
                LEFT JOIN pilote p ON u.id = p.utilisateur_id
                LEFT JOIN etudiant e ON u.id = e.utilisateur_id
                ORDER BY u.id
            ");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Renommer 'mot_de_passe' en 'mdp' pour chaque utilisateur
            foreach ($users as &$user) {
                $user['mdp'] = $user['mot_de_passe'];
                unset($user['mot_de_passe']);
            }
            
            return $users;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des utilisateurs: " . $e->getMessage());
            return [];
        }
    }

    public function createUser($email, $password, $userType) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Insérer d'abord dans la table utilisateur
            $stmt = $this->pdo->prepare("
                INSERT INTO utilisateur (email, mot_de_passe, nom, prenom)
                VALUES (:email, :mot_de_passe, :nom, :prenom)
            ");
            
            $stmt->execute([
                ':email' => $email,
                ':mot_de_passe' => $hashedPassword,
                ':nom' => 'Nouvel', // Valeurs par défaut, à modifier selon vos besoins
                ':prenom' => 'Utilisateur'
            ]);
            
            $userId = $this->pdo->lastInsertId();
            
            // Ensuite, insérer dans la table correspondant au type d'utilisateur
            if ($userType == 0) { // Étudiant
                $stmt = $this->pdo->prepare("
                    INSERT INTO etudiant (utilisateur_id, promotion, formation)
                    VALUES (:utilisateur_id, :promotion, :formation)
                ");
                $stmt->execute([
                    ':utilisateur_id' => $userId,
                    ':promotion' => 'Promotion ' . (date('Y') + 2), // Exemple: Promotion 2027
                    ':formation' => 'Formation 1'
                ]);
            } elseif ($userType == 1) { // Pilote
                $stmt = $this->pdo->prepare("
                    INSERT INTO pilote (utilisateur_id, departement, specialite)
                    VALUES (:utilisateur_id, :departement, :specialite)
                ");
                $stmt->execute([
                    ':utilisateur_id' => $userId,
                    ':departement' => 'Département 1',
                    ':specialite' => 'Spécialité 1'
                ]);
            } elseif ($userType == 2) { // Administrateur
                $stmt = $this->pdo->prepare("
                    INSERT INTO administrateur (utilisateur_id)
                    VALUES (:utilisateur_id)
                ");
                $stmt->execute([':utilisateur_id' => $userId]);
            }
            
            return $userId;
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    public function updateUser($id, $data) {
        try {
            $query = "UPDATE utilisateur SET ";
            $params = [':id' => $id];
            
            if (isset($data['email'])) {
                $query .= "email = :email, ";
                $params[':email'] = $data['email'];
            }
            
            if (isset($data['password']) && !empty($data['password'])) {
                $query .= "mot_de_passe = :mot_de_passe, ";
                $params[':mot_de_passe'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            
            if (isset($data['nom'])) {
                $query .= "nom = :nom, ";
                $params[':nom'] = $data['nom'];
            }
            
            if (isset($data['prenom'])) {
                $query .= "prenom = :prenom, ";
                $params[':prenom'] = $data['prenom'];
            }
            
            // Supprimer la virgule finale
            $query = rtrim($query, ", ");
            
            $query .= " WHERE id = :id";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            
            // Si le type d'utilisateur a changé, mettre à jour les tables correspondantes
            if (isset($data['utilisateur'])) {
                // Déterminer le type actuel de l'utilisateur
                $currentType = $this->getUserType($id);
                
                if ($currentType != $data['utilisateur']) {
                    // Supprimer les enregistrements des tables de type d'utilisateur
                    $this->pdo->prepare("DELETE FROM etudiant WHERE utilisateur_id = :id")->execute([':id' => $id]);
                    $this->pdo->prepare("DELETE FROM pilote WHERE utilisateur_id = :id")->execute([':id' => $id]);
                    $this->pdo->prepare("DELETE FROM administrateur WHERE utilisateur_id = :id")->execute([':id' => $id]);
                    
                    // Ajouter un nouvel enregistrement selon le nouveau type
                    if ($data['utilisateur'] == 0) { // Étudiant
                        $stmt = $this->pdo->prepare("
                            INSERT INTO etudiant (utilisateur_id, promotion, formation)
                            VALUES (:utilisateur_id, :promotion, :formation)
                        ");
                        $stmt->execute([
                            ':utilisateur_id' => $id,
                            ':promotion' => 'Promotion ' . (date('Y') + 2),
                            ':formation' => 'Formation 1'
                        ]);
                    } elseif ($data['utilisateur'] == 1) { // Pilote
                        $stmt = $this->pdo->prepare("
                            INSERT INTO pilote (utilisateur_id, departement, specialite)
                            VALUES (:utilisateur_id, :departement, :specialite)
                        ");
                        $stmt->execute([
                            ':utilisateur_id' => $id,
                            ':departement' => 'Département 1',
                            ':specialite' => 'Spécialité 1'
                        ]);
                    } elseif ($data['utilisateur'] == 2) { // Administrateur
                        $stmt = $this->pdo->prepare("
                            INSERT INTO administrateur (utilisateur_id)
                            VALUES (:utilisateur_id)
                        ");
                        $stmt->execute([':utilisateur_id' => $id]);
                    }
                }
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    public function deleteUser($id) {
        try {
            // Supprimer d'abord les enregistrements dans les tables liées
            $this->pdo->prepare("DELETE FROM etudiant WHERE utilisateur_id = :id")->execute([':id' => $id]);
            $this->pdo->prepare("DELETE FROM pilote WHERE utilisateur_id = :id")->execute([':id' => $id]);
            $this->pdo->prepare("DELETE FROM administrateur WHERE utilisateur_id = :id")->execute([':id' => $id]);
            
            // Ensuite supprimer l'utilisateur
            $stmt = $this->pdo->prepare("DELETE FROM utilisateur WHERE id = :id");
            $stmt->execute([':id' => $id]);
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }
    
    private function getUserType($userId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    CASE 
                        WHEN a.id IS NOT NULL THEN 2 
                        WHEN p.id IS NOT NULL THEN 1 
                        WHEN e.id IS NOT NULL THEN 0 
                        ELSE NULL 
                    END as utilisateur
                FROM utilisateur u
                LEFT JOIN administrateur a ON u.id = a.utilisateur_id
                LEFT JOIN pilote p ON u.id = p.utilisateur_id
                LEFT JOIN etudiant e ON u.id = e.utilisateur_id
                WHERE u.id = :id
            ");
            $stmt->execute([':id' => $userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? $result['utilisateur'] : null;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du type d'utilisateur: " . $e->getMessage());
            return null;
        }
    }
}