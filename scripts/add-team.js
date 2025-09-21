// Funkcja do otwierania i zamykania formularza dodawania zespołu
function toggleTeamWindow() {
    console.log("toggleTeamWindow function");
    let teamWindow = document.querySelector("#add-team");
    let mainContainer = document.getElementById("main-container");
    if (!teamWindow) return;
    teamWindow.classList.toggle("hidden");
    let icon = document.querySelector(".icon-cancel");
    if (icon) {
        icon.addEventListener("click", closeTeamWindow);
    }
    if (mainContainer) {
        mainContainer.classList.toggle("unreachable");
    }
}

// Funkcja do zamykania okna formularza
function closeTeamWindow() {
    console.log("closeTeamWindow function");
    let teamWindow = document.querySelector("#add-team");
    let mainContainer = document.getElementById("main-container");
    if (teamWindow) teamWindow.classList.add("hidden");
    if (mainContainer) {
        mainContainer.classList.remove("unreachable");
    }
}

// Zamykanie formularza po naciśnięciu "Esc"
document.addEventListener("keydown", function(event) {
    let teamWindow = document.querySelector("#add-team");
    if (!teamWindow || teamWindow.classList.contains("hidden")) return;

    if (event.key === "Escape") {
        console.log("keydown event on add-team.js script");
        teamWindow.classList.add("hidden");
        let mainContainer = document.getElementById("main-container");
        if (mainContainer) {
            mainContainer.classList.remove("unreachable");
        }
    }
});


// Funkcja walidująca formularz i wysyłająca dane do PHP przy użyciu Fetch API
document.getElementById("add-team-form").addEventListener("submit", function (event) {

    console.log("add-team-form submit event occurred");

    event.preventDefault();  // Zatrzymanie domyślnego działania formularza (przeładowania strony)

    const resultDiv = document.getElementById("result");
    // Pobranie danych z formularza
    const teamName = document.getElementById("team-name").value.trim();
    // Walidacja DOMPurify
    const cleanTeamName = DOMPurify.sanitize(teamName);

    // Walidacja danych
    const isValid = (
        cleanTeamName === teamName && cleanTeamName.length > 0 && cleanTeamName.length <= 255
    );

    if (!isValid) {
        resultDiv.innerHTML = "<span class='error'>An error occurred. Please provide valid team name</span>";
        closeTeamWindow();
        return;
    }

    // Przygotowanie danych do wysłania
    const formData = new FormData();
    formData.append("team_name", cleanTeamName);

    // Wysłanie danych do serwera za pomocą Fetch API
    fetch("addNewTeam.php", {   // <-- analogicznie do addNewProject.php
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = "<span class='success'>Team added successfully!</span>";

                // Generowanie HTML nowego wiersza
                const newTeamHTML = `
                    <div class="team team-content">
                        <div class="team-id">${data.id}</div>
                        <div class="teams-name">
                            <form action="team-details.php" method="post">
                                <input type="hidden" name="team-id" value="${data.id}">
                                <button class="submit-order-form" type="submit">
                                    ${data.team_name}
                                </button>
                            </form>
                        </div>
                        <div class="team-created-at">${data.created_at}</div>
                        <div class="team-members-count">${data.members_count}</div>
                    </div>
                `;

                // Dodaj nowy zespół na koniec listy
                const teamContainer = document.querySelector("#main .team");
                const teamList = document.querySelectorAll(".team.team-content");
                const lastTeam = teamList[teamList.length - 1];

                if (lastTeam) {
                    lastTeam.insertAdjacentHTML("afterend", newTeamHTML);
                } else {
                    teamContainer.insertAdjacentHTML("beforeend", newTeamHTML);
                }

                // Zamknij okno dodawania zespołu
                closeTeamWindow();

            } else {
                /*resultDiv.innerHTML = "<span class='error'>Failed to add team. Please try again</span>";
                closeTeamWindow();*/

                // jeśli backend podał własną wiadomość → pokaż ją
                const errorMsg = data.message ? data.message : "Failed to add team. Please try again";
                resultDiv.innerHTML = `<span class='error'>${errorMsg}</span>`;
                closeTeamWindow();
            }
        })
        .catch(error => {
            console.error("Error:", error);
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
            closeTeamWindow();
        });
});

