
function toggleEditTaskWindowUser(taskId) {

    const resultDiv = document.getElementById("result");

    fetch(`getTaskDataUser.php?id=${taskId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data && data.data.task) {
            const task = data.data.task;
            document.getElementById("edit-task-id").value = task.id;
            document.getElementById("task-title").textContent = task.title;
            document.getElementById("current-status").textContent = task.status;

            let editWindow = document.querySelector("#change-status");
            let mainContainer = document.getElementById("main-container");

            editWindow.classList.remove("hidden");
            mainContainer.classList.add("unreachable");
        } else {
            resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to load task data'}</span>`;
            setTimeout(() => window.location.reload(), 1500);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        resultDiv.innerHTML = "<span class='error'>Failed to load task data</span>";
        setTimeout(() => window.location.reload(), 1500);
    });
}

function closeChangeStatusWindow() {
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

document.getElementById("change-status-form").addEventListener("submit", function(event) {

    event.preventDefault();

    const resultDiv = document.getElementById("result");
    const formData = new FormData(this);

    const taskId = formData.get("id");
    const status = formData.get("status");

    if (!taskId || !status) {
        resultDiv.innerHTML = "<span class='error'>Task ID and status are required</span>";
        return;
    }

    const cleanTaskId = DOMPurify.sanitize(taskId);
    const cleanStatus = DOMPurify.sanitize(status);

    formData.set("id", cleanTaskId);
    formData.set("status", cleanStatus);

    fetch("updateTaskStatus.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            closeChangeStatusWindow();
            if (data.success) {
                resultDiv.innerHTML = `<span class='success'>${data.message || 'Task status updated successfully!'}</span>`;
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to update task status'}</span>`;
            }
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error("Error:", error);
            closeChangeStatusWindow();
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
            setTimeout(() => window.location.reload(), 1500);
        });
});
