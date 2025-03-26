document.addEventListener('DOMContentLoaded', function() {
    // Créer les éléments du menu burger s'ils n'existent pas
    createMobileMenuElements();
    
    // Obtenir les éléments
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
    const mobileMenuClose = document.querySelector('.mobile-menu-close');
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
    
    // Fonction pour basculer le menu
    function toggleMobileMenu() {
        mobileMenuToggle.classList.toggle('open');
        mobileMenu.classList.toggle('open');
        mobileMenuOverlay.classList.toggle('open');
        
        // Empêcher le défilement du corps lorsque le menu est ouvert
        if (mobileMenu.classList.contains('open')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }
    
    // Écouteurs d'événements
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', toggleMobileMenu);
    }
    
    if (mobileMenuOverlay) {
        mobileMenuOverlay.addEventListener('click', toggleMobileMenu);
    }
    
    if (mobileMenuClose) {
        mobileMenuClose.addEventListener('click', toggleMobileMenu);
    }
    
    // Fermer le menu lors du clic sur un lien
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', toggleMobileMenu);
    });
    
    // Fermer le menu lors du redimensionnement de la fenêtre si l'écran devient grand
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && mobileMenu && mobileMenu.classList.contains('open')) {
            toggleMobileMenu();
        }
    });
    
    // Fonction pour créer les éléments du menu mobile s'ils n'existent pas
    function createMobileMenuElements() {
        if (!document.querySelector('.mobile-menu-toggle')) {
            // Créer le bouton toggle (hamburger)
            const toggleButton = document.createElement('button');
            toggleButton.className = 'mobile-menu-toggle';
            toggleButton.setAttribute('aria-label', 'Toggle menu');
            
            for (let i = 0; i < 4; i++) {
                const span = document.createElement('span');
                toggleButton.appendChild(span);
            }
            
            // Créer l'overlay
            const overlay = document.createElement('div');
            overlay.className = 'mobile-menu-overlay';
            
            // Créer le menu mobile
            const mobileMenu = document.createElement('div');
            mobileMenu.className = 'mobile-menu';
            
            // En-tête du menu
            const menuHeader = document.createElement('div');
            menuHeader.className = 'mobile-menu-header';
            
            const logoCircle = document.createElement('div');
            logoCircle.className = 'logo-circle logo-circle-small';
            
            const brandText = document.createElement('span');
            brandText.className = 'brand-text-sm';
            brandText.textContent = 'J';
            logoCircle.appendChild(brandText);
            
            const closeButton = document.createElement('button');
            closeButton.className = 'mobile-menu-close';
            closeButton.setAttribute('aria-label', 'Close menu');
            closeButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
            
            menuHeader.appendChild(logoCircle);
            menuHeader.appendChild(closeButton);
            
            // Navigation mobile
            const mobileNav = document.createElement('nav');
            mobileNav.className = 'mobile-nav';
            
            // Récupérer les liens de navigation existants et les dupliquer
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            if (navLinks.length > 0) {
                navLinks.forEach(link => {
                    const mobileLink = document.createElement('a');
                    mobileLink.href = link.href;
                    mobileLink.className = 'mobile-nav-link';
                    if (link.classList.contains('active')) {
                        mobileLink.classList.add('active');
                    }
                    mobileLink.textContent = link.textContent;
                    mobileNav.appendChild(mobileLink);
                });
            } else {
                // Liens par défaut si aucun lien n'est trouvé
                const defaultLinks = [
                    { href: '/', text: 'Accueil', active: true },
                    { href: '/jobs', text: 'Offres', active: false },
                    { href: '/companies', text: 'Entreprises', active: false },
                    { href: '/about', text: 'À propos', active: false },
                    { href: '/contact', text: 'Contact', active: false }
                ];
                
                defaultLinks.forEach(link => {
                    const mobileLink = document.createElement('a');
                    mobileLink.href = link.href;
                    mobileLink.className = 'mobile-nav-link';
                    if (link.active) {
                        mobileLink.classList.add('active');
                    }
                    mobileLink.textContent = link.text;
                    mobileNav.appendChild(mobileLink);
                });
            }
            
            // Pied de page du menu
            const menuFooter = document.createElement('div');
            menuFooter.className = 'mobile-menu-footer';
            
            const menuButtons = document.createElement('div');
            menuButtons.className = 'mobile-menu-buttons';
            
            // Boutons de connexion/inscription
            const loginButton = document.createElement('a');
            loginButton.href = '/login';
            loginButton.className = 'button button-primary';
            loginButton.textContent = 'Connexion';
            
            const registerButton = document.createElement('a');
            registerButton.href = '/register';
            registerButton.className = 'button button-outline';
            registerButton.textContent = 'Inscription';
            
            menuButtons.appendChild(loginButton);
            menuButtons.appendChild(registerButton);
            menuFooter.appendChild(menuButtons);
            
            // Assembler le menu
            mobileMenu.appendChild(menuHeader);
            mobileMenu.appendChild(mobileNav);
            mobileMenu.appendChild(menuFooter);
            
            // Ajouter les éléments au DOM
            const navbar = document.querySelector('.navbar');
            if (navbar) {
                navbar.querySelector('.container').appendChild(toggleButton);
                document.body.appendChild(overlay);
                document.body.appendChild(mobileMenu);
            }
        }
    }
});