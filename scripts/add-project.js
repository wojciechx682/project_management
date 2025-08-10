// Funkcja do otwierania i zamykania formularza dodawania projektu
function toggleProjectWindow() {
    console.log("toggleProjectWindow function");
    let projectWindow = document.querySelector("#add-project");
    let mainContainer = document.getElementById("main-container");
    if (!projectWindow) return;
    projectWindow.classList.toggle("hidden");
    let icon = document.querySelector(".icon-cancel");
    if (icon) {
        icon.addEventListener("click", closeProjectWindow);
    }
    if (mainContainer) {
        mainContainer.classList.toggle("unreachable");
    }
}

// Funkcja do zamykania okna formularza
function closeProjectWindow() {
    console.log("closeProjectWindow function");
    let projectWindow = document.querySelector("#add-project");
    let mainContainer = document.getElementById("main-container");
    if (projectWindow) projectWindow.classList.add("hidden");
    if (mainContainer) {
        mainContainer.classList.remove("unreachable");
    }
}

// Zamykanie formularza po naciśnięciu "Esc"
document.addEventListener("keydown", function(event) {
    let projectWindow = document.querySelector("#add-project");
    if (!projectWindow || projectWindow.classList.contains("hidden")) return;

    if (event.key === "Escape") {
        console.log("keydown event on add-project.js script");
        projectWindow.classList.add("hidden");
        let mainContainer = document.getElementById("main-container");
        if (mainContainer) {
            mainContainer.classList.remove("unreachable");
        }
    }
});

