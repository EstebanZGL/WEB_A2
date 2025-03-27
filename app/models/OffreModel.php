class OffreModel {
    private $db;

    public function __construct() {
        $this->db = getDbConnection();
    }

    public function getAllOffres() {
        $sql = "
            SELECT os.*, e.nom as entreprise, 
            GROUP_CONCAT(c.nom SEPARATOR ', ') as competences,
            (SELECT COUNT(*) FROM candidature WHERE offre_id = os.id) as nb_postulants
            FROM offre_stage os
            JOIN entreprise e ON os.entreprise_id = e.id
            LEFT JOIN offre_competence oc ON os.id = oc.offre_id
            LEFT JOIN competence c ON oc.competence_id = c.id
            WHERE os.statut = 'ACTIVE'
            GROUP BY os.id
            ORDER BY os.date_publication DESC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function searchOffres($params = []) {
        $sql = "
            SELECT os.*, e.nom as entreprise, 
            GROUP_CONCAT(c.nom SEPARATOR ', ') as competences,
            (SELECT COUNT(*) FROM candidature WHERE offre_id = os.id) as nb_postulants
            FROM offre_stage os
            JOIN entreprise e ON os.entreprise_id = e.id
            LEFT JOIN offre_competence oc ON os.id = oc.offre_id
            LEFT JOIN competence c ON oc.competence_id = c.id
            WHERE os.statut = 'ACTIVE'
        ";

        $conditions = [];
        $parameters = [];

        if (!empty($params['jobTitle'])) {
            $conditions[] = "(os.titre LIKE ? OR os.description LIKE ? OR e.nom LIKE ?)";
            $searchTerm = '%' . $params['jobTitle'] . '%';
            $parameters[] = $searchTerm;
            $parameters[] = $searchTerm;
            $parameters[] = $searchTerm;
        }

        if (!empty($params['location'])) {
            $conditions[] = "(e.nom LIKE ? OR e.adresse LIKE ?)";
            $searchTerm = '%' . $params['location'] . '%';
            $parameters[] = $searchTerm;
            $parameters[] = $searchTerm;
        }

        if (!empty($params['filters']['salary'])) {
            $salaryConditions = [];
            foreach ($params['filters']['salary'] as $salaryRange) {
                if ($salaryRange == '0-50000') {
                    $salaryConditions[] = "(os.remuneration BETWEEN 0 AND 50000)";
                } elseif ($salaryRange == '50000-100000') {
                    $salaryConditions[] = "(os.remuneration BETWEEN 50000 AND 100000)";
                } elseif ($salaryRange == '100000+') {
                    $salaryConditions[] = "(os.remuneration > 100000)";
                }
            }
            if (!empty($salaryConditions)) {
                $conditions[] = '(' . implode(' OR ', $salaryConditions) . ')';
            }
        }

        if (!empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }

        $sql .= " GROUP BY os.id ORDER BY os.date_publication DESC";

        $stmt = $this->db->prepare($sql);
        
        // Bind parameters
        $paramIndex = 1;
        foreach ($parameters as $param) {
            $stmt->bindValue($paramIndex++, $param);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getOffreById($id) {
        $sql = "
            SELECT os.*, e.nom as entreprise, e.description as entreprise_description, 
            GROUP_CONCAT(c.nom SEPARATOR ', ') as competences,
            (SELECT COUNT(*) FROM candidature WHERE offre_id = os.id) as nb_postulants
            FROM offre_stage os
            JOIN entreprise e ON os.entreprise_id = e.id
            LEFT JOIN offre_competence oc ON os.id = oc.offre_id
            LEFT JOIN competence c ON oc.competence_id = c.id
            WHERE os.id = ?
            GROUP BY os.id
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
