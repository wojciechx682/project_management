
document.querySelectorAll(".user-action-button").forEach(button => {
    button.addEventListener("click", () => {
        const userContent = button.closest(".users-content");
        const options = userContent.querySelector(".user-action-options");
        const icon = button.querySelector("i");

        options.classList.toggle("hidden");

        if (icon.classList.contains("icon-down-open")) {
            icon.classList.replace("icon-down-open", "icon-up-open");
        } else {
            icon.classList.replace("icon-up-open", "icon-down-open");
        }
    });
});
