function toggleAddCommentWindow(taskId) {
    console.log("toggleEditCommentWindow function");
    const modal = document.getElementById("add-comment");
    const input = document.getElementById("comment-task-id");
    const mainContainer = document.getElementById("main-container");

    if (!modal) return;

    // Ustaw ID zadania w ukrytym polu
    if (input) input.value = taskId;

    // Przełącz widoczność okna modalnego
    modal.classList.toggle("hidden");

    // Jeśli modal jest widoczny, zablokuj interakcję z tłem
    if (!modal.classList.contains("hidden")) {
        if (mainContainer) mainContainer.classList.add("unreachable");
    } else {
        if (mainContainer) mainContainer.classList.remove("unreachable");
    }
}

function closeAddCommentWindow() {
    console.log("closeAddCommentWindow function");
    const modal = document.getElementById("add-comment");
    const mainContainer = document.getElementById("main-container");

    if (modal) modal.classList.add("hidden");
    if (mainContainer) mainContainer.classList.remove("unreachable");
}

// Zamknij okno po naciśnięciu Esc
document.addEventListener("keydown", function(event) {
    const modal = document.getElementById("add-comment");
    if (!modal || modal.classList.contains("hidden")) return;

    if (event.key === "Escape") {
        closeAddCommentWindow();
    }
});

// ========================
//  Obsługa formularza dodawania komentarza
// ========================

document.getElementById("add-comment-form").addEventListener("submit", function (event) {

    console.log("add-comment-form submit event occurred");

    event.preventDefault(); // zatrzymaj przeładowanie strony

    const resultDiv = document.getElementById("result");

    // ========================
    //  Pobranie danych z formularza
    // ========================
    const taskId = document.getElementById("comment-task-id").value.trim();
    const content = document.getElementById("comment-content").value.trim();

    // ========================
    //  Walidacja DOMPurify
    // ========================
    const cleanTaskId = DOMPurify.sanitize(taskId);
    const cleanContent = DOMPurify.sanitize(content);

    // ========================
    //  Walidacja danych
    // ========================
    const isValid = (
        cleanTaskId === taskId && cleanTaskId !== "" &&
        cleanContent === content && cleanContent.length >= 10 && cleanContent.length <= 255
    );

    if (!isValid) {
        resultDiv.innerHTML = "<span class='error'>Please provide a valid comment (10–255 characters)</span>";
        closeAddCommentWindow();
        return;
    }

    // ========================
    //  Przygotowanie danych do wysłania
    // ========================
    const formData = new FormData();
    formData.append("task_id", cleanTaskId);
    formData.append("content", cleanContent);

    // ========================
    //  Wysłanie danych do serwera
    // ========================
    fetch("addNewComment.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {

            // console.log("Server response:", data);

            if (data.success) {
                resultDiv.innerHTML = "<span class='success'>Comment added successfully</span>";

                // Zamknij okno po sukcesie
                closeAddCommentWindow();

                // Opcjonalnie: wyczyść pole formularza
                document.getElementById("comment-content").value = "";

                // Odśwież stronę, aby pokazać zmiany
                setTimeout(() => {
                    window.location.reload();
                }, 1500);

            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || "Failed to add comment. Please try again."}</span>`;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            resultDiv.innerHTML = "<span class='error'>An unexpected error occurred. Please try again.</span>";
        });
});
