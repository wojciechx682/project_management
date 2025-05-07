// Funkcja do otwierania i zamykania formularza dodawania zadania
function toggleTaskWindow() {
    console.log("toggleTaskWindow function");
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

// Funkcja do zamykania okna formularza
function closeTaskWindow() {
    console.log("closeTaskWindow function");
    let taskWindow = document.querySelector("#add-task");
    let mainContainer = document.getElementById("main-container");
    if (taskWindow) taskWindow.classList.add("hidden");
    if (mainContainer) {
        mainContainer.classList.remove("unreachable");
    }
}

// Zamykanie formularza po naciśnięciu "Esc"
document.addEventListener("keydown", function(event) {
    let taskWindow = document.querySelector("#add-task");
    if (!taskWindow || taskWindow.classList.contains("hidden")) return;

    if (event.key === "Escape") {
        console.log("keydown event on add-task.js script");
        taskWindow.classList.add("hidden");
        let mainContainer = document.getElementById("main-container");
        if (mainContainer) {
            mainContainer.classList.remove("unreachable");
        }
    }
});

// Funkcja walidująca formularz i wysyłająca dane do PHP przy użyciu Fetch API
document.getElementById("add-task-form").addEventListener("submit", function (event) {

    console.log("add-task-form submit event occurred");

    event.preventDefault();  // Zatrzymanie domyślnego działania formularza (przeładowania strony)

    const resultDiv = document.getElementById("result");

    // Pobranie danych z formularza
    const title = document.getElementById("task-title").value.trim();
    const description = document.getElementById("task-description").value.trim();
    const priority = document.getElementById("task-priority").value.trim();
    const status = document.getElementById("task-status").value.trim();
    const dueDate = document.getElementById("task-due-date").value.trim();
    const assignedUser = document.getElementById("task-assigned-user-id").value.trim();

    // Walidacja DOMPurify
    const cleanTitle = DOMPurify.sanitize(title);
    const cleanDescription = DOMPurify.sanitize(description);
    const cleanPriority = DOMPurify.sanitize(priority);
    const cleanStatus = DOMPurify.sanitize(status);
    const cleanDueDate = DOMPurify.sanitize(dueDate);
    const cleanAssignedUser = DOMPurify.sanitize(assignedUser);

    // Walidacja danych
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
                return;
    }

    // Przygotowanie danych do wysłania
    const formData = new FormData();
    formData.append("title", cleanTitle);
    formData.append("description", cleanDescription);
    formData.append("priority", cleanPriority);
    formData.append("status", cleanStatus);
    formData.append("dueDate", cleanDueDate);
    formData.append("assignedUser", cleanAssignedUser);

    // Wysłanie danych do serwera za pomocą Fetch API
    fetch("addNewTask.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = "<span class='success'>Task added successfully!</span>";

                // Generowanie HTML nowego wiersza
                /*const newProjectHTML = `
                    <div class="project project-content">
                        <div class="project-id">${data.id}</div>
                        <div class="project-name">
                            <form action="project-details.php" method="post">
                                <input type="hidden" name="project-id" value="${data.id}">
                                <button class="submit-order-form" type="submit">
                                    ${data.title}
                                </button>
                            </form>
                        </div>
                        <div class="project-desc">${data.description}</div>
                        <div class="project-start-date">${data.start_date}</div>
                        <div class="project-end-date">${data.end_date}</div>
                        <div class="project-status">${data.status}</div>
                        <div class="team-name">${data.team_name}</div>
                    </div>
                `;*/

                const newTaskHTML = `
                    <div class="task task-content">
                        <div class="task-id">${data.id}</div>
                        <div class="task-title">
                            <a href="#" onclick="toggleTaskDetails(${data.id})">${data.title}</a>
                        </div>                        
                        <div class="task-priority">${data.priority}</div>
                        <div class="task-status">${data.status}</div>
                        <div class="task-due-date">${data.due_date}</div>                       
                        <div class="task-assigned-user">${data.assigned_user_first_name + " " + data.assigned_user_last_name}</div>
                        <div class="task-created-at">${data.created_at}</div>                           
                        <div class="task-manage">
                            <div class="task-action-button">
                                Manage <i class="icon-down-open"></i>
                            </div>
                            <div class="task-options-container">
                                <div class="task-action-options hidden">
                                    <div class="task-option" onclick="toggleEditTaskWindow(${data.id})">Edit</div>
                                    <div class="task-option task-option-delete" onclick="toggleDeleteTaskWindow(${data.id})">Delete</div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                /*// Dodaj nowy projekt na koniec listy
                const taskContainer = document.querySelector("#main .task"); // null - gdy nie ma zadań
                const taskList = document.querySelectorAll(".task.task-content"); // Pobierz wszystkie istniejące taski
                const lastTask = taskList[taskList.length - 1]; // Znajdź ostatni task
                // Jeśli istnieją taski, dodaj nowy poniżej ostatniego
                if (lastTask) {
                    lastTask.insertAdjacentHTML("afterend", newTaskHTML);
                } else {
                    // Jeśli nie ma żadnych zadań, dodaj nowy na początku
                    taskContainer.insertAdjacentHTML("beforeend", newTaskHTML); // "Cannot read properties of null (reading 'insertAdjacentHTML')". ponieważ taskContainer to null
                }*/

                const mainDiv = document.getElementById("main");
                // Znajdź element <hr> po przycisku "ADD NEW", aby wstawić listę zadań po nim
                const hrElement = mainDiv.querySelector("hr:last-of-type"); // Zakładamy, że to HR po "ADD NEW"

                // Spróbuj znaleźć kontener nagłówka zadań.
                // Nagłówek ma klasę 'task', ale nie 'task-content'.
                let taskHeaderElement = mainDiv.querySelector(".task:not(.task-content)");

                if (!taskHeaderElement) {
                    // Nagłówek nie istnieje - to jest pierwsze zadanie. Stwórz HTML nagłówka.
                    const taskHeaderHTML = `
                        <div class="task">
                            <div class="task-id"><span class="header-option">ID</span></div>
                            <div class="task-title"><span class="header-option">Title</span></div>
                            <div class="task-priority"><span class="header-option">Priority</span></div>
                            <div class="task-status"><span class="header-option">Status</span></div>
                            <div class="task-due-date"><span class="header-option">Due date</span></div>
                            <div class="task-assigned-user"><span class="header-option">Assigned user</span></div>
                            <div class="task-created-at"><span class="header-option">Created at</span></div>
                            <div class="task-manage"><span class="header-option">Manage</span></div>
                        </div>
                    `;
                    // Wstaw nagłówek, a ZARAZ PO NIM nowe zadanie.
                    // Wstawiamy je po <hr>, a przed <div id="result">
                    if (hrElement) {
                        hrElement.insertAdjacentHTML("afterend", taskHeaderHTML + newTaskHTML);
                    } else {
                        // Fallback, jeśli <hr> nie został znaleziony - wstaw na końcu #main, ale przed #result
                        const resultDivRef = document.getElementById("result");
                        mainDiv.insertBefore(new DOMParser().parseFromString(taskHeaderHTML + newTaskHTML, "text/html").body.firstChild, resultDivRef);
                    }
                } else {
                    // Nagłówek już istnieje, więc po prostu dodaj nowe zadanie na końcu listy zadań.
                    // Zadania są dodawane po elemencie nagłówka, lub po ostatnim .task-content
                    const existingTaskContents = mainDiv.querySelectorAll(".task.task-content");
                    if (existingTaskContents.length > 0) {
                        existingTaskContents[existingTaskContents.length - 1].insertAdjacentHTML("afterend", newTaskHTML);
                    } else {
                        // Nagłówek jest, ale nie ma jeszcze żadnych .task-content, dodaj po nagłówku
                        taskHeaderElement.insertAdjacentHTML("afterend", newTaskHTML);
                    }
                }

                // Opcjonalnie: zamknij okno dodawania projektu
                closeTaskWindow();

            } else {
                resultDiv.innerHTML = "<span class='error'>Failed to add task. Please try again</span>";
                closeTaskWindow();
            }
        })
        .catch(error => {
            console.error("Error:", error);
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
            closeTaskWindow();
        });
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
