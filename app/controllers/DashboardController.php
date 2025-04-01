public function getStudentDashboardData() {
    $data = [];
    $data['title'] = "Tableau de bord étudiant";

    // Récupération des statistiques de candidatures
    $data['candidatures'] = [
                'acceptees' => $this->offreModel->countCandidatures('acceptee'),
                'refusees' => $this->offreModel->countCandidatures('refusee'),
                'en_attente' => $this->offreModel->countCandidatures('en_attente')
    ];
    
    // Pagination des offres
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $per_page = 5; // Nombre d'offres par page
    $offset = ($page - 1) * $per_page;
    
    $data['offers'] = $this->offreModel->getOffres($per_page, $offset);
    $data['total_offers'] = $this->offreModel->countOffres();
    $data['current_page'] = $page;
    $data['per_page'] = $per_page;

    // Pagination de la wishlist
    $wishlist_page = isset($_GET['wishlist_page']) ? (int)$_GET['wishlist_page'] : 1;
    $wishlist_offset = ($wishlist_page - 1) * $per_page;
    
    if (isset($_SESSION['user_id'])) {
        $data['wishlist'] = $this->wishlistModel->getWishlistByUserId($_SESSION['user_id'], $per_page, $wishlist_offset);
        $data['total_wishlist'] = $this->wishlistModel->countWishlistItems($_SESSION['user_id']);
        $data['current_wishlist_page'] = $wishlist_page;
}

    return $data;
}