function toggleProjectWindow() {
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

function closeProjectWindow() {
    let projectWindow = document.querySelector("#add-project");
    let mainContainer = document.getElementById("main-container");
    if (projectWindow) projectWindow.classList.add("hidden");
    if (mainContainer) {
        mainContainer.classList.remove("unreachable");
    }
}

document.addEventListener("keydown", function(event) {
    let projectWindow = document.querySelector("#add-project");
    if (!projectWindow || projectWindow.classList.contains("hidden")) return;

    if (event.key === "Escape") {
        projectWindow.classList.add("hidden");
        let mainContainer = document.getElementById("main-container");
        if (mainContainer) {
            mainContainer.classList.remove("unreachable");
        }
    }
});

document.getElementById("add-project-form").addEventListener("submit", function (event) {

    event.preventDefault();

    const resultDiv = document.getElementById("result");

    const title = document.getElementById("project-title").value.trim();
    const description = document.getElementById("project-description").value.trim();
    const status = document.getElementById("project-status").value.trim();
    const startDate = document.getElementById("project-start-date").value.trim();
    const endDate = document.getElementById("project-end-date").value.trim();
    const teamId = document.getElementById("project-team-id").value.trim();

    const cleanTitle = DOMPurify.sanitize(title);
    const cleanDescription = DOMPurify.sanitize(description);
    const cleanStatus = DOMPurify.sanitize(status);
    const cleanStartDate = DOMPurify.sanitize(startDate);
    const cleanEndDate = DOMPurify.sanitize(endDate);
    const cleanTeamId = DOMPurify.sanitize(teamId);

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
        setTimeout(() => window.location.reload(), 1500);
        return;
    }

    const formData = new FormData();
    formData.append("title", cleanTitle);
    formData.append("description", cleanDescription);
    formData.append("status", cleanStatus);
    formData.append("start_date", cleanStartDate);
    formData.append("end_date", cleanEndDate);
    formData.append("team_id", cleanTeamId);

    fetch("addNewProject.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            closeProjectWindow();
            if (data.success) {
                resultDiv.innerHTML = `<span class='success'>${data.message || 'Project added successfully!'}</span>`;
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to add project. Please try again'}</span>`;
            }
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error("Error:", error);
            closeProjectWindow();
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
            setTimeout(() => window.location.reload(), 1500);
        });
});
