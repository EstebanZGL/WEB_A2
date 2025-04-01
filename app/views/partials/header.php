<header class="navbar">
    <div class="container">
        <a href="/" class="navbar-brand">
            <div class="logo-circle">
                <span>C</span>
            </div>
            <span class="brand-text">Career<span class="brand-neon">Hub</span></span>
        </a>

        <nav class="navbar-nav">
            <a href="/" class="nav-link <?php echo ($_SERVER['REQUEST_URI'] === '/') ? 'active' : ''; ?>">Accueil</a>
            <a href="/offres" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/offres') === 0) ? 'active' : ''; ?>">Offres</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/dashboard" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/dashboard') === 0) ? 'active' : ''; ?>">Tableau de bord</a>
                <?php if ($_SESSION['user_type'] === 'etudiant'): ?>
                    <a href="/wishlist" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/wishlist') === 0) ? 'active' : ''; ?>">Wishlist</a>
                <?php endif; ?>
                <?php if ($_SESSION['user_type'] === 'admin'): ?>
                    <a href="/admin" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin') === 0) ? 'active' : ''; ?>">Administration</a>
                <?php endif; ?>
                <?php if ($_SESSION['user_type'] === 'pilote'): ?>
                    <a href="/gestion" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/gestion') === 0) ? 'active' : ''; ?>">Gestion</a>
                <?php endif; ?>
            <?php endif; ?>
        </nav>

        <div id="user-status">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/logout" class="button button-secondary">Déconnexion</a>
            <?php else: ?>
                <a href="/login" class="button button-primary">Connexion</a>
            <?php endif; ?>
        </div>
    </div>
</header>