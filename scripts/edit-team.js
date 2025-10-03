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

function closeEditTeamWindow() {

    console.log("closeEditTeamWindow function");

    let editWindow = document.querySelector("#edit-team-window");
    let mainContainer = document.getElementById("main-container");

    if (editWindow) {
        editWindow.classList.add("hidden");
    }
    if (mainContainer) {
        mainContainer.classList.remove("unreachable");
    }
}

// Zamykanie formularza edycji zespołu po naciśnięciu Esc
document.addEventListener("keydown", function(event) {
    let editWindow = document.querySelector("#edit-team-window");
    if (!editWindow || editWindow.classList.contains("hidden")) return;

    if (event.key === "Escape") {
        closeEditTeamWindow();
    }
});


// Obsługa formularza edycji
// Obsługa formularza edycji zespołu
document.getElementById("edit-team-form").addEventListener("submit", function(event) {

    event.preventDefault();

    const resultDiv = document.getElementById("result");
    const formData = new FormData(this);

    // Pobranie i walidacja danych
    const teamId = formData.get("team_id");
    const teamName = formData.get("team_name").trim();

    const cleanTeamId = DOMPurify.sanitize(teamId);
    const cleanTeamName = DOMPurify.sanitize(teamName);

    if (!cleanTeamId || !cleanTeamName) {
        resultDiv.innerHTML = "<span class='error'>Please provide a valid team name</span>";
        return;
    }

    // Wysłanie danych do serwera
    fetch("updateTeam.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = "<span class='success'>Team updated successfully</span>";

                // Odśwież stronę, aby pokazać zmiany
                setTimeout(() => {
                    window.location.reload();
                }, 1500);

                closeEditTeamWindow();
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to update team'}</span>`;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
        });
});
