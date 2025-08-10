// Funkcja do obsługi formularza usuwania projektu
function handleDeleteProjectForm() {
    const deleteForm = document.querySelector(".delete-project-form");

    deleteForm.addEventListener("submit", function(event) {
        event.preventDefault();
        deleteProject();
    });
}

// Funkcja wysyłająca żądanie usunięcia projektu
function deleteProject() {
    const form = document.querySelector(".delete-project-form");
    const projectId = form.querySelector('input[name="project-id"]').value;
    const resultDiv = document.getElementById("result");
    const errorContainer = document.querySelector(".delete-error-message");

    // Walidacja DOMPurify
    const cleanProjectId = DOMPurify.sanitize(projectId);

    // Walidacja danych
    if (!cleanProjectId || cleanProjectId !== projectId || !Number.isInteger(Number(cleanProjectId))) {
        if (errorContainer) {
            errorContainer.textContent = "Invalid project ID";
        }
        return;
    }

    // Przygotowanie danych
    const formData = new FormData();
    formData.append("project-id", cleanProjectId);

    // Wysłanie żądania
    fetch("deleteProject.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeRemoveProjectBox();

                // Przekierowanie po udanym usunięciu
                window.location.href = "projects.php?delete_success=1";
            } else {
                if (errorContainer) {
                    errorContainer.textContent = data.message || "Failed to delete project";
                }
            }
        })
        .catch(error => {
            console.error("Error:", error);
            if (errorContainer) {
                errorContainer.textContent = "An error occurred. Please try again";
            }
        });
}

// Inicjalizacja po załadowaniu DOM
document.addEventListener("DOMContentLoaded", function() {
    handleDeleteProjectForm();

    // Obsługa przycisku Cancel
    const cancelBtn = document.querySelector(".cancel-order");
    if (cancelBtn) {
        cancelBtn.addEventListener("click", closeRemoveProjectBox);
    }
});

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







