function toggleTaskDetails(orderId) {
    console.log("toggleTaskDetails function");
    let removeBox = document.querySelector(".update-status");
    if (!removeBox) return;
    removeBox.classList.toggle("hidden");
    let icon = document.querySelector(".icon-cancel");
    if (icon) {
        icon.addEventListener("click", closeRemoveBox);
    }
}

function closeRemoveBox() {
    console.log("closeRemoveBox function");
    let removeBox = document.querySelector(".update-status");
    let mainContainer = document.getElementById("main-container");
    if (removeBox) removeBox.classList.add("hidden");
    if (mainContainer) {
        mainContainer.classList.remove("bright", "unreachable");
    }
}

document.addEventListener("keydown", function(event) {
    let removeBox = document.querySelector(".update-status");
    if (!removeBox || removeBox.classList.contains("hidden")) return;

    if (event.key === "Escape") {
        removeBox.classList.add("hidden");
        let mainContainer = document.getElementById("main-container");
        if (mainContainer) {
            mainContainer.classList.remove("bright", "unreachable");
        }
    }
});

$("div.task-title a").on("click", function(e) {
    e.preventDefault();
    let onclickAttribute = $(this).attr("onclick");
    let taskId = onclickAttribute.match(/toggleTaskDetails\((\d+)\)/)[1];
    let data = $(this);
    //let result = document.querySelector('div#window');
    //let categoryName = DOMPurify.sanitize(data[0][0].value);

    let mainContainer = document.getElementById("main-container");
    if (mainContainer) {
        mainContainer.classList.toggle("bright");
        mainContainer.classList.toggle("unreachable");
    }

    $.ajax({
        type: "GET",
        url: "getTaskDetails.php",
        data: { taskId: taskId },  // 👈 Przekazujemy taskId jako obiekt
        timeout: 2000
    }).done(function(data) {

        $("div#task-details-window").html(data);

        //console.log(result);

    }).fail(function(data) { // (xhr, status, error)

    }).always(function() {

    });
});

document.querySelectorAll("div.task-title a").forEach(link => {
    link.addEventListener("click", function (e) {
        e.preventDefault();
        let onclickAttribute = this.getAttribute("onclick");
        let taskIdMatch = onclickAttribute.match(/toggleTaskDetails\((\d+)\)/);
        if (!taskIdMatch) return;

        let taskId = taskIdMatch[1];

        let mainContainer = document.getElementById("main-container");
        if (mainContainer) {
            mainContainer.classList.toggle("bright");
            mainContainer.classList.toggle("unreachable");
        }

        fetch("getTaskDetails.php?taskId=" + encodeURIComponent(taskId), {
            method: "GET",
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Błąd sieci!");
                }
                return response.text();
            })
            .then(data => {
                document.querySelector("div#task-details-window").innerHTML = data;
            })
            .catch(error => {
                console.error("Wystąpił błąd:", error);
            });
    });
});

/*function removeOrder(orderId) {

    let removeBox = document.querySelector(".update-status");
    let input = removeBox.querySelector('form.remove-order > input[type="hidden"][name="order-id"]');
    input.value = orderId;
    removeBox.classList.toggle("hidden");
    let mainContainer = document.getElementById("main-container");
    mainContainer.classList.toggle("bright");
    mainContainer.classList.toggle("unreachable");
    let icon = document.querySelector(".icon-cancel");
    let cancelBtn = document.querySelector(".cancel-order");

    buttons = [icon, cancelBtn];
    buttons.forEach(function(button) {
        button.addEventListener("click", closeRemoveBox);
    });

    function closeRemoveBox() {
        mainContainer.classList.toggle("unreachable", false);
        let textarea = removeBox.querySelector("textarea");
        resetError(textarea);
        removeBox.classList.toggle("hidden", true);
        mainContainer.classList.toggle("bright", false);
        removeMessagesAndResetButtons(removeBox);
    }
    function removeMessagesAndResetButtons(removeBox) {
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
    }
    let deliveryDate = document.querySelector(".delivery-date");
    deliveryDate.classList.toggle("hidden", false);
}
function resetError(textarea) {
    let spanError = textarea.nextElementSibling;
    spanError.classList.toggle("hidden", true);
    textarea.value = "";
}
document.addEventListener("keydown", function(event) {

    let removeBox = document.querySelector("div.update-status");
    let mainContainer = document.getElementById("main-container");

    if (!removeBox.classList.contains("hidden")) {

        if (event.key === "Escape") {

            removeBox.classList.toggle("hidden");
            mainContainer.classList.toggle("bright");
            mainContainer.classList.toggle("unreachable");
            let textArea = removeBox.querySelector("textarea");

            resetError(textArea);

            let successMsg = removeBox.querySelector("span.archive-success");
            let errorMsg = removeBox.querySelector("span.update-failed");

            if (successMsg) {
                successMsg.remove();
            }
            if (errorMsg) {
                errorMsg.remove();
            }
            let cancelButton = removeBox.querySelector('button.cancel-order');
            let form = removeBox.querySelector('.remove-order');
            let textarea = removeBox.querySelector('textarea[name="comment"]');
            cancelButton.classList.toggle("hidden", false);
            form.classList.toggle("hidden", false);
        }
    }
});*/
