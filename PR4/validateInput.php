<?php
// Fonction pour sécuriser les entrées utilisateur
function validateInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Fonction pour valider le numéro de page dans la pagination
function validatePage($page, $totalPages) {
    if (!isset($page) || !ctype_digit($page)) {
        return 1;
    }
    $page = (int)$page;
    return ($page >= 1 && $page <= $totalPages) ? $page : 1;
}
?>

/* upload.html */
<!DOCTYPE html>
<html>
<head>
    <title>Televersement securise</title>
    <script>
        function redirectToPagination() {
            window.location.href = 'pagination.php';
        }
    </script>
</head>
<body>
    <!-- Formulaire de téléversement de fichier PDF -->
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file" accept="application/pdf" required>
        <button type="submit">Televerser</button>
    </form>
</body>
</html>