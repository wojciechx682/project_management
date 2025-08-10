
function showConfirmationModal(userId, actionType) {

    console.log("showModalBox.js");

    let modalBox = document.querySelector(".accept-user");
    let userIdInput = modalBox.querySelector('input[name="user-id"]');
    let actionInput = modalBox.querySelector('input[name="action"]');

    // ustaw wartości:
    userIdInput.value = userId;
    actionInput.value = actionType; // ← accept lub reject

    let title = modalBox.querySelector("h2");

    if (actionType === "accept") {
        title.innerHTML = "Accept user";
    } else if (actionType === "reject") {
        title.innerHTML = "Reject user";
    }

    modalBox.classList.toggle("hidden");
    let mainContainer = document.getElementById("main-container");
    //mainContainer.classList.toggle("bright");
    mainContainer.classList.toggle("unreachable");
    let icon = document.querySelector(".icon-cancel");
    let cancelBtn = document.querySelector(".cancel-order");

    buttons = [icon, cancelBtn];
    buttons.forEach(function(button) {
        button.addEventListener("click", closeRemoveBox);
    });

    function closeRemoveBox() {
        mainContainer.classList.toggle("unreachable", false);
        modalBox.classList.toggle("hidden", true);
    }
    let acceptUserContainer = document.querySelector(".accept-user-container");
    acceptUserContainer.classList.toggle("hidden", false);
}

document.addEventListener("keydown", function(event) {

    let modalBox = document.querySelector("div.accept-user");
    let mainContainer = document.getElementById("main-container");

    if (!modalBox.classList.contains("hidden")) {

        if (event.key === "Escape") {

            modalBox.classList.toggle("hidden");
            mainContainer.classList.toggle("unreachable");
            let cancelButton = modalBox.querySelector('button.cancel-order');
            let form = modalBox.querySelector('.accept-user-form');
            cancelButton.classList.toggle("hidden", false);
            form.classList.toggle("hidden", false);
        }
    }
});

window.addEventListener("load", () => {
    let users = document.querySelector(".users");
    if (!users) {
        const main = document.getElementById("main");
        const message = document.createElement("div");
        message.textContent = "No pending users";
        main.appendChild(message);
    }
});
