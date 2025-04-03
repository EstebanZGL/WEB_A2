// Méthode pour récupérer la liste des villes disponibles
public function getAvailableCities() {
    try {
        // Récupérer les villes distinctes des offres
        $stmt = $this->pdo->query("
            SELECT DISTINCT lieu as ville 
            FROM offre_stage 
            WHERE lieu IS NOT NULL 
            AND lieu != '' 
            ORDER BY lieu ASC
        ");
        
        // Debug: Afficher la requête SQL
        error_log("Requête SQL pour les villes: " . "SELECT DISTINCT lieu as ville FROM offre_stage WHERE lieu IS NOT NULL AND lieu != '' ORDER BY lieu ASC");
        $cities = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
        // Debug: Afficher le nombre de villes trouvées
        error_log("Nombre de villes trouvées: " . count($cities));
        error_log("Villes trouvées: " . print_r($cities, true));
        
        return $cities;
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des villes: " . $e->getMessage());
        return [];
    }
}
