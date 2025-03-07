<?php
require 'validateInput.php';

// Listes des secteurs d'activite et des villes pour générer des entreprises aléatoires
$secteurs = ['Technologie', 'Finance', 'Sante', 'Education', 'Transport', 'Energie', 'Tourisme', 'Alimentation', 'Mode', 'Divertissement'];
$villes = ['Paris', 'Londres', 'New York', 'Berlin', 'Tokyo', 'Madrid', 'Rome', 'Dubai', 'Singapour', 'Toronto'];
$entreprises = [];

// Génération de 50 entreprises aléatoires
for ($i = 1; $i <= 50; $i++) {
    $entreprises[] = [
        'nom' => "Entreprise $i",
        'secteur' => $secteurs[array_rand($secteurs)],
        'ville' => $villes[array_rand($villes)]
    ];
}

$total = count($entreprises);
$perPage = 10;
$totalPages = ceil($total / $perPage);
$page = validatePage($_GET['page'] ?? '1', $totalPages);

$start = ($page - 1) * $perPage;
$entreprisesAffichees = array_slice($entreprises, $start, $perPage);

// Style de la table et des liens de pagination
echo "<style> 
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a { padding: 8px 12px; margin: 0 5px; border: 1px solid black; text-decoration: none; }
        
    </style>";

// Affichage du tableau des entreprises
echo "<table>";
echo "<tr><th>Nom</th><th>Secteur</th><th>Ville</th></tr>";
foreach ($entreprisesAffichees as $entreprise) {
    echo "<tr><td>" . validateInput($entreprise['nom']) . "</td>";
    echo "<td>" . validateInput($entreprise['secteur']) . "</td>";
    echo "<td>" . validateInput($entreprise['ville']) . "</td></tr>";
}
echo "</table>";

// Navigation entre les pages
echo "<div class='pagination'>";
echo "<p> Page $page / $totalPages<";
if ($page > 1) {
    echo '<a href="?page=1"><<</a> ';
    echo '<a href="?page=' . ($page - 1) . '"><</a> ';
}
if ($page < $totalPages) {
    echo '<a href="?page=' . ($page + 1) . '">></a> ';
    echo '<a href="?page=' . $totalPages . '">>></a>';
}
echo "</div>";
?>
