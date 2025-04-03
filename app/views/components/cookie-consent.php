<div class="cookie-consent" id="cookieConsent">
    <div class="cookie-content">
        <div class="cookie-text">
            <h3>🍪 Paramètres des cookies</h3>
            <p>Nous utilisons des cookies pour améliorer votre expérience sur notre site. Ces cookies sont essentiels pour le bon fonctionnement du site et la sécurité de vos données.</p>
        </div>
        <div class="cookie-actions">
            <button class="cookie-btn cookie-btn-accept" onclick="acceptCookies()">
                Accepter
            </button>
            <button class="cookie-btn cookie-btn-decline" onclick="declineCookies()">
                Refuser
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (!localStorage.getItem('cookieConsent')) {
        setTimeout(function() {
            document.getElementById('cookieConsent').classList.add('show');
        }, 1000);
    }
});

function acceptCookies() {
    localStorage.setItem('cookieConsent', 'accepted');
    document.getElementById('cookieConsent').classList.remove('show');
}

function declineCookies() {
    localStorage.setItem('cookieConsent', 'declined');
    document.getElementById('cookieConsent').classList.remove('show');
}
</script>