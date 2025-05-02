function toggleTaskDetails() {
    console.log("toggleTaskDetails function");
    let taskDetailsWindow = document.querySelector("#task-details");
    if (!taskDetailsWindow) return;
    taskDetailsWindow.classList.toggle("hidden");
    let icon = document.querySelector(".icon-cancel");
    if (icon) {
        icon.addEventListener("click", closeTaskDetails);
    }
}

function closeTaskDetails() {
    console.log("closeTaskDetails function");
    let taskDetailsWindow = document.querySelector("#task-details");
    let mainContainer = document.getElementById("main-container");
    if (taskDetailsWindow) taskDetailsWindow.classList.add("hidden");
    if (mainContainer) {
        mainContainer.classList.remove("unreachable");
    }
}

document.addEventListener("keydown", function(event) {
    let taskDetailsWindow = document.querySelector("#task-details");
    if (!taskDetailsWindow || taskDetailsWindow.classList.contains("hidden")) return;

    if (event.key === "Escape") {
        taskDetailsWindow.classList.add("hidden");
        let mainContainer = document.getElementById("main-container");
        if (mainContainer) {
            mainContainer.classList.remove("unreachable");
        }
    }
});

document.querySelectorAll("div.task-title a").forEach(link => {

    link.addEventListener("click", function (e) {

        e.preventDefault();

        let onclickAttribute = this.getAttribute("onclick");
        let taskIdMatch = onclickAttribute.match(/toggleTaskDetails\((\d+)\)/);

        // Walidacja ID zadania
        if (!taskIdMatch || !/^\d+$/.test(taskIdMatch[1])) {
            showError("Invalid task ID");
                return;
        }

        let taskId = taskIdMatch[1];

        let mainContainer = document.getElementById("main-container");
        if (mainContainer) {
            mainContainer.classList.toggle("unreachable");
        }

        fetch(`getTaskDetails.php?taskId=${encodeURIComponent(taskId)}`, {
            method: "GET",
            headers: {
                "Accept": "application/json, text/html"
            }
        })
        .then(response => {
            if (!response.ok) throw new Error("Network response was not ok");

            const contentType = response.headers.get("content-type");
            if (contentType && contentType.includes("application/json")) {
                return response.json();
            }
            return response.text();
        })
        .then(data => {
            if (typeof data === "object") {
                // Obsługa JSON (błąd)
                throw new Error(data.error || "Unknown error");
            } else {
                // Oczyszczanie i wstawianie HTML
                const cleanHTML = DOMPurify.sanitize(data);
                const taskDetailsWindow = document.getElementById("task-details-window");
                taskDetailsWindow.innerHTML = cleanHTML;
                taskDetailsWindow.classList.remove("hidden");

                // Dodaj obsługę zamknięcia okna
                const closeIcon = taskDetailsWindow.querySelector(".icon-cancel");
                if (closeIcon) {
                    closeIcon.addEventListener("click", () => {
                        taskDetailsWindow.classList.add("hidden");
                        mainContainer.classList.remove("unreachable");
                    });
                }
                toggleTaskDetails();
            }
        })
        .catch(error => {
            showError(error.message);
            mainContainer.classList.remove("unreachable");
        });
    });
});

// Funkcja do wyświetlania błędów
function showError(message) {
    const resultDiv = document.getElementById("result");
    if (resultDiv) {
        resultDiv.innerHTML = `<span class="error">${DOMPurify.sanitize(message)}</span>`;
        setTimeout(() => {
            resultDiv.innerHTML = "";
        }, 5000);
    }
}

/*$("div.task-title a").on("click", function(e) {
    e.preventDefault();
    let onclickAttribute = $(this).attr("onclick");
    let taskId = onclickAttribute.match(/toggleTaskDetails\((\d+)\)/)[1];
    //let data = $(this);
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
});*/



/*function removeOrder(orderId) {
z
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
        button.addEventListener("click", closeTaskDetails);
    });

    function closeTaskDetails() {
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
