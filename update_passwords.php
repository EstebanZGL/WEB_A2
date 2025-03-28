<?php
// Connexion à la base de données
require_once 'config/database.php';
$db = getDbConnection();

// Récupérer tous les utilisateurs
$sql = "SELECT id, mot_de_passe FROM utilisateur";
$stmt = $db->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Générer les requêtes SQL pour mettre à jour les mots de passe
echo "-- Requêtes SQL pour mettre à jour les mots de passe existants\n";
foreach ($users as $user) {
    $hashedPassword = password_hash($user['mot_de_passe'], PASSWORD_DEFAULT);
    echo "UPDATE utilisateur SET mot_de_passe = '$hashedPassword' WHERE id = {$user['id']};\n";
}

// Générer le mot de passe hashé pour le nouvel administrateur
$adminPassword = 'admin2025';
$hashedAdminPassword = password_hash($adminPassword, PASSWORD_DEFAULT);

// Générer les requêtes SQL pour créer l'administrateur
echo "\n-- Requêtes SQL pour créer l'administrateur\n";
echo "INSERT INTO utilisateur (email, mot_de_passe, nom, prenom) VALUES ('admin@cesi.fr', '$hashedAdminPassword', 'Admin', 'Système');\n";
echo "INSERT INTO administrateur (utilisateur_id) VALUES (LAST_INSERT_ID());\n";

echo "\n-- Pour vérifier que l'administrateur a été créé correctement\n";
echo "SELECT u.*, a.id as admin_id FROM utilisateur u JOIN administrateur a ON u.id = a.utilisateur_id WHERE u.email = 'admin@cesi.fr';\n";
?>