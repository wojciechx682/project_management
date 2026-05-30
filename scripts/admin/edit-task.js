// Funkcje do otwierania i zamykania okna edycji
function toggleEditTaskWindow(taskId) {

    console.log("toggleEditTaskWindow function");

    // Pobierz dane zadania (możesz to zrobić przez AJAX lub wcześniej załadować dane)
    fetch(`getTaskData.php?id=${taskId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Wypełnij formularz danymi projektu
            document.getElementById("edit-task-id").value = data.task.id;
            document.getElementById("edit-task-title").value = data.task.title;
            document.getElementById("edit-task-description").value = data.task.description;
            document.getElementById("edit-task-priority").value = data.task.priority.toLowerCase();
            document.getElementById("edit-task-status").value = data.task.status.toLowerCase().replace(' ', '_');

            // Konwersja dat do formatu YYYY-MM-DD (wymaganego przez input type="date")
            const duedate = new Date(data.task.dueDate).toISOString().split('T')[0];

            document.getElementById("edit-task-due-date").value = duedate;
            document.getElementById("edit-task-assigned-user-id").value = data.task.assigned_user_id;

            // Otwórz okno edycji
            let editWindow = document.querySelector("#edit-task");
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

function closeEditTaskWindow() {
    console.log("closeEditTaskWindow function");
    let editWindow = document.querySelector("#edit-task");
    let mainContainer = document.getElementById("main-container");

    if(editWindow) editWindow.classList.add("hidden");
    if(mainContainer) mainContainer.classList.remove("unreachable");
}

// Zamykanie formularza po naciśnięciu Esc
document.addEventListener("keydown", function(event) {
    let editWindow = document.querySelector("#edit-task");
    if(!editWindow || editWindow.classList.contains("hidden")) return;

    if(event.key === "Escape") {
        closeEditTaskWindow();
    }
});

// Obsługa formularza edycji
document.getElementById("edit-task-form").addEventListener("submit", function(event) {

    event.preventDefault();

    const resultDiv = document.getElementById("result");
    const formData = new FormData(this);

    // Walidacja danych
    const title = formData.get("title").trim();
    const description = formData.get("description").trim();
    const priority = formData.get("priority");
    const status = formData.get("status");
    const dueDate = formData.get("due_date");
    const assignedUserId = formData.get("assigned_user_id");

    // Walidacja DOMPurify
    const cleanTitle = DOMPurify.sanitize(title);
    const cleanDescription = DOMPurify.sanitize(description);
    const cleanPriority = DOMPurify.sanitize(priority);
    const cleanStatus = DOMPurify.sanitize(status);
    const cleanDueDate = DOMPurify.sanitize(dueDate);
    const cleanAssignedUserId = DOMPurify.sanitize(assignedUserId);

    // Wysłanie danych do serwera
    fetch("updateTask.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                resultDiv.innerHTML = "<span class='success'>Task updated successfully!</span>";

                // Odśwież stronę, aby pokazać zmiany
                setTimeout(() => {
                    window.location.reload();
                }, 1500);

                closeEditTaskWindow();
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to update task'}</span>`;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
        });
});

