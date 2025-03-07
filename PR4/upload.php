<?php
require 'validateInput.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $file = $_FILES['file'];
        $maxSize = 2 * 1024 * 1024;
        $allowedType = 'application/pdf';

        // Vérification de la taille du fichier
        if ($file['size'] > $maxSize) {
            die("Fichier trop volumineux (max 2 Mo). ");
        }

        // Vérification du type MIME
        if (mime_content_type($file['tmp_name']) !== $allowedType) {
            die("Seuls les fichiers PDF sont autorises.");
        }

        // Création du dossier uploads s'il n'existe pas
        $uploadDir = 'uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Sécurisation du nom du fichier
        $safeFileName = validateInput(basename($file['name']));
        $destination = $uploadDir . $safeFileName;
        
        // Déplacement du fichier téléversé
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            echo "<script>window.location.href = 'pagination.php';</script>";
            exit;
        } else {
            echo "Erreur lors du televersement.";
            echo "<br><a href='pagination.php'>Aller a la pagination</a>";
        }
    } else {
        echo "Aucun fichier n'a ete televerse.";
    }
}
?>