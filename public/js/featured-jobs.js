document.addEventListener('DOMContentLoaded', function() {
    // Récupérer le conteneur des offres à la une
    const featuredJobsContainer = document.getElementById('featured-jobs');
    
    // Vérifier si le conteneur existe
    if (!featuredJobsContainer) return;
    
    // Fonction pour déterminer quelle image utiliser en fonction du type de l'offre
    function getJobImage(job) {
        // Vérifier si le job a un type défini
        const jobType = job.type ? job.type.toLowerCase() : '';
        const jobTitle = job.titre ? job.titre.toLowerCase() : '';
        const jobDescription = job.description ? job.description.toLowerCase() : '';
        
        // Déterminer l'image en fonction du contenu de l'offre
        if (jobType.includes('informatique') || jobType.includes('tech') || 
            jobTitle.includes('développeur') || jobTitle.includes('informatique') || 
            jobDescription.includes('développeur') || jobDescription.includes('informatique')) {
            return 'public/images/info-img.png';
        } else if (jobType.includes('btp') || jobType.includes('construction') || 
                  jobTitle.includes('btp') || jobTitle.includes('construction') || 
                  jobDescription.includes('btp') || jobDescription.includes('construction')) {
            return 'public/images/btp-img.png';
        } else if (jobType.includes('santé') || jobType.includes('médical') || jobType.includes('sante') || 
                  jobTitle.includes('santé') || jobTitle.includes('médical') || 
                  jobDescription.includes('santé') || jobDescription.includes('médical')) {
            return 'public/images/sante-img.png';
        } else if (jobType.includes('finance') || jobType.includes('comptabilité') || 
                  jobTitle.includes('finance') || jobTitle.includes('comptabilité') || 
                  jobDescription.includes('finance') || jobDescription.includes('comptabilité')) {
            return 'public/images/fin-img.png';
        } else if (jobType.includes('marketing') || jobType.includes('communication') || 
                  jobTitle.includes('marketing') || jobTitle.includes('communication') || 
                  jobDescription.includes('marketing') || jobDescription.includes('communication')) {
            return 'public/images/mark-img.png';
        } else {
            // Pour "Autre" ou si le type n'est pas reconnu
            return 'public/images/gen-img.png';
        }
    }
    
    // Fonction pour afficher les offres à la une
    function displayFeaturedJobs(jobs) {
        // Vider le conteneur
        featuredJobsContainer.innerHTML = '';
        
        if (jobs.length === 0) {
            featuredJobsContainer.innerHTML = '<p class="no-jobs">Aucune offre à la une disponible pour le moment.</p>';
            return;
        }
        
        // Afficher chaque offre
        jobs.forEach(job => {
            // Formater la date
            const dateDebut = new Date(job.date_debut);
            const dateFin = job.date_fin ? new Date(job.date_fin) : null;
            const formattedDate = dateFin 
                ? `${dateDebut.toLocaleDateString()} - ${dateFin.toLocaleDateString()}`
                : `À partir du ${dateDebut.toLocaleDateString()}`;
            
            // Créer la carte d'offre
            const jobCard = document.createElement('div');
            jobCard.className = 'job-card';
            
            jobCard.innerHTML = `
                <a href="offres/details/${job.id}" class="job-card-link" aria-label="Voir les détails de ${job.titre}"></a>
                <div class="job-card-image">
                    <img src="${getJobImage(job)}" alt="${job.titre}" class="job-image">
                </div>
                <div class="job-card-content">
                    <h3 class="job-title">${job.titre}</h3>
                    <div class="job-location">
                        <span class="iconify" data-icon="mdi:map-marker" width="16" height="16"></span>
                        <span>${job.ville || 'Non spécifiée'}</span>
                    </div>
                    <div class="job-company">
                        <span class="iconify" data-icon="mdi:office-building" width="16" height="16"></span>
                        <span>${job.entreprise || job.nom_entreprise || 'Entreprise non spécifiée'}</span>
                    </div>
                    <div class="job-salary">
                        <span class="iconify" data-icon="mdi:currency-eur" width="16" height="16"></span>
                        <span>${job.remuneration}€/an</span>
                    </div>
                </div>
                <div class="job-card-actions">
                    <div class="job-date">
                        <span class="iconify" data-icon="mdi:calendar" width="16" height="16"></span>
                        <span>${formattedDate}</span>
                    </div>
                </div>
            `;
            
            featuredJobsContainer.appendChild(jobCard);
        });
    }
    
    // Charger les offres à la une
    fetch('offres/featured')
        .then(response => response.json())
        .then(jobs => {
            displayFeaturedJobs(jobs);
        })
        .catch(error => {
            console.error("Erreur lors du chargement des offres à la une:", error);
            featuredJobsContainer.innerHTML = '<p class="no-jobs">Erreur lors du chargement des offres à la une.</p>';
        });
});