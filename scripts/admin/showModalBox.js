
function showConfirmationModal(userId) {

    let modalBox = document.querySelector(".accept-user");
    let input = modalBox.querySelector('form.accept-user-form > input[type="hidden"][name="user-id"]');
    input.value = userId;
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
        //let textarea = modalBox.querySelector("textarea");
        //resetError(textarea);
        modalBox.classList.toggle("hidden", true);
        //mainContainer.classList.toggle("bright", false);
        //removeMessagesAndResetButtons(modalBox);
    }
    /*function removeMessagesAndResetButtons(removeBox) {
        const spanMsgs = removeBox.querySelectorAll("span.archive-success, span.update-failed");
        const cancelButton = removeBox.querySelector('button.cancel-order');
        const form = removeBox.querySelector('.remove-order');
        for(let i = 0; i < spanMsgs.length; i++) {
            let resultMsg = spanMsgs[i];
            if(resultMsg) {
                resultMsg.remove();
            }
        }
        cancelButton.classList.toggle("hidden", false);
        form.classList.toggle("hidden", false);
    }*/
    let acceptUserContainer = document.querySelector(".accept-user-container");
    acceptUserContainer.classList.toggle("hidden", false);
}
/*function resetError(textarea) {
    let spanError = textarea.nextElementSibling;
    spanError.classList.toggle("hidden", true);
    textarea.value = "";
}*/
document.addEventListener("keydown", function(event) {

    let modalBox = document.querySelector("div.accept-user");
    let mainContainer = document.getElementById("main-container");

    if (!modalBox.classList.contains("hidden")) {

        if (event.key === "Escape") {

            modalBox.classList.toggle("hidden");
            //mainContainer.classList.toggle("bright");
            mainContainer.classList.toggle("unreachable");
            //let textArea = modalBox.querySelector("textarea");

            //resetError(textArea);

            //let successMsg = modalBox.querySelector("span.archive-success");
            //let errorMsg = modalBox.querySelector("span.update-failed");

            /*if (successMsg) {
                successMsg.remove();
            }
            if (errorMsg) {
                errorMsg.remove();
            }*/
            let cancelButton = modalBox.querySelector('button.cancel-order');
            let form = modalBox.querySelector('.accept-user-form');
            //let textarea = modalBox.querySelector('textarea[name="comment"]');
            cancelButton.classList.toggle("hidden", false);
            form.classList.toggle("hidden", false);
        }
    }
});

window.addEventListener("load", () => {
    let users = document.querySelector(".users");
    if (!users) {
        document.getElementById("content").append("Brak przypisanych zamówień");
    }
});
