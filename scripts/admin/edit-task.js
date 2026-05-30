function toggleEditTaskWindow(taskId) {

    const resultDiv = document.getElementById("result");

    fetch(`getTaskData.php?id=${taskId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data && data.data.task) {
            const task = data.data.task;
            document.getElementById("edit-task-id").value = task.id;
            document.getElementById("edit-task-title").value = task.title;
            document.getElementById("edit-task-description").value = task.description;
            document.getElementById("edit-task-priority").value = task.priority.toLowerCase();
            document.getElementById("edit-task-status").value = task.status.toLowerCase().replace(' ', '_');

            const duedate = new Date(task.dueDate).toISOString().split('T')[0];
            document.getElementById("edit-task-due-date").value = duedate;
            document.getElementById("edit-task-assigned-user-id").value = task.assigned_user_id;

            let editWindow = document.querySelector("#edit-task");
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

function closeEditTaskWindow() {
    let editWindow = document.querySelector("#edit-task");
    let mainContainer = document.getElementById("main-container");

    if(editWindow) editWindow.classList.add("hidden");
    if(mainContainer) mainContainer.classList.remove("unreachable");
}

document.addEventListener("keydown", function(event) {
    let editWindow = document.querySelector("#edit-task");
    if(!editWindow || editWindow.classList.contains("hidden")) return;

    if(event.key === "Escape") {
        closeEditTaskWindow();
    }
});

document.getElementById("edit-task-form").addEventListener("submit", function(event) {

    event.preventDefault();

    const resultDiv = document.getElementById("result");
    const formData = new FormData(this);

    fetch("updateTask.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            closeEditTaskWindow();
            if (data.success) {
                resultDiv.innerHTML = `<span class='success'>${data.message || 'Task updated successfully!'}</span>`;
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to update task'}</span>`;
            }
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error("Error:", error);
            closeEditTaskWindow();
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
            setTimeout(() => window.location.reload(), 1500);
        });
});
