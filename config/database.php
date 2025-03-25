<?php
// Configuration de la base de données
$host = '20.107.81.71'; // hôte de la base de données
$dbname = 'LeBonPlan'; // nom de la base de données
$username = 'G3_Distant'; // votre nom d'utilisateur MySQL
$password = '?LeCrewDuCesi6942'; // votre mot de passe MySQL

// Fonction pour obtenir une connexion PDO
function getDbConnection() {
    global $host, $dbname, $username, $password;
    
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
    
    return $pdo;
}
?>