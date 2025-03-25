<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="/cesi-lebonplan/public/css/login.css">
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
</head>

<body>
    <div class="header">
        <h1>Connexion</h1>
        <p>Entrez vos identifiants pour accéder à votre compte</p>
    </div>
    <div class="container">
        <a href="/cesi-lebonplan/home" class="back">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="white" d="M15.414 7l-5.707 5.707a1 1 0 0 0 0 1.414l5.707 5.707A1 1 0 0 0 16 18.414L10.707 13 16 7.586A1 1 0 0 0 15.414 7z"/>
            </svg>
            <span class="back-text">Retour</span>
        </a>
        
        <div class="logo">
            <img src="/cesi-lebonplan/public/images/logo.png" alt="Connexion" width="300" height="300">
        </div>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php echo $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <!-- Formulaire qui envoie les données à login/authenticate -->
        <form action="/cesi-lebonplan/login/authenticate" method="post">
            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <div class="input-container">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="input-icon">
                        <path fill="#5f6168" d="M4 20q-.825 0-1.412-.587T2 18V6q0-.825.588-1.412T4 4h16q.825 0 1.413.588T22 6v12q0 .825-.587 1.413T20 20zm8-7L4 8v10h16V8zm0-2l8-5H4zM4 8V6v12z"/>
                    </svg>
                    <input type="email" placeholder="prenom.nom@viacesi.fr" id="email" name="email" required>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <div class="input-container">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="input-icon">
                        <g fill="none" stroke="#5f6168" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <path d="M5 13a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2z"/>
                            <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0-2 0m-3-5V7a4 4 0 1 1 8 0v4"/>
                        </g>
                    </svg>
                    <input type="password" placeholder="••••••••" id="password" name="password" required>
                </div>
            </div>
            <button type="submit" class="btn">Se connecter</button>
        </form>
          
        <div class="footer">
            <a href="#">Politique de confidentialité</a>
            <a href="#">Conditions d'utilisation</a>
        </div>
    </div>   
</body>
</html>