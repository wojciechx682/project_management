function toggleTaskWindow() {
    let taskWindow = document.querySelector("#add-task");
    let mainContainer = document.getElementById("main-container");
    if (!taskWindow) return;
    taskWindow.classList.toggle("hidden");
    let icon = document.querySelector(".icon-cancel");
    if (icon) {
        icon.addEventListener("click", closeTaskWindow);
    }
    if (mainContainer) {
        mainContainer.classList.toggle("unreachable");
    }
}

function closeTaskWindow() {
    let taskWindow = document.querySelector("#add-task");
    let mainContainer = document.getElementById("main-container");
    if (taskWindow) taskWindow.classList.add("hidden");
    if (mainContainer) {
        mainContainer.classList.remove("unreachable");
    }
}

document.addEventListener("keydown", function(event) {
    let taskWindow = document.querySelector("#add-task");
    if (!taskWindow || taskWindow.classList.contains("hidden")) return;

    if (event.key === "Escape") {
        taskWindow.classList.add("hidden");
        let mainContainer = document.getElementById("main-container");
        if (mainContainer) {
            mainContainer.classList.remove("unreachable");
        }
    }
});

document.getElementById("add-task-form").addEventListener("submit", function (event) {

    event.preventDefault();

    const resultDiv = document.getElementById("result");

    const title = document.getElementById("task-title").value.trim();
    const description = document.getElementById("task-description").value.trim();
    const priority = document.getElementById("task-priority").value.trim();
    const status = document.getElementById("task-status").value.trim();
    const dueDate = document.getElementById("task-due-date").value.trim();
    const assignedUser = document.getElementById("task-assigned-user-id").value.trim();

    const cleanTitle = DOMPurify.sanitize(title);
    const cleanDescription = DOMPurify.sanitize(description);
    const cleanPriority = DOMPurify.sanitize(priority);
    const cleanStatus = DOMPurify.sanitize(status);
    const cleanDueDate = DOMPurify.sanitize(dueDate);
    const cleanAssignedUser = DOMPurify.sanitize(assignedUser);

    const isValid = (
        cleanTitle === title && cleanTitle.length > 0 && cleanTitle.length <= 255 &&
        cleanDescription === description && cleanDescription.length > 0 && cleanDescription.length <= 90 &&
        cleanPriority === priority && (["low", "medium", "high"].includes(cleanPriority)) &&
        cleanStatus === status && (["not_started", "in_progress", "completed", "cancelled"].includes(cleanStatus)) &&
        cleanDueDate === dueDate && cleanDueDate !== "" &&
        cleanAssignedUser === assignedUser && cleanAssignedUser.length > 0
    );

    if (!isValid) {
        resultDiv.innerHTML = "<span class='error'>An error occurred. Please provide valid information</span>";
        closeTaskWindow();
        setTimeout(() => window.location.reload(), 1500);
        return;
    }

    const formData = new FormData();
    formData.append("title", cleanTitle);
    formData.append("description", cleanDescription);
    formData.append("priority", cleanPriority);
    formData.append("status", cleanStatus);
    formData.append("dueDate", cleanDueDate);
    formData.append("assignedUser", cleanAssignedUser);

    fetch("addNewTask.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            closeTaskWindow();
            if (data.success) {
                resultDiv.innerHTML = `<span class='success'>${data.message || 'Task added successfully!'}</span>`;
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to add task. Please try again'}</span>`;
            }
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error("Error:", error);
            closeTaskWindow();
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
            setTimeout(() => window.location.reload(), 1500);
        });
});
