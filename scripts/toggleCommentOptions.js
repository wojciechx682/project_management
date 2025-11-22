
document.querySelectorAll(".task-action-button").forEach(button => {
    button.addEventListener("click", () => {

        console.log("toggleTaskOptions.js");

        const taskContent = button.closest(".comment-content");
        const options = taskContent.querySelector(".task-action-options");
        const icon = button.querySelector("i");

        options.classList.toggle("hidden");

        if (icon.classList.contains("icon-down-open")) {
            icon.classList.replace("icon-down-open", "icon-up-open");
        } else {
            icon.classList.replace("icon-up-open", "icon-down-open");
        }
    });
});
