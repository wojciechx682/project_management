// Funkcje do otwierania i zamykania okna edycji
/*function toggleDeleteProjectWindow(projectId) {

    console.log("toggleDeleteProjectWindow function");

    // Pobierz dane projektu (możesz to zrobić przez AJAX lub wcześniej załadować dane)
    fetch(`getProjectData.php?id=${projectId}`)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Wypełnij formularz danymi projektu
                document.getElementById("edit-project-id").value = data.project.id;
                document.getElementById("edit-project-title").value = data.project.name;
                document.getElementById("edit-project-description").value = data.project.description;
                document.getElementById("edit-project-status").value = data.project.status.toLowerCase().replace(' ', '_');

                // Konwersja dat do formatu YYYY-MM-DD (wymaganego przez input type="date")
                const startDate = new Date(data.project.start_date).toISOString().split('T')[0];
                const endDate = new Date(data.project.end_date).toISOString().split('T')[0];

                document.getElementById("edit-project-start-date").value = startDate;
                document.getElementById("edit-project-end-date").value = endDate;
                document.getElementById("edit-project-team-id").value = data.project.team_id;

                // Otwórz okno edycji
                let editWindow = document.querySelector("#edit-project");
                let mainContainer = document.getElementById("main-container");

                editWindow.classList.remove("hidden");
                mainContainer.classList.add("unreachable");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            document.getElementById("result").innerHTML = "<span class='error'>Failed to load project data</span>";
        });
}*/

function toggleDeleteProjectWindow(projectId) {

    console.log("Project ID = " + projectId);

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


    let form = document.getElementById("delete-project-form");
    let projectIdInput = form.querySelector('input[name="projectId"]');

    projectIdInput.value = projectId;

    console.log("projectIdInput = ");
    console.log(projectIdInput);



    /*fetch(`deleteProject.php?id=${projectId}`, {
        method: 'DELETE'
    })
        .then(response => response.json())
        .then(data => {
            const resultDiv = document.getElementById('result');
            if (data.success) {
                // Remove task from DOM
                document.querySelectorAll('.task-content').forEach(task => {
                    if (task.querySelector('.task-id').textContent.trim() === projectId.toString()) {
                        task.remove();
                    }
                });
                // Usuń nagłówek jeśli nie ma już zadań
                const remainingTasks = document.querySelectorAll('.task-content');
                const taskHeader = document.querySelector('.task:not(.task-content)');
                if (remainingTasks.length === 0 && taskHeader) {
                    taskHeader.remove();
                }
                resultDiv.innerHTML = '<span class="success">Task deleted successfully!</span>';

                // Odśwież stronę, aby pokazać zmiany
                setTimeout(() => {
                    window.location.reload();
                }, 1500);

            } else {
                resultDiv.innerHTML = `<span class="error">${data.message}</span>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('result').innerHTML = '<span class="error">Failed to delete task</span>';
        });*/
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
document.getElementById("delete-project-form").addEventListener("submit", function(event) {

    event.preventDefault();

    //const resultDiv = document.getElementById("result");
    let form = document.getElementById("delete-project-form");
    let projectIdInput = form.querySelector('input[name="projectId"]');
    let projectId = projectIdInput.value;

    fetch(`deleteProject.php?id=${projectId}`, {
        method: 'DELETE'
    })
        .then(response => response.json())
        .then(data => {
            //const resultDiv = document.getElementById('result');
            if (data.success) {

                //resultDiv.innerHTML = '<span class="success">Project deleted successfully</span>';

                // Odśwież stronę, aby pokazać zmiany
                /*setTimeout(() => {
                    window.location.href = "projects.php";
                }, 1500);*/

                window.location.href = "projects.php";
                /*const resultDiv = document.getElementById('result');
                resultDiv.innerHTML = '<span class="success">Project deleted successfully</span>';*/

            } else {
                //resultDiv.innerHTML = `<span class="error">${data.message}</span>`;

                window.location.href = "projects.php";
                /*const resultDiv = document.getElementById('result');
                resultDiv.innerHTML = '<span class="error">${data.message}</span>';*/
            }
        })
        .catch(error => {
            /*console.error('Error:', error);
            document.getElementById('result').innerHTML = '<span class="error">Failed to delete project</span>';*/

            window.location.href = "projects.php";
            /*const resultDiv = document.getElementById('result');
            resultDiv.innerHTML = '<span class="error">Failed to delete project</span>';*/
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
