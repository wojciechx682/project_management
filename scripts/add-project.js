function toggleProjectWindow() {
    console.log("toggleProjectWindow function");
    let projectWindow = document.querySelector("#add-project");
    let mainContainer = document.getElementById("main-container");
    if (!projectWindow) return;
    projectWindow.classList.toggle("hidden");
    let icon = document.querySelector(".icon-cancel");
    if (icon) {
        icon.addEventListener("click", closeProjectWindow);
    }
    if (mainContainer) {
        mainContainer.classList.toggle("unreachable");
    }
}

function closeProjectWindow() {
    console.log("closeProjectWindow function");
    let projectWindow = document.querySelector("#add-project");
    let mainContainer = document.getElementById("main-container");
    if (projectWindow) projectWindow.classList.add("hidden");
    if (mainContainer) {
        mainContainer.classList.remove("unreachable");
    }
}

document.addEventListener("keydown", function(event) {
    let projectWindow = document.querySelector("#add-project");
    if (!projectWindow || projectWindow.classList.contains("hidden")) return;

    if (event.key === "Escape") {
        console.log("keydown event on add-project.js script");
        projectWindow.classList.add("hidden");
        let mainContainer = document.getElementById("main-container");
        if (mainContainer) {
            mainContainer.classList.remove("unreachable");
        }
    }
});

/*document.querySelectorAll("div.task-title a").forEach(link => {
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
                toggleTaskDetails(taskId);
            })
            .catch(error => {
                console.error("Wystąpił błąd:", error);
            });
    });
});*/
