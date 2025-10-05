
document.querySelectorAll(".team-member-action-button").forEach(button => {
    button.addEventListener("click", () => {

        console.log("toggleUserOptions.js");

        const memberContent = button.closest(".team-member-content");
        const options = memberContent.querySelector(".team-member-action-options");
        const icon = button.querySelector("i");

        // Przełącz widoczność opcji
        options.classList.toggle("hidden");

        // Zamień ikonę strzałki
        if (icon.classList.contains("icon-down-open")) {
            icon.classList.replace("icon-down-open", "icon-up-open");
        } else {
            icon.classList.replace("icon-up-open", "icon-down-open");
        }
    });
});
