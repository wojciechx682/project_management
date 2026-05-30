function toggleAddCommentWindow(taskId) {
    const modal = document.getElementById("add-comment");
    const input = document.getElementById("comment-task-id");
    const mainContainer = document.getElementById("main-container");

    if (!modal) return;

    if (input) input.value = taskId;

    modal.classList.toggle("hidden");

    if (!modal.classList.contains("hidden")) {
        if (mainContainer) mainContainer.classList.add("unreachable");
    } else {
        if (mainContainer) mainContainer.classList.remove("unreachable");
    }
}

function closeAddCommentWindow() {
    const modal = document.getElementById("add-comment");
    const mainContainer = document.getElementById("main-container");

    if (modal) modal.classList.add("hidden");
    if (mainContainer) mainContainer.classList.remove("unreachable");
}

document.addEventListener("keydown", function(event) {
    const modal = document.getElementById("add-comment");
    if (!modal || modal.classList.contains("hidden")) return;

    if (event.key === "Escape") {
        closeAddCommentWindow();
    }
});

document.getElementById("add-comment-form").addEventListener("submit", function (event) {

    event.preventDefault();

    const resultDiv = document.getElementById("result");

    const taskId = document.getElementById("comment-task-id").value.trim();
    const content = document.getElementById("comment-content").value.trim();

    const cleanTaskId = DOMPurify.sanitize(taskId);
    const cleanContent = DOMPurify.sanitize(content);

    const isValid = (
        cleanTaskId === taskId && cleanTaskId !== "" &&
        cleanContent === content && cleanContent.length >= 10 && cleanContent.length <= 255
    );

    if (!isValid) {
        resultDiv.innerHTML = "<span class='error'>Please provide a valid comment (10–255 characters)</span>";
        closeAddCommentWindow();
        setTimeout(() => window.location.reload(), 1500);
        return;
    }

    const formData = new FormData();
    formData.append("task_id", cleanTaskId);
    formData.append("content", cleanContent);

    fetch("addNewComment.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            closeAddCommentWindow();
            if (data.success) {
                resultDiv.innerHTML = `<span class='success'>${data.message || 'Comment added successfully'}</span>`;
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to add comment. Please try again.'}</span>`;
            }
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error("Error:", error);
            closeAddCommentWindow();
            resultDiv.innerHTML = "<span class='error'>An unexpected error occurred. Please try again.</span>";
            setTimeout(() => window.location.reload(), 1500);
        });
});
