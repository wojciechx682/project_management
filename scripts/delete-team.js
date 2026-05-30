
function toggleDeleteTeamWindow(teamId) {

    console.log("Team ID = " + teamId);

    let modalBox = document.querySelector(".accept-user");

    console.log("modalBox = ");
    console.log(modalBox);

    modalBox.classList.toggle("hidden");

    let mainContainer = document.getElementById("main-container");

    mainContainer.classList.toggle("unreachable");

    let icon = document.querySelector(".icon-cancel");
    let cancelBtn = document.querySelector(".cancel-order");

    console.log("icon = ");
    console.log(icon);

    let buttons = [icon, cancelBtn];
    buttons.forEach(function(button) {
        button.addEventListener("click", closeRemoveBox);
    });

    function closeRemoveBox() {
        mainContainer.classList.toggle("unreachable", false);
        modalBox.classList.toggle("hidden", true);
    }

    let acceptUserContainer = document.querySelector(".accept-user-container");
    acceptUserContainer.classList.toggle("hidden", false);


    let form = document.getElementById("delete-team-form");
    let teamIdInput = form.querySelector('input[name="teamId"]');

    teamIdInput.value = teamId;

    console.log("teamIdInput = ");
    console.log(teamIdInput);

}

function closeDeleteProjectWindow() {
    console.log("closeDeleteProjectWindow function");

    let modalBox = document.querySelector(".accept-user");
    let acceptUserContainer = document.querySelector(".accept-user-container");
    let mainContainer = document.getElementById("main-container");

    if(modalBox) modalBox.classList.add("hidden");
    if(acceptUserContainer) acceptUserContainer.classList.add("hidden");
    if(mainContainer) mainContainer.classList.remove("unreachable");

}

// Obsługa formularza edycji
document.getElementById("delete-team-form").addEventListener("submit", function(event) {

    event.preventDefault();

    //const resultDiv = document.getElementById("result");
    let form = document.getElementById("delete-team-form");
    let teamIdInput = form.querySelector('input[name="teamId"]');
    let teamId = teamIdInput.value;

    fetch(`deleteTeam.php?id=${teamId}`, {
        method: 'DELETE'
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = "teams.php";
            } else {
                window.location.href = "teams.php";
            }
        })
        .catch(error => {
            window.location.href = "teams.php";
        });
});



/*function closeEditProjectWindow() {
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
});*/

// Dodaj obsługę przycisku Edit w projekcie
/*
document.querySelector('.btn-link-tasks').addEventListener('click', function() {
    // Pobierz ID projektu z URL lub innego miejsca
    const projectId = <?php echo $_SESSION["selected_project_id"]; ?>;
    toggleEditProjectWindow(projectId);
});*/
