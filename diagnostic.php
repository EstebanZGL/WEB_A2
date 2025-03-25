<?php
// Script de diagnostic pour identifier les problèmes de redirection
// Placer ce fichier à la racine du projet et y accéder directement via
// http://localhost/Project_MVC/diagnostic.php

// Désactiver la mise en cache du navigateur
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Fonction pour afficher les informations de manière formatée
function printSection($title, $content) {
    echo "<div style='margin: 20px 0; padding: 10px; border: 1px solid #ddd; border-radius: 5px;'>";
    echo "<h3 style='margin-top: 0; color: #333;'>$title</h3>";
    echo "<div style='font-family: monospace; white-space: pre-wrap;'>";
    echo $content;
    echo "</div></div>";
}

// Démarrer la sortie HTML
echo "<!DOCTYPE html>
<html>
<head>
    <title>Diagnostic de redirection</title>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; color: #333; max-width: 1200px; margin: 0 auto; }
        h1 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        h2 { color: #2980b9; margin-top: 30px; }
        pre { background-color: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .warning { color: #e74c3c; font-weight: bold; }
        .success { color: #27ae60; font-weight: bold; }
        .info { color: #3498db; }
        .container { display: flex; flex-direction: column; gap: 20px; }
        button { padding: 10px 15px; background-color: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #2980b9; }
    </style>
</head>
<body>
    <h1>Diagnostic de redirection pour Project_MVC</h1>
    <p>Ce script analyse votre environnement pour identifier les problèmes de redirection.</p>";

// 1. Informations sur le serveur et PHP
$serverInfo = "Serveur: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
$serverInfo .= "PHP Version: " . phpversion() . "\n";
$serverInfo .= "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
$serverInfo .= "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
$serverInfo .= "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
printSection("1. Informations sur le serveur", $serverInfo);

// 2. Vérification des sessions
$sessionInfo = "";
if (session_status() == PHP_SESSION_DISABLED) {
    $sessionInfo .= "<span class='warning'>Les sessions PHP sont désactivées sur ce serveur.</span>\n";
} elseif (session_status() == PHP_SESSION_NONE) {
    session_start();
    $sessionInfo .= "<span class='success'>Session démarrée avec succès.</span>\n";
} else {
    $sessionInfo .= "<span class='success'>Une session est déjà active.</span>\n";
}

$sessionInfo .= "ID de session: " . session_id() . "\n";
$sessionInfo .= "Chemin de sauvegarde des sessions: " . session_save_path() . "\n";
$sessionInfo .= "Cookie de session: " . (isset($_COOKIE[session_name()]) ? $_COOKIE[session_name()] : "Non défini") . "\n\n";

$sessionInfo .= "Contenu de la session:\n";
if (empty($_SESSION)) {
    $sessionInfo .= "La session est vide.\n";
} else {
    foreach ($_SESSION as $key => $value) {
        $sessionInfo .= "  $key: " . (is_array($value) ? json_encode($value) : $value) . "\n";
    }
}
printSection("2. Vérification des sessions", $sessionInfo);

// 3. Analyse des cookies
$cookieInfo = "";
if (empty($_COOKIE)) {
    $cookieInfo .= "Aucun cookie n'est défini.\n";
} else {
    $cookieInfo .= "Liste des cookies:\n";
    foreach ($_COOKIE as $name => $value) {
        $cookieInfo .= "  $name: " . (is_array($value) ? json_encode($value) : $value) . "\n";
    }
}
printSection("3. Analyse des cookies", $cookieInfo);

// 4. Vérification des fichiers de configuration
$configInfo = "";

// Vérifier le fichier .htaccess principal
$htaccessPath = __DIR__ . '/.htaccess';
if (file_exists($htaccessPath)) {
    $htaccessContent = file_get_contents($htaccessPath);
    $configInfo .= "Contenu du fichier .htaccess principal:\n" . htmlspecialchars($htaccessContent) . "\n\n";
} else {
    $configInfo .= "<span class='warning'>Le fichier .htaccess principal n'existe pas.</span>\n\n";
}

// Vérifier le fichier .htaccess dans public/
$publicHtaccessPath = __DIR__ . '/public/.htaccess';
if (file_exists($publicHtaccessPath)) {
    $publicHtaccessContent = file_get_contents($publicHtaccessPath);
    $configInfo .= "Contenu du fichier public/.htaccess:\n" . htmlspecialchars($publicHtaccessContent) . "\n\n";
} else {
    $configInfo .= "Le fichier public/.htaccess n'existe pas.\n\n";
}

// Vérifier le fichier routes/web.php
$webRoutesPath = __DIR__ . '/routes/web.php';
if (file_exists($webRoutesPath)) {
    $webRoutesContent = file_get_contents($webRoutesPath);
    // Vérifier si le débogage est activé
    if (strpos($webRoutesContent, 'echo "URI reçue par le routeur:') !== false && strpos($webRoutesContent, '// echo "URI reçue par le routeur:') === false) {
        $configInfo .= "<span class='warning'>Le débogage est activé dans routes/web.php, ce qui peut interférer avec les redirections.</span>\n\n";
    } else {
        $configInfo .= "<span class='success'>Le débogage est désactivé dans routes/web.php.</span>\n\n";
    }
    
    // Vérifier la gestion de la déconnexion
    if (strpos($webRoutesContent, 'case \'logout\':') !== false) {
        if (strpos($webRoutesContent, '$logoutController->logout()') !== false) {
            $configInfo .= "<span class='success'>La route 'logout' utilise correctement le contrôleur LogoutController.</span>\n";
        } else if (strpos($webRoutesContent, 'header(\'Location:') !== false && strpos($webRoutesContent, 'exit') === false) {
            $configInfo .= "<span class='warning'>La route 'logout' effectue une redirection sans appeler exit(), ce qui peut causer des problèmes.</span>\n";
        }
    }
} else {
    $configInfo .= "<span class='warning'>Le fichier routes/web.php n'existe pas.</span>\n";
}
printSection("4. Vérification des fichiers de configuration", $configInfo);

// 5. Test de redirection
$redirectInfo = "";
$redirectInfo .= "Pour tester les redirections, cliquez sur les boutons ci-dessous:\n\n";
$redirectInfo .= "<div class='container'>";
$redirectInfo .= "<a href='http://localhost/Project_MVC/home'><button>Accéder à la page d'accueil</button></a>";
$redirectInfo .= "<a href='http://localhost/Project_MVC/login'><button>Accéder à la page de connexion</button></a>";
$redirectInfo .= "</div>";

// Ajouter un formulaire pour tester la connexion
$redirectInfo .= "<h3>Tester la connexion:</h3>";
$redirectInfo .= "<form action='http://localhost/Project_MVC/login/authenticate' method='post' style='display: flex; flex-direction: column; max-width: 300px; gap: 10px;'>";
$redirectInfo .= "<input type='email' name='email' placeholder='Email' required>";
$redirectInfo .= "<input type='password' name='password' placeholder='Mot de passe' required>";
$redirectInfo .= "<button type='submit'>Se connecter</button>";
$redirectInfo .= "</form>";

// Ajouter un bouton pour effacer la session
$redirectInfo .= "<h3>Actions de diagnostic:</h3>";
$redirectInfo .= "<div class='container'>";
$redirectInfo .= "<a href='?action=clear_session'><button>Effacer la session</button></a>";
$redirectInfo .= "<a href='?action=clear_cookies'><button>Effacer les cookies</button></a>";
$redirectInfo .= "<a href='?action=test_redirect'><button>Tester une redirection simple</button></a>";
$redirectInfo .= "</div>";

// Traiter les actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'clear_session':
            session_destroy();
            $redirectInfo .= "<p class='success'>Session effacée avec succès. <a href='diagnostic.php'>Rafraîchir</a></p>";
            break;
        case 'clear_cookies':
            foreach ($_COOKIE as $name => $value) {
                setcookie($name, '', time() - 3600, '/');
            }
            $redirectInfo .= "<p class='success'>Cookies effacés avec succès. <a href='diagnostic.php'>Rafraîchir</a></p>";
            break;
        case 'test_redirect':
            $redirectInfo .= "<p class='info'>Test de redirection en cours...</p>";
            $redirectInfo .= "<script>
                setTimeout(function() {
                    window.location.href = 'diagnostic.php?action=redirect_complete';
                }, 1000);
            </script>";
            break;
        case 'redirect_complete':
            $redirectInfo .= "<p class='success'>Redirection JavaScript réussie!</p>";
            break;
    }
}

printSection("5. Test de redirection", $redirectInfo);

// 6. Recommandations
$recommendations = "";
$recommendations .= "Voici quelques recommandations pour résoudre les problèmes de redirection:\n\n";
$recommendations .= "1. <strong>Vérifiez la cohérence des redirections</strong> dans tous vos fichiers. Utilisez toujours le même format (relatif ou absolu).\n";
$recommendations .= "2. <strong>Assurez-vous que toutes les redirections sont suivies d'un exit()</strong> pour terminer l'exécution du script.\n";
$recommendations .= "3. <strong>Désactivez le débogage</strong> dans les fichiers de production pour éviter les interférences avec les en-têtes HTTP.\n";
$recommendations .= "4. <strong>Effacez régulièrement les cookies et la cache du navigateur</strong> pendant le développement pour éviter les problèmes liés aux anciennes données.\n";
$recommendations .= "5. <strong>Vérifiez les permissions des dossiers de session</strong> pour vous assurer que PHP peut y écrire correctement.\n";
$recommendations .= "6. <strong>Utilisez des chemins relatifs cohérents</strong> dans toutes vos redirections, en tenant compte du sous-dossier 'Project_MVC'.\n";

printSection("6. Recommandations", $recommendations);

// 7. Analyse des contrôleurs qui pourraient causer des redirections
$controllersInfo = "";

// Vérifier le contrôleur AdminController.php
$adminControllerPath = __DIR__ . '/app/controllers/AdminController.php';
if (file_exists($adminControllerPath)) {
    $adminControllerContent = file_get_contents($adminControllerPath);
    if (strpos($adminControllerContent, 'header("Location:') !== false) {
        $controllersInfo .= "<span class='warning'>Le fichier AdminController.php contient des redirections qui pourraient causer des problèmes.</span>\n";
        
        // Extraire les redirections
        preg_match_all('/header\("Location: ([^"]+)/', $adminControllerContent, $matches);
        if (!empty($matches[1])) {
            $controllersInfo .= "Redirections trouvées dans AdminController.php:\n";
            foreach ($matches[1] as $redirect) {
                $controllersInfo .= "  - $redirect\n";
            }
        }
    } else {
        $controllersInfo .= "Aucune redirection trouvée dans AdminController.php.\n";
    }
} else {
    $controllersInfo .= "Le fichier AdminController.php n'existe pas.\n";
}

// Vérifier le contrôleur GestionController.php
$gestionControllerPath = __DIR__ . '/app/controllers/GestionController.php';
if (file_exists($gestionControllerPath)) {
    $gestionControllerContent = file_get_contents($gestionControllerPath);
    if (strpos($gestionControllerContent, 'header("Location:') !== false) {
        $controllersInfo .= "<span class='warning'>Le fichier GestionController.php contient des redirections qui pourraient causer des problèmes.</span>\n";
        
        // Extraire les redirections
        preg_match_all('/header\("Location: ([^"]+)/', $gestionControllerContent, $matches);
        if (!empty($matches[1])) {
            $controllersInfo .= "Redirections trouvées dans GestionController.php:\n";
            foreach ($matches[1] as $redirect) {
                $controllersInfo .= "  - $redirect\n";
            }
        }
    } else {
        $controllersInfo .= "Aucune redirection trouvée dans GestionController.php.\n";
    }
} else {
    $controllersInfo .= "Le fichier GestionController.php n'existe pas.\n";
}

// Vérifier les autres contrôleurs
$otherControllers = ['HomeController.php', 'OffresController.php'];
foreach ($otherControllers as $controller) {
    $controllerPath = __DIR__ . '/app/controllers/' . $controller;
    if (file_exists($controllerPath)) {
        $controllerContent = file_get_contents($controllerPath);
        if (strpos($controllerContent, 'header("Location:') !== false) {
            $controllersInfo .= "<span class='warning'>Le fichier $controller contient des redirections qui pourraient causer des problèmes.</span>\n";
            
            // Extraire les redirections
            preg_match_all('/header\("Location: ([^"]+)/', $controllerContent, $matches);
            if (!empty($matches[1])) {
                $controllersInfo .= "Redirections trouvées dans $controller:\n";
                foreach ($matches[1] as $redirect) {
                    $controllersInfo .= "  - $redirect\n";
                }
            }
        } else {
            $controllersInfo .= "Aucune redirection trouvée dans $controller.\n";
        }
    } else {
        $controllersInfo .= "Le fichier $controller n'existe pas.\n";
    }
}

printSection("7. Analyse des contrôleurs", $controllersInfo);

// Fermer la sortie HTML
echo "</body></html>";
?>