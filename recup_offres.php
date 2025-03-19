<?php
// Connexion à la base de données (modifie les valeurs en fonction de ta configuration)
$host = '20.107.81.71'; // hôte de la base de données
$dbname = 'LeBonPlan'; // nom de la base de données
$username = 'G3_Distant'; // votre nom d'utilisateur MySQL
$password = '?LeCrewDuCesi6942'; // votre mot de passe MySQL


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des filtres et paramètres de recherche
$search = $_GET['search'] ?? '';
$location = $_GET['location'] ?? '';
$filters = $_GET['filters'] ?? [];

// Création de la requête SQL dynamique
$query = "SELECT * FROM offres WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (titre LIKE :search OR description LIKE :search OR entreprise LIKE :search)";
    $params[':search'] = "%$search%";
}

if (!empty($location)) {
    $query .= " AND entreprise LIKE :location"; // Supposons que l'entreprise reflète la localisation
    $params[':location'] = "%$location%";
}

if (!empty($filters['jobType'])) {
    $placeholders = implode(',', array_fill(0, count($filters['jobType']), '?'));
    $query .= " AND type_emploi IN ($placeholders)";
    $params = array_merge($params, $filters['jobType']);
}

if (!empty($filters['experienceLevel'])) {
    $placeholders = implode(',', array_fill(0, count($filters['experienceLevel']), '?'));
    $query .= " AND experience IN ($placeholders)";
    $params = array_merge($params, $filters['experienceLevel']);
}

if (!empty($filters['salary'])) {
    foreach ($filters['salary'] as $range) {
        if ($range === '$0-$50K') {
            $query .= " AND remuneration BETWEEN 0 AND 50000";
        } elseif ($range === '$50K-$100K') {
            $query .= " AND remuneration BETWEEN 50000 AND 100000";
        } elseif ($range === '$100K-$150K') {
            $query .= " AND remuneration BETWEEN 100000 AND 150000";
        } elseif ($range === '$150K+') {
            $query .= " AND remuneration >= 150000";
        }
    }
}

// Préparer et exécuter la requête
$stmt = $pdo->prepare($query);
$stmt->execute($params);

// Renvoyer les résultats au format JSON
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($jobs);
?>
