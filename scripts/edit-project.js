function toggleEditProjectWindow(projectId) {

    const resultDiv = document.getElementById("result");

    fetch(`getProjectData.php?id=${projectId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data && data.data.project) {
                const project = data.data.project;
                document.getElementById("edit-project-id").value = project.id;
                document.getElementById("edit-project-title").value = project.name;
                document.getElementById("edit-project-description").value = project.description;
                document.getElementById("edit-project-status").value = project.status.toLowerCase().replace(' ', '_');

                const startDate = new Date(project.start_date).toISOString().split('T')[0];
                const endDate = new Date(project.end_date).toISOString().split('T')[0];

                document.getElementById("edit-project-start-date").value = startDate;
                document.getElementById("edit-project-end-date").value = endDate;
                document.getElementById("edit-project-team-id").value = project.team_id;

                let editWindow = document.querySelector("#edit-project");
                let mainContainer = document.getElementById("main-container");

                editWindow.classList.remove("hidden");
                mainContainer.classList.add("unreachable");
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to load project data'}</span>`;
                setTimeout(() => window.location.reload(), 1500);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            resultDiv.innerHTML = "<span class='error'>Failed to load project data</span>";
            setTimeout(() => window.location.reload(), 1500);
        });
}

function closeEditProjectWindow() {
    let editWindow = document.querySelector("#edit-project");
    let mainContainer = document.getElementById("main-container");

    if(editWindow) editWindow.classList.add("hidden");
    if(mainContainer) mainContainer.classList.remove("unreachable");
}

document.addEventListener("keydown", function(event) {
    let editWindow = document.querySelector("#edit-project");
    if(!editWindow || editWindow.classList.contains("hidden")) return;

    if(event.key === "Escape") {
        closeEditProjectWindow();
    }
});

document.getElementById("edit-project-form").addEventListener("submit", function(event) {

    event.preventDefault();

    const resultDiv = document.getElementById("result");
    const formData = new FormData(this);

    fetch("updateProject.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            closeEditProjectWindow();
            if (data.success) {
                resultDiv.innerHTML = `<span class='success'>${data.message || 'Project updated successfully!'}</span>`;
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to update project'}</span>`;
            }
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error("Error:", error);
            closeEditProjectWindow();
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
            setTimeout(() => window.location.reload(), 1500);
        });
});
