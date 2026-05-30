
// Funkcje do otwierania i zamykania okna edycji
function toggleEditTaskWindowUser(taskId) {

    console.log("toggleEditTaskWindowUser function");

    // Pobierz dane zadania (możesz to zrobić przez AJAX lub wcześniej załadować dane)
    fetch(`getTaskDataUser.php?id=${taskId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Wypełnij formularz danymi projektu
            document.getElementById("edit-task-id").value = data.task.id;
            document.getElementById("task-title").textContent = data.task.title;
            document.getElementById("current-status").textContent = data.task.status;

            // Otwórz okno edycji
            let editWindow = document.querySelector("#change-status");
            let mainContainer = document.getElementById("main-container");

            editWindow.classList.remove("hidden");
            mainContainer.classList.add("unreachable");
        }
    })
    .catch(error => {
        console.error("Error:", error);
        document.getElementById("result").innerHTML = "<span class='error'>Failed to load task data</span>";
    });
}
function closeChangeStatusWindow() {
    console.log("closeChangeStatusWindow function");
    let changeStatusWindow = document.querySelector("#change-status");
    let mainContainer = document.getElementById("main-container");

    if (changeStatusWindow) changeStatusWindow.classList.add("hidden");
    if (mainContainer) mainContainer.classList.remove("unreachable");
}

document.addEventListener("keydown", function(event) {
    let changeStatusWindow = document.querySelector("#change-status");
    if (!changeStatusWindow || changeStatusWindow.classList.contains("hidden")) return;

    if (event.key === "Escape") {
        closeChangeStatusWindow();
    }
});

// Obsługa formularza zmiany statusu
document.getElementById("change-status-form").addEventListener("submit", function(event) {

    event.preventDefault();

    const resultDiv = document.getElementById("result");
    const formData = new FormData(this);

    // Walidacja danych
    const taskId = formData.get("id");
    const status = formData.get("status");

    if (!taskId || !status) {
        resultDiv.innerHTML = "<span class='error'>Task ID and status are required</span>";
        return;
    }

    // Walidacja DOMPurify
    const cleanTaskId = DOMPurify.sanitize(taskId);
    const cleanStatus = DOMPurify.sanitize(status);

    formData.set("id", cleanTaskId);
    formData.set("status", cleanStatus);

    // Wysłanie danych do serwera
    fetch("updateTaskStatus.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = "<span class='success'>Task status updated successfully!</span>";

                // Odśwież stronę, aby pokazać zmiany
                setTimeout(() => {
                    window.location.reload();
                }, 1500);

                closeChangeStatusWindow();
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to update task status'}</span>`;
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
    toggleEditTaskWindow(projectId);
});*/
