<?php
session_start();

// Connexion à la base de données
$host = 'localhost'; 
$dbname = 'lebonplan';
$username = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérification des informations de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Requête SQL pour récupérer l'utilisateur avec l'email fourni
        $stmt = $pdo->prepare("SELECT * FROM connexion WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe hashé
        if ($user && password_verify($password, $user['mdp'])) { 
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            header("Location: index.html"); // Redirige vers la page d'accueil
            exit();
        } else {
            echo "<script>alert('Email ou mot de passe incorrect'); window.location.href='index.html';</script>";
        }
    } else {
        echo "<script>alert('Veuillez remplir tous les champs'); window.location.href='index.html';</script>";
    }
} else {
    header("Location: index.html");
    exit();
}
?>
