/**
 * Mobile Menu Functionality
 * Gère le comportement du menu mobile
 */
document.addEventListener('DOMContentLoaded', function() {
  // Éléments du menu mobile
  const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
  const mobileMenu = document.querySelector('.mobile-menu');
  const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
  const mobileMenuClose = document.querySelector('.mobile-menu-close');
  const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

  // Fonction pour ouvrir le menu
  function openMobileMenu() {
    mobileMenuToggle.classList.add('open');
    mobileMenu.classList.add('open');
    mobileMenuOverlay.classList.add('open');
    document.body.style.overflow = 'hidden'; // Empêche le défilement de la page
  }

  // Fonction pour fermer le menu
  function closeMobileMenu() {
    mobileMenuToggle.classList.remove('open');
    mobileMenu.classList.remove('open');
    mobileMenuOverlay.classList.remove('open');
    document.body.style.overflow = ''; // Réactive le défilement de la page
  }

  // Événements pour ouvrir/fermer le menu
  if (mobileMenuToggle) {
    mobileMenuToggle.addEventListener('click', function(e) {
      e.stopPropagation();
      if (mobileMenu.classList.contains('open')) {
        closeMobileMenu();
      } else {
        openMobileMenu();
      }
    });
  }

  // Fermer le menu quand on clique sur le bouton de fermeture
  if (mobileMenuClose) {
    mobileMenuClose.addEventListener('click', function(e) {
      e.stopPropagation();
      closeMobileMenu();
    });
  }

  // Fermer le menu quand on clique sur l'overlay
  if (mobileMenuOverlay) {
    mobileMenuOverlay.addEventListener('click', function(e) {
      e.stopPropagation();
      closeMobileMenu();
    });
  }

  // Fermer le menu quand on clique sur un lien
  if (mobileNavLinks.length > 0) {
    mobileNavLinks.forEach(function(link) {
      link.addEventListener('click', function() {
        closeMobileMenu();
      });
    });
  }

  // Empêcher la propagation du clic à l'intérieur du menu
  if (mobileMenu) {
    mobileMenu.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  }
});