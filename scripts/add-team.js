function toggleTeamWindow() {
    let teamWindow = document.querySelector("#add-team");
    let mainContainer = document.getElementById("main-container");
    if (!teamWindow) return;
    teamWindow.classList.toggle("hidden");
    let icon = document.querySelector(".icon-cancel");
    if (icon) {
        icon.addEventListener("click", closeTeamWindow);
    }
    if (mainContainer) {
        mainContainer.classList.toggle("unreachable");
    }

    fetch("getAvailableUsers.php")
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById("user-id");
            select.innerHTML = "";
            const users = (data.success && data.data && data.data.users) ? data.data.users : [];

            if (users.length > 0) {
                users.forEach(user => {
                    const option = document.createElement("option");
                    option.value = user.id;
                    option.textContent = `${user.first_name} ${user.last_name} (${user.email})`;
                    select.appendChild(option);
                });
            } else {
                const option = document.createElement("option");
                option.value = "";
                option.textContent = "No available users";
                select.appendChild(option);
            }
        })
        .catch(error => {
            console.error("Error loading users:", error);
        });
}

function closeTeamWindow() {
    let teamWindow = document.querySelector("#add-team");
    let mainContainer = document.getElementById("main-container");
    if (teamWindow) teamWindow.classList.add("hidden");
    if (mainContainer) {
        mainContainer.classList.remove("unreachable");
    }
}

document.addEventListener("keydown", function(event) {
    let teamWindow = document.querySelector("#add-team");
    if (!teamWindow || teamWindow.classList.contains("hidden")) return;

    if (event.key === "Escape") {
        teamWindow.classList.add("hidden");
        let mainContainer = document.getElementById("main-container");
        if (mainContainer) {
            mainContainer.classList.remove("unreachable");
        }
    }
});

document.getElementById("add-team-form").addEventListener("submit", function (event) {

    event.preventDefault();

    const resultDiv = document.getElementById("result");
    const teamName = document.getElementById("team-name").value.trim();
    const cleanTeamName = DOMPurify.sanitize(teamName);

    const isValid = (
        cleanTeamName === teamName && cleanTeamName.length > 0 && cleanTeamName.length <= 255
    );

    if (!isValid) {
        resultDiv.innerHTML = "<span class='error'>An error occurred. Please provide valid team name</span>";
        closeTeamWindow();
        setTimeout(() => window.location.reload(), 1500);
        return;
    }

    const formData = new FormData();
    formData.append("team_name", cleanTeamName);

    fetch("addNewTeam.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            closeTeamWindow();
            if (data.success) {
                resultDiv.innerHTML = `<span class='success'>${data.message || 'Team added successfully'}</span>`;
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to add team. Please try again'}</span>`;
            }
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error("Error:", error);
            closeTeamWindow();
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
            setTimeout(() => window.location.reload(), 1500);
        });
});
