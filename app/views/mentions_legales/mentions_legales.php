<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mentions Légales | LeBonPlan</title>
    <meta name="description" content="Mentions légales du site LeBonPlan." />
    <link rel="stylesheet" href="public/css/style.css" />
    <link rel="stylesheet" href="public/css/responsive-complete.css">
    <link rel="stylesheet" href="public/css/wishlist.css">
    <!-- Ajout d'Iconify pour les icônes -->
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
</head>
<body>
    <div id="app">
        <!-- Menu Mobile Overlay -->
        <div class="mobile-menu-overlay"></div>
        
        <!-- Menu Mobile -->
        <div class="mobile-menu">
            <div class="mobile-menu-header">
                <img src="public/images/logo.png" alt="D" width="100" height="113">
                <button class="mobile-menu-close">&times;</button>
            </div>
            <nav class="mobile-nav">
                <a href="home" class="mobile-nav-link">Accueil</a>
                <a href="offres" class="mobile-nav-link">Emplois</a>
                <a href="dashboard" class="mobile-nav-link" id="mobile-page-dashboard" style="display:none;">Tableau de bord</a>
                <a href="gestion" class="mobile-nav-link" id="mobile-page-gestion" style="display:none;">Gestion</a>
            </nav>
            <div class="mobile-menu-footer">
                <div class="mobile-menu-buttons">
                    <a href="login" id="mobile-login-Bouton" class="button button-primary button-glow">Connexion</a>
                    <a href="logout" id="mobile-logout-Bouton" class="button button-primary button-glow" style="display:none;">Déconnexion</a>
                </div>
            </div>
        </div>
        
        <header class="navbar">
            <div class="container">
                <img src="public/images/logo.png" alt="D" width="150" height="170">
                
                <!-- Bouton Menu Mobile -->
                <button class="mobile-menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <nav class="navbar-nav">
                    <a href="home" class="nav-link">Accueil</a>
                    <a href="offres" class="nav-link">Emplois</a>
                    <a href="dashboard" class="nav-link" id="page-dashboard" style="display:none;">Tableau de bord</a>
                    <a href="gestion" class="nav-link" id="page-gestion" style="display:none;">Gestion</a>
                </nav>

                <div id="user-status">
                    <a href="login" id="login-Bouton" class="button button-outline button-glow">Connexion</a>
                    <a href="logout" id="logout-Bouton" class="button button-outline button-glow" style="display:none;">Déconnexion</a>
                </div>
            </div>
            <span id="welcome-message" class="welcome-message"></span>
        </header>

        <main class="main-content">
            <div class="container">
                <section class="section">
                    <h1 class="section-title">Mentions Légales</h1>
                    
                    <div class="card">
                        <div class="card-body">
                            <h2>1. Informations légales</h2>
                            <p>Le site LeBonPlan est édité par :</p>
                            <ul>
                                <li><strong>Nom de l'entreprise :</strong> CESI École d'Ingénieurs</li>
                                <li><strong>Forme juridique :</strong> Association loi 1901</li>
                                <li><strong>Adresse :</strong> [Adresse du CESI]</li>
                                <li><strong>Téléphone :</strong> [Numéro de téléphone]</li>
                                <li><strong>Email :</strong> [Email de contact]</li>
                                <li><strong>Directeur de la publication :</strong> [Nom du directeur]</li>
                            </ul>
                            
                            <h2>2. Hébergement</h2>
                            <p>Le site est hébergé par :</p>
                            <ul>
                                <li><strong>Nom de l'hébergeur :</strong> [Nom de l'hébergeur]</li>
                                <li><strong>Adresse :</strong> [Adresse de l'hébergeur]</li>
                                <li><strong>Téléphone :</strong> [Téléphone de l'hébergeur]</li>
                            </ul>
                            
                            <h2>3. Propriété intellectuelle</h2>
                            <p>L'ensemble des éléments constituant le site LeBonPlan (textes, graphismes, logiciels, photographies, images, vidéos, sons, plans, noms, logos, marques, créations et œuvres protégeables diverses, bases de données, etc.) ainsi que le site lui-même, relèvent des législations françaises et internationales sur le droit d'auteur et la propriété intellectuelle.</p>
                            <p>Ces éléments sont la propriété exclusive de CESI. Toute reproduction ou représentation, en tout ou partie, est interdite sans l'accord préalable et écrit de CESI.</p>
                            
                            <h2>4. Protection des données personnelles</h2>
                            <p>Les informations recueillies sur ce site font l'objet d'un traitement informatique destiné à [préciser la finalité du traitement]. Conformément à la loi « informatique et libertés » du 6 janvier 1978 modifiée et au Règlement européen n°2016/679/UE du 27 avril 2016, vous bénéficiez d'un droit d'accès, de rectification, de portabilité et d'effacement de vos données ou encore de limitation du traitement.</p>
                            <p>Pour exercer ces droits ou pour toute question sur le traitement de vos données, vous pouvez contacter [préciser le service ou la personne en charge].</p>
                            
                            <h2>5. Cookies</h2>
                            <p>Le site LeBonPlan utilise des cookies pour améliorer l'expérience utilisateur. En naviguant sur ce site, vous acceptez l'utilisation de cookies conformément à notre politique de confidentialité.</p>
                            
                            <h2>6. Limitation de responsabilité</h2>
                            <p>CESI ne pourra être tenue responsable des dommages directs et indirects causés au matériel de l'utilisateur, lors de l'accès au site LeBonPlan.</p>
                            <p>CESI décline toute responsabilité quant à l'utilisation qui pourrait être faite des informations et contenus présents sur LeBonPlan.</p>
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <footer class="footer">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-brand">
                        <a href="home" class="footer-logo">
                            <img src="public/images/logo.png" alt="D" width="150" height="170">
                        </a>
                        <p class="footer-tagline">Votre passerelle vers des opportunités de carrière.</p>
                    </div>
                    <div class="footer-links">
                        <h3 class="footer-heading">Pour les Chercheurs d'Emploi</h3>
                        <ul>
                            <li><a href="offres" class="footer-link">Parcourir les Emplois</a></li>
                            <li><a href="mentions-legales" class="footer-link">Mentions Légales</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p class="copyright">© <span id="current-year">2025</span> LeBonPlan. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Important: Charger mobile-menu.js avant les autres scripts -->
    <script src="public/js/mobile-menu.js"></script>
    <script src="public/js/mentions_legales.js"></script>
</body>
</html>