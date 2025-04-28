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
        cleanDescription === description && cleanDescription.length > 0 && cleanDescription.length <= 1000 &&
        cleanStatus === status && (["planned", "in_progress", "completed", "cancelled"].includes(cleanStatus)) &&
        cleanStartDate === startDate && cleanStartDate !== "" &&
        cleanEndDate === endDate && cleanEndDate !== "" &&
        cleanTeamId === teamId && cleanTeamId.length > 0
    );

    if (!isValid) {
        resultDiv.innerHTML = "<span class='error'>An error occurred. Please provide valid information</span>";
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

                // Generowanie HTML nowego wiersza
                const newProjectHTML = `
                    <div class="project project-content">
                        <div class="project-id">${data.id}</div>
                        <div class="project-name">
                            <form action="project-details.php" method="post">
                                <input type="hidden" name="project-id" value="${data.id}">
                                <button class="submit-order-form" type="submit">
                                    ${cleanTitle}
                                </button>
                            </form>
                        </div>
                        <div class="project-desc">${cleanDescription}</div>
                        <div class="project-start-date">${cleanStartDate}</div>
                        <div class="project-end-date">${cleanEndDate}</div>
                        <div class="project-status">${cleanStatus}</div>
                        <div class="team-name">${data.team_name}</div>
                    </div>
                `;

                // Dodaj nowy projekt na koniec listy
                const projectContainer = document.querySelector("#main .project");
                const projectList = document.querySelectorAll(".project.project-content"); // Pobierz wszystkie istniejące projekty
                const lastProject = projectList[projectList.length - 1]; // Znajdź ostatni projekt

                // Jeśli istnieją projekty, dodaj nowy poniżej ostatniego
                if (lastProject) {
                    lastProject.insertAdjacentHTML("afterend", newProjectHTML);
                } else {
                    // Jeśli nie ma żadnych projektów, dodaj nowy na początku
                    projectContainer.insertAdjacentHTML("beforeend", newProjectHTML);
                }

                // Opcjonalnie: zamknij okno dodawania projektu
                closeProjectWindow();

            } else {
                resultDiv.innerHTML = "<span class='error'>Failed to add project. Please try again</span>";
            }
        })
        .catch(error => {
            console.error("Error:", error);
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
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
