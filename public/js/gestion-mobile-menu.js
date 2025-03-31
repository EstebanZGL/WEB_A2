/**
 * Script spécifique pour le menu mobile de la page de gestion
 * Ce script remplace le comportement par défaut du menu mobile pour la page de gestion
 */
document.addEventListener('DOMContentLoaded', function() {
  // Éléments du menu mobile
  const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
  const mobileMenu = document.querySelector('.mobile-menu');
  const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
  const mobileMenuClose = document.querySelector('.mobile-menu-close');
  const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

  // Vérifier si tous les éléments nécessaires existent
  if (!mobileMenuToggle || !mobileMenu || !mobileMenuOverlay) {
    console.error('Éléments du menu mobile manquants');
    return;
  }

  // Fonction pour ouvrir le menu
  function openMobileMenu() {
    console.log('Ouverture du menu mobile (gestion)');
    mobileMenuToggle.classList.add('open');
    mobileMenu.classList.add('open');
    mobileMenuOverlay.classList.add('open');
    document.body.style.overflow = 'hidden'; // Empêche le défilement de la page
  }

  // Fonction pour fermer le menu
  function closeMobileMenu() {
    console.log('Fermeture du menu mobile (gestion)');
    mobileMenuToggle.classList.remove('open');
    mobileMenu.classList.remove('open');
    mobileMenuOverlay.classList.remove('open');
    document.body.style.overflow = ''; // Réactive le défilement de la page
  }

  // Événements pour ouvrir/fermer le menu
  mobileMenuToggle.addEventListener('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    console.log('Clic sur le bouton burger (gestion)');
    
    if (mobileMenu.classList.contains('open')) {
      closeMobileMenu();
    } else {
      openMobileMenu();
    }
  });

  // Fermer le menu quand on clique sur le bouton de fermeture
  if (mobileMenuClose) {
    mobileMenuClose.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      closeMobileMenu();
    });
  }

  // Fermer le menu quand on clique sur l'overlay
  mobileMenuOverlay.addEventListener('click', function(e) {
    e.stopPropagation();
    closeMobileMenu();
  });

  // Fermer le menu quand on clique sur un lien
  if (mobileNavLinks.length > 0) {
    mobileNavLinks.forEach(function(link) {
      link.addEventListener('click', function() {
        closeMobileMenu();
      });
    });
  }

  // Empêcher la propagation du clic à l'intérieur du menu
  mobileMenu.addEventListener('click', function(e) {
    e.stopPropagation();
  });
});