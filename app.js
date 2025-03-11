document.addEventListener("DOMContentLoaded", function () {
    const searchForm = document.getElementById("search-form");
    const jobSearchInput = document.getElementById("job-search");
    const locationSearchInput = document.getElementById("location-search");
    const jobListContainer = document.getElementById("jobs-list");
    const noJobsFound = document.getElementById("no-jobs-found");

    async function fetchJobs() {
        const search = jobSearchInput.value.trim();
        const location = locationSearchInput.value.trim();
        const filters = {
            jobType: [...document.querySelectorAll('input[data-filter="jobType"]:checked')].map(input => input.value),
            experienceLevel: [...document.querySelectorAll('input[data-filter="experienceLevel"]:checked')].map(input => input.value),
            salary: [...document.querySelectorAll('input[data-filter="salary"]:checked')].map(input => input.value),
        };

        const queryParams = new URLSearchParams({ search, location, filters: JSON.stringify(filters) });

        try {
            const response = await fetch(`fetch_jobs.php?${queryParams.toString()}`);
            const jobs = await response.json();

            jobListContainer.innerHTML = "";

            if (jobs.length === 0) {
                noJobsFound.classList.remove("hide");
                return;
            } else {
                noJobsFound.classList.add("hide");
            }

            jobs.forEach(job => {
                const jobElement = document.createElement("div");
                jobElement.classList.add("job-card");
                jobElement.innerHTML = `
                    <div class="job-card-header">
                        <h3>${job.titre}</h3>
                        <p class="job-company">${job.entreprise}</p>
                    </div>
                    <p class="job-description">${job.description.substring(0, 150)}...</p>
                    <p class="job-skills">Compétences : ${job.competences}</p>
                    <p class="job-salary">Salaire : ${job.remuneration}€</p>
                    <p class="job-date">Publié le : ${job.date_offre}</p>
                    <p class="job-postulants">${job.nb_postulants} étudiant(s) ont postulé</p>
                    <button class="apply-button">Postuler</button>
                `;
                jobListContainer.appendChild(jobElement);
            });
        } catch (error) {
            console.error("Erreur lors de la récupération des offres :", error);
        }
    }

    searchForm.addEventListener("submit", function (event) {
        event.preventDefault();
        fetchJobs();
    });

    document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', fetchJobs);
    });

    fetchJobs(); // Charger les offres au démarrage
});
