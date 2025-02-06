document.addEventListener("DOMContentLoaded", function() {
    // Bouton de remontée en haut de la page
    let scrollTopBtn = document.createElement("button");
    scrollTopBtn.id = "scrollTop";
    scrollTopBtn.innerHTML = "&#8679;";
    document.body.appendChild(scrollTopBtn);
    scrollTopBtn.style.display = "none";

    window.addEventListener("scroll", function() {
        scrollTopBtn.style.display = window.scrollY > 200 ? "block" : "none";
    });

    scrollTopBtn.addEventListener("click", function() {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });

    // Bannière de cookies
    if (!localStorage.getItem("cookieConsent")) {
        let cookieBanner = document.createElement("div");
        cookieBanner.innerHTML = `
            <p>Ce site utilise des cookies pour améliorer votre expérience. 
            <button id='acceptCookies'>Accepter</button></p>
        `;
        Object.assign(cookieBanner.style, {
            position: "fixed",
            bottom: "0",
            background: "black",
            color: "white",
            width: "100%",
            textAlign: "center",
            padding: "10px"
        });
        document.body.appendChild(cookieBanner);

        document.getElementById("acceptCookies").addEventListener("click", function() {
            localStorage.setItem("cookieConsent", "true");
            cookieBanner.remove();
        });
    }

    // Validation du formulaire
    let form = document.querySelector("form");
    if (form) {
        form.addEventListener("submit", function(event) {
            let requiredFields = document.querySelectorAll("input[required], textarea[required]");
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.border = "2px solid red";
                    isValid = false;
                } else {
                    field.style.border = "";
                }
            });

            // Validation du CV
            let cvInput = document.getElementById("cv");
            if (cvInput) {
                let file = cvInput.files[0];
                let allowedFormats = [
                    'application/pdf', 'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.oasis.opendocument.text',
                    'application/rtf', 'image/jpeg', 'image/png'
                ];

                if (file && (!allowedFormats.includes(file.type) || file.size > 2 * 1024 * 1024)) {
                    alert("Le fichier doit être au format .pdf, .doc, .docx, .odt, .rtf, .jpg ou .png et ne doit pas dépasser 2 Mo.");
                    event.preventDefault();
                }
            }

            if (!isValid) {
                event.preventDefault();
                alert("Veuillez remplir tous les champs obligatoires.");
            }
        });
    }

    // Conversion du nom en majuscules
    let nomInput = document.getElementById("nom");
    if (nomInput) {
        nomInput.addEventListener("input", function() {
            this.value = this.value.toUpperCase();
        });
    }

    // Menu burger
    let menuBurger = document.getElementById("menu-burger");
    let navMenu = document.querySelector("nav");

    if (menuBurger && navMenu) {
        menuBurger.addEventListener("click", function() {
            navMenu.classList.toggle("show");
        });
    }
});
