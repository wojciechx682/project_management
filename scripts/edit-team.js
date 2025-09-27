// Funkcje do otwierania i zamykania okna edycji
function toggleEditTeamWindow(teamId) {

    console.log("toggleEditTeamWindow function called with ID:", teamId);

    fetch(`getTeamData.php?id=${teamId}`)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Używamy data.team, a nie data.project!
                const team = data.team;

                // Wypełniamy formularz o nowych ID
                document.getElementById("edit-team-id").value = team.id;
                document.getElementById("edit-team-name").value = team.name;

                // Otwieramy okno edycji o nowym ID
                let editWindow = document.querySelector("#edit-team-window");
                let mainContainer = document.getElementById("main-container");

                if (editWindow && mainContainer) {
                    editWindow.classList.remove("hidden");
                    mainContainer.classList.add("unreachable");
                }
            } else {
                // Jeśli serwer zwrócił błąd, wyświetl go
                document.getElementById("result").innerHTML = `<span class='error'>${data.message}</span>`;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            document.getElementById("result").innerHTML = "<span class='error'>Failed to load team data</span>";
        });
}

function closeEditProjectWindow() {
    console.log("closeEditProjectWindow function");
    let editWindow = document.querySelector("#edit-project");
    let mainContainer = document.getElementById("main-container");

    if(editWindow) editWindow.classList.add("hidden");
    if(mainContainer) mainContainer.classList.remove("unreachable");
}

// Zamykanie formularza po naciśnięciu Esc
document.addEventListener("keydown", function(event) {
    let editWindow = document.querySelector("#edit-project");
    if(!editWindow || editWindow.classList.contains("hidden")) return;

    if(event.key === "Escape") {
        closeEditProjectWindow();
    }
});

// Obsługa formularza edycji
document.getElementById("edit-project-form").addEventListener("submit", function(event) {

    event.preventDefault();

    const resultDiv = document.getElementById("result");
    const formData = new FormData(this);

    // Walidacja danych
    const title = formData.get("title").trim();
    const description = formData.get("description").trim();
    const status = formData.get("status");
    const startDate = formData.get("start_date");
    const endDate = formData.get("end_date");
    const teamId = formData.get("team_id");

    // Walidacja DOMPurify
    const cleanTitle = DOMPurify.sanitize(title);
    const cleanDescription = DOMPurify.sanitize(description);
    const cleanStatus = DOMPurify.sanitize(status);
    const cleanStartDate = DOMPurify.sanitize(startDate);
    const cleanEndDate = DOMPurify.sanitize(endDate);
    const cleanTeamId = DOMPurify.sanitize(teamId);

    // Wysłanie danych do serwera
    fetch("updateProject.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                resultDiv.innerHTML = "<span class='success'>Project updated successfully!</span>";

                // Odśwież stronę, aby pokazać zmiany
                setTimeout(() => {
                    window.location.reload();
                }, 1500);

                closeEditProjectWindow();
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to update project'}</span>`;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
        });
});

// Dodaj obsługę przycisku Edit w projekcie
/*
document.querySelector('.btn-link-tasks').addEventListener('click', function() {
    // Pobierz ID projektu z URL lub innego miejsca
    const projectId = <?php echo $_SESSION["selected_project_id"]; ?>;
    toggleEditProjectWindow(projectId);
});*/