// Funkcja walidująca formularz i wysyłająca dane do PHP przy użyciu Fetch API
document.getElementById("add-project-form").addEventListener("submit", function (event) {

    console.log("add-project-form submit event occurred");

    event.preventDefault();  // Zatrzymanie domyślnego działania formularza (przeładowania strony)

    const resultDiv = document.getElementById("result");

    // Pobranie danych z formularza
    const title = document.getElementById("project-title").value.trim();
    const description = document.getElementById("project-description").value.trim();
    const status = document.getElementById("project-status").value.trim();
    const startDate = document.getElementById("project-start-date").value.trim();
    const endDate = document.getElementById("project-end-date").value.trim();
    const teamId = document.getElementById("project-team-id").value.trim();

    // Walidacja DOMPurify
    const cleanTitle = DOMPurify.sanitize(title);
    const cleanDescription = DOMPurify.sanitize(description);
    const cleanStatus = DOMPurify.sanitize(status);
    const cleanStartDate = DOMPurify.sanitize(startDate);
    const cleanEndDate = DOMPurify.sanitize(endDate);
    const cleanTeamId = DOMPurify.sanitize(teamId);

    // Walidacja danych
    const isValid = (
        cleanTitle === title && cleanTitle.length > 0 && cleanTitle.length <= 255 &&
        cleanDescription === description && cleanDescription.length > 0 && cleanDescription.length <= 90 &&
        cleanStatus === status && (["planned", "in_progress", "completed", "cancelled"].includes(cleanStatus)) &&
        cleanStartDate === startDate && cleanStartDate !== "" &&
        cleanEndDate === endDate && cleanEndDate !== "" &&
        cleanTeamId === teamId && cleanTeamId.length > 0
    );

    if (!isValid) {
        resultDiv.innerHTML = "<span class='error'>An error occurred. Please provide valid information</span>";
        closeProjectWindow();
        return;
    }

    // Przygotowanie danych do wysłania
    const formData = new FormData();
    formData.append("title", cleanTitle);
    formData.append("description", cleanDescription);
    formData.append("status", cleanStatus);
    formData.append("start_date", cleanStartDate);
    formData.append("end_date", cleanEndDate);
    formData.append("team_id", cleanTeamId);

    // Wysłanie danych do serwera za pomocą Fetch API
    fetch("addNewProject.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = "<span class='success'>Project added successfully!</span>";

                // HTML dla nagłówka tabeli - taki sam jak w view/manager/project-header.php
                const projectHeaderHTML = `
                    <div class="project">
                        <div class="project-id">
                            <span class="header-option">ID</span>
                        </div>
                        <div class="project-name">
                            <span class="header-option">Name</span>
                        </div>
                        <div class="project-desc">
                            <span class="header-option">Description</span>
                        </div>
                        <div class="project-start-date">
                            <span class="header-option">Start date</span>
                        </div>
                        <div class="project-end-date">
                            <span class="header-option">End date</span>
                        </div>
                        <div class="project-status">
                            <span class="header-option">Status</span>
                        </div>
                        <div class="project-team">
                            <span class="header-option">Team name</span>
                        </div>
                    </div>
                `;

                // Generowanie HTML nowego wiersza projektu
                const newProjectHTML = `
                    <div class="project project-content">
                        <div class="project-id">${data.id}</div>
                        <div class="project-name">
                            <form action="project-details.php" method="post">
                                <input type="hidden" name="project-id" value="${data.id}">
                                <button class="submit-order-form" type="submit">
                                    ${data.title}
                                </button>
                            </form>
                        </div>
                        <div class="project-desc">${data.description}</div>
                        <div class="project-start-date">${data.start_date}</div>
                        <div class="project-end-date">${data.end_date}</div>
                        <div class="project-status">${data.status}</div>
                        <div class="team-name">${data.team_name}</div>
                    </div>
                `;

                /*// Dodaj nowy projekt na koniec listy
                const projectContainer = document.querySelector("#main .project");
                const projectList = document.querySelectorAll(".project.project-content"); // Pobierz wszystkie istniejące projekty
                const lastProject = projectList[projectList.length - 1]; // Znajdź ostatni projekt

                // Jeśli istnieją projekty, dodaj nowy poniżej ostatniego
                if (lastProject) {
                    lastProject.insertAdjacentHTML("afterend", newProjectHTML);
                } else {
                    // Jeśli nie ma żadnych projektów, dodaj nowy na początku
                    projectContainer.insertAdjacentHTML("beforeend", newProjectHTML);
                }*/

                // Znajdź kontener, w którym przechowywane są wszystkie projekty
                const projectsListContainer = document.querySelector(".project-list");

                // Sprawdzenie, czy kontener listy projektów istnieje
                if (!projectsListContainer) {
                    console.error("Error: '.project-list' container not found!");
                    resultDiv.innerHTML = "<span class='error'>UI Error: Could not find where to add the project.</span>";
                    return; // Zakończ, jeśli nie można znaleźć kontenera
                }

                /*// Pobierz wszystkie istniejące projekty (elementy z danymi) z właściwego kontenera
                const existingDataProjects = projectsListContainer.querySelectorAll(".project.project-content");
                const lastDataProject = existingDataProjects[existingDataProjects.length - 1]; // Znajdź ostatni projekt z danymi

                // Jeśli istnieją projekty z danymi, dodaj nowy poniżej ostatniego
                if (lastDataProject) {
                    lastDataProject.insertAdjacentHTML("afterend", newProjectHTML);
                } else {
                    // Jeśli nie ma żadnych projektów z danymi, dodaj nowy jako ostatnie dziecko
                    // kontenera listy projektów. Jeśli w .project-list jest tylko nagłówek,
                    // nowy projekt zostanie dodany po nim.
                    projectsListContainer.insertAdjacentHTML("beforeend", newProjectHTML);
                }*/

                // Sprawdzenie, czy nagłówek już istnieje
                // Nagłówek ma klasę "project", ale nie ma klasy "project-content"
                const existingHeader = projectsListContainer.querySelector(".project:not(.project-content)");

                // Jeśli nagłówek nie istnieje, dodaj go
                if (!existingHeader) {
                    projectsListContainer.insertAdjacentHTML("afterbegin", projectHeaderHTML);
                }

                // Dodanie nowego projektu
                // Można uprościć logikę dodawania, zawsze dodając na końcu kontenera,
                // jeśli nagłówek jest już na początku lub został właśnie dodany.
                projectsListContainer.insertAdjacentHTML("beforeend", newProjectHTML);

                // Opcjonalnie: zamknij okno dodawania projektu
                closeProjectWindow();

            } else {
                resultDiv.innerHTML = "<span class='error'>Failed to add project. Please try again</span>";
                closeProjectWindow();
            }
        })
        .catch(error => {
            console.error("Error:", error);
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
            closeProjectWindow();
        });
});

/*document.querySelectorAll("div.task-title a").forEach(link => {
    link.addEventListener("click", function (e) {
        e.preventDefault();
        let onclickAttribute = this.getAttribute("onclick");
        let taskIdMatch = onclickAttribute.match(/toggleTaskDetails\((\d+)\)/);
        if (!taskIdMatch) return;

        let taskId = taskIdMatch[1];

        let mainContainer = document.getElementById("main-container");
        if (mainContainer) {
            mainContainer.classList.toggle("bright");
            mainContainer.classList.toggle("unreachable");
        }

        fetch("getTaskDetails.php?taskId=" + encodeURIComponent(taskId), {
            method: "GET",
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Błąd sieci!");
                }
                return response.text();
            })
            .then(data => {
                document.querySelector("div#task-details-window").innerHTML = data;
                toggleTaskDetails(taskId);
            })
            .catch(error => {
                console.error("Wystąpił błąd:", error);
            });
    });
});*/
