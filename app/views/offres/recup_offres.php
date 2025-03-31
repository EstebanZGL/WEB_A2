<?php
// Utiliser la fonction de connexion existante
require_once '../../config/database.php';
$pdo = getDbConnection();

// Récupération des filtres et paramètres de recherche
$search = $_GET['search'] ?? '';
$location = $_GET['location'] ?? '';
$filters = isset($_GET['filters']) ? json_decode($_GET['filters'], true) : [];

// Création de la requête SQL dynamique
$query = "SELECT o.*, e.nom as entreprise FROM offre_stage o 
          LEFT JOIN entreprise e ON o.entreprise_id = e.id
          WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (o.titre LIKE :search OR o.description LIKE :search)";
    $params[':search'] = "%$search%";
}

if (!empty($location)) {
    $query .= " AND e.nom LIKE :location";
    $params[':location'] = "%$location%";
}

// Gestion des filtres de salaire
if (!empty($filters['salary'])) {
    foreach ($filters['salary'] as $range) {
        if ($range === '0-50000') {
            $query .= " AND o.remuneration BETWEEN 0 AND 50000";
        } elseif ($range === '50000-100000') {
            $query .= " AND o.remuneration BETWEEN 50000 AND 100000";
        } elseif ($range === '100000+') {
            $query .= " AND o.remuneration >= 100000";
        }
    }
}

// Trier par date de publication décroissante
$query .= " ORDER BY o.date_publication DESC";

// Préparer et exécuter la requête
$stmt = $pdo->prepare($query);
$stmt->execute($params);

// Récupérer les compétences pour chaque offre
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($jobs as &$job) {
    // Récupérer les compétences associées à cette offre
    $stmt = $pdo->prepare("
        SELECT c.nom
        FROM offre_competence oc
        JOIN competence c ON oc.competence_id = c.id
        WHERE oc.offre_id = :offre_id
    ");
    $stmt->execute([':offre_id' => $job['id']]);
    $competences = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $job['competences'] = implode(', ', $competences);
    
    // Compter le nombre de candidatures pour cette offre
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM candidature 
        WHERE offre_id = :offre_id
    ");
    $stmt->execute([':offre_id' => $job['id']]);
    $job['nb_postulants'] = $stmt->fetchColumn();
}

// Renvoyer les résultats au format JSON
header('Content-Type: application/json');
echo json_encode($jobs);
?>