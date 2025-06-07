// Funkcje do otwierania i zamykania okna edycji
function toggleDeleteProjectWindow(projectId) {

    console.log("toggleDeleteProjectWindow function");

    /*let editWindow = document.querySelector("#edit-project");
    let mainContainer = document.getElementById("main-container");
    editWindow.classList.remove("hidden");
    mainContainer.classList.add("unreachable");*/

    let modalBox = document.querySelector(".delete-project");
    let projectIdInput = modalBox.querySelector('input[name="project-id"]');

    // ustaw wartości:
    projectIdInput.value = projectId;

    modalBox.classList.toggle("hidden");
    let mainContainer = document.getElementById("main-container");
    mainContainer.classList.toggle("unreachable");

    let icon = document.querySelector(".icon-cancel");
    let cancelBtn = document.querySelector(".cancel-order");

    let buttons = [cancelBtn];
    buttons.forEach(function(button) {
        button.addEventListener("click", closeRemoveProjectBox);
    });

    let deleteProjectContainer = document.querySelector(".delete-project-container");
    deleteProjectContainer.classList.toggle("hidden", false);
}

function closeRemoveProjectBox() {
    let mainContainer = document.getElementById("main-container");
    let modalBox = document.querySelector(".delete-project");
    mainContainer.classList.toggle("unreachable", false);
    modalBox.classList.toggle("hidden", true);
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

    formData.set('title', cleanTitle);
    formData.set('description', cleanDescription);
    formData.set('status', cleanStatus);
    formData.set('start_date', cleanStartDate);
    formData.set('end_date', cleanEndDate);
    formData.set('team_id', cleanTeamId);

    // Wysłanie danych do serwera
    fetch("updateProject.php", {
        method: "POST",
        body: formData // Teraz formData zawiera zaktualizowane, oczyszczone wartości
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
