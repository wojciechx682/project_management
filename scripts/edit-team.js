function toggleEditTeamWindow(teamId) {

    const resultDiv = document.getElementById("result");

    fetch(`getTeamData.php?id=${teamId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data && data.data.team) {
                const team = data.data.team;

                document.getElementById("edit-team-id").value = team.id;
                document.getElementById("edit-team-name").value = team.name;

                let editWindow = document.querySelector("#edit-team-window");
                let mainContainer = document.getElementById("main-container");

                if (editWindow && mainContainer) {
                    editWindow.classList.remove("hidden");
                    mainContainer.classList.add("unreachable");
                }
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to load team data'}</span>`;
                setTimeout(() => window.location.reload(), 1500);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            resultDiv.innerHTML = "<span class='error'>Failed to load team data</span>";
            setTimeout(() => window.location.reload(), 1500);
        });
}

function closeEditTeamWindow() {

    let editWindow = document.querySelector("#edit-team-window");
    let mainContainer = document.getElementById("main-container");

    if (editWindow) {
        editWindow.classList.add("hidden");
    }
    if (mainContainer) {
        mainContainer.classList.remove("unreachable");
    }
}

document.addEventListener("keydown", function(event) {
    let editWindow = document.querySelector("#edit-team-window");
    if (!editWindow || editWindow.classList.contains("hidden")) return;

    if (event.key === "Escape") {
        closeEditTeamWindow();
    }
});

document.getElementById("edit-team-form").addEventListener("submit", function(event) {

    event.preventDefault();

    const resultDiv = document.getElementById("result");
    const formData = new FormData(this);

    fetch("updateTeam.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            closeEditTeamWindow();
            if (data.success) {
                resultDiv.innerHTML = `<span class='success'>${data.message || 'Team updated successfully'}</span>`;
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to update team'}</span>`;
            }
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error("Error:", error);
            closeEditTeamWindow();
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
            setTimeout(() => window.location.reload(), 1500);
        });
});
