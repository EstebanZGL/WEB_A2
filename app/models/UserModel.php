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

    /**
     * Crée un utilisateur de base (fonction interne)
     */
    private function createBaseUser($email, $password, $nom, $prenom) {
        try {
            // Insérer dans la table utilisateur
            $stmt = $this->pdo->prepare("
                INSERT INTO utilisateur (email, mot_de_passe, nom, prenom, date_creation)
                VALUES (:email, :mot_de_passe, :nom, :prenom, :date_creation)
            ");
            
            $stmt->execute([
                ':email' => $email,
                ':mot_de_passe' => $password, // Le mot de passe est déjà haché dans le contrôleur
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':date_creation' => date('Y-m-d H:i:s')
            ]);
            
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de l'utilisateur de base: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crée un étudiant (utilisateur + entrée dans la table étudiant)
     */
    public function createEtudiant($email, $password, $nom, $prenom, $promotion = '', $formation = '', $offre_id = null) {
        try {
            // Vérifier si l'email existe déjà
            $stmt = $this->pdo->prepare("SELECT id FROM utilisateur WHERE email = :email");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                error_log("Erreur: L'email $email existe déjà dans la base de données");
                return false;
            }
            
            // Vérifier la structure de la table utilisateur
            $stmt = $this->pdo->query("DESCRIBE utilisateur");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            error_log("Colonnes de la table utilisateur: " . implode(", ", $columns));
            
            $hasDateCreation = in_array('date_creation', $columns);
            
            $this->pdo->beginTransaction();
            
            // Créer l'utilisateur de base
            if ($hasDateCreation) {
                $stmt = $this->pdo->prepare("
                    INSERT INTO utilisateur (email, mot_de_passe, nom, prenom, date_creation)
                    VALUES (:email, :mot_de_passe, :nom, :prenom, :date_creation)
                ");
                
                $params = [
                    ':email' => $email,
                    ':mot_de_passe' => $password,
                    ':nom' => $nom,
                    ':prenom' => $prenom,
                    ':date_creation' => date('Y-m-d H:i:s')
                ];
            } else {
                $stmt = $this->pdo->prepare("
                    INSERT INTO utilisateur (email, mot_de_passe, nom, prenom)
                    VALUES (:email, :mot_de_passe, :nom, :prenom)
                ");
                
                $params = [
                    ':email' => $email,
                    ':mot_de_passe' => $password,
                    ':nom' => $nom,
                    ':prenom' => $prenom
                ];
            }
            
            error_log("Tentative d'insertion utilisateur avec les paramètres: " . print_r($params, true));
            
            if (!$stmt->execute($params)) {
                $errorInfo = $stmt->errorInfo();
                error_log("Erreur SQL lors de l'insertion de l'utilisateur: " . print_r($errorInfo, true));
                $this->pdo->rollBack();
                return false;
            }
            
            $userId = $this->pdo->lastInsertId();
            error_log("Utilisateur créé avec ID: $userId");
            
            if (!$userId) {
                error_log("Erreur: Impossible d'obtenir l'ID de l'utilisateur après insertion");
                $this->pdo->rollBack();
                return false;
            }
            
            // Définir les valeurs par défaut si nécessaire
            $promotion = $promotion ?: ('Promotion ' . (date('Y') + 2));
            $formation = $formation ?: 'Formation 1';
            
            // Vérifier si offre_id est vide
            if (empty($offre_id)) {
                $stmt = $this->pdo->prepare("
                    INSERT INTO etudiant (utilisateur_id, promotion, formation)
                    VALUES (:utilisateur_id, :promotion, :formation)
                ");
                
                $params = [
                    ':utilisateur_id' => $userId,
                    ':promotion' => $promotion,
                    ':formation' => $formation
                ];
            } else {
                $stmt = $this->pdo->prepare("
                    INSERT INTO etudiant (utilisateur_id, promotion, formation, offre_id)
                    VALUES (:utilisateur_id, :promotion, :formation, :offre_id)
                ");
                
                $params = [
                    ':utilisateur_id' => $userId,
                    ':promotion' => $promotion,
                    ':formation' => $formation,
                    ':offre_id' => intval($offre_id)
                ];
            }
            
            error_log("Exécution de la requête d'insertion d'étudiant avec les paramètres: " . print_r($params, true));
            
            if (!$stmt->execute($params)) {
                $errorInfo = $stmt->errorInfo();
                error_log("Erreur SQL lors de l'insertion de l'étudiant: " . print_r($errorInfo, true));
                $this->pdo->rollBack();
                return false;
            }
            
            $this->pdo->commit();
            error_log("Étudiant créé avec succès: ID utilisateur=$userId");
            return $userId;
        } catch (PDOException $e) {
            error_log("Exception PDO lors de la création de l'étudiant: " . $e->getMessage());
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        } catch (Exception $e) {
            error_log("Exception générale lors de la création de l'étudiant: " . $e->getMessage());
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        }
    }

    /**
     * Crée un pilote (utilisateur + entrée dans la table pilote)
     */
    public function createPilote($email, $password, $nom, $prenom, $departement = '', $specialite = '') {
        try {
            $this->pdo->beginTransaction();
            
            // Créer l'utilisateur de base
            $userId = $this->createBaseUser($email, $password, $nom, $prenom);
            
            if (!$userId) {
                $this->pdo->rollBack();
                return false;
            }
            
            // Créer l'entrée dans la table pilote
            $stmt = $this->pdo->prepare("
                INSERT INTO pilote (utilisateur_id, departement, specialite)
                VALUES (:utilisateur_id, :departement, :specialite)
            ");
            
            $stmt->execute([
                ':utilisateur_id' => $userId,
                ':departement' => $departement ?: 'Département 1',
                ':specialite' => $specialite ?: 'Spécialité 1'
            ]);
            
            $this->pdo->commit();
            return $userId;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Erreur lors de la création du pilote: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crée un administrateur (utilisateur + entrée dans la table administrateur)
     */
    public function createAdmin($email, $password, $nom, $prenom) {
        try {
            $this->pdo->beginTransaction();
            
            // Créer l'utilisateur de base
            $userId = $this->createBaseUser($email, $password, $nom, $prenom);
            
            if (!$userId) {
                $this->pdo->rollBack();
                return false;
            }
            
            // Créer l'entrée dans la table administrateur
            $stmt = $this->pdo->prepare("
                INSERT INTO administrateur (utilisateur_id)
                VALUES (:utilisateur_id)
            ");
            
            $stmt->execute([':utilisateur_id' => $userId]);
            
            $this->pdo->commit();
            return $userId;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Erreur lors de la création de l'administrateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fonction de compatibilité pour l'ancien code et le nouveau code
     * Cette fonction peut être appelée de différentes manières :
     * - createUser($email, $password, $nom, $prenom) : pour créer un étudiant (utilisé dans GestionController)
     * - createUser($email, $password, $userType) : pour créer un utilisateur selon son type (ancien code)
     */
    public function createUser($email, $password, $nomOrType = 'Nouvel', $prenom = 'Utilisateur') {
        // Déterminer si le troisième paramètre est un type d'utilisateur ou un nom
        if (is_numeric($nomOrType) && ($nomOrType == 0 || $nomOrType == 1 || $nomOrType == 2)) {
            // C'est un type d'utilisateur (ancien format)
            $userType = $nomOrType;
            $nom = 'Nouvel';
            
            switch ($userType) {
                case 0: // Étudiant
                    return $this->createEtudiant($email, $password, $nom, $prenom);
                case 1: // Pilote
                    return $this->createPilote($email, $password, $nom, $prenom);
                case 2: // Administrateur
                    return $this->createAdmin($email, $password, $nom, $prenom);
                default:
                    return false;
            }
        } else {
            // C'est un nom (nouveau format utilisé dans GestionController)
            $nom = $nomOrType;
            // Par défaut, créer un étudiant
            return $this->createEtudiant($email, $password, $nom, $prenom);
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