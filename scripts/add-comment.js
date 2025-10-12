function toggleAddCommentWindow(taskId) {
    console.log("toggleAddCommentWindow function");
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