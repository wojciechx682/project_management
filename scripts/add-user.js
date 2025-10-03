function toggleUserWindow() {
    console.log("toggleUserWindow function");
    let userWindow = document.querySelector("#add-user");
    let mainContainer = document.getElementById("main-container");
    if (!userWindow) return;

    // Pobierz dostępnych userów z backendu
    fetch("getAvailableUsers.php")
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById("user-id");
            select.innerHTML = ""; // wyczyść listę

            if (data.success && data.users.length > 0) {
                data.users.forEach(user => {
                    const option = document.createElement("option");
                    option.value = user.id;
                    option.textContent = `${user.first_name} ${user.last_name} (${user.email})`;
                    select.appendChild(option);
                });
            } else {
                const option = document.createElement("option");
                option.value = "";
                option.textContent = "No available users";
                select.appendChild(option);
            }
        })
        .catch(error => {
            console.error("Error loading users:", error);
        });

    // Pokaż modal
    userWindow.classList.toggle("hidden");
    if (mainContainer) mainContainer.classList.toggle("unreachable");
}


function closeUserWindow() {
    console.log("closeUserWindow function");
    let userWindow = document.querySelector("#add-user");
    let mainContainer = document.getElementById("main-container");
    if (userWindow) userWindow.classList.add("hidden");
    if (mainContainer) mainContainer.classList.remove("unreachable");
}

document.addEventListener("keydown", function(event) {
    let userWindow = document.querySelector("#add-user");
    if (!userWindow || userWindow.classList.contains("hidden")) return;
    if (event.key === "Escape") {
        closeUserWindow();
    }
});

document.getElementById("add-user-form").addEventListener("submit", function (event) {

    event.preventDefault();

    const resultDiv = document.getElementById("result");
    const userId = document.getElementById("user-id").value.trim();

    if (!userId) {
        resultDiv.innerHTML = "<span class='error'>Please select a user</span>";
        return;
    }

    const formData = new FormData();
    formData.append("user_id", userId);

    fetch("addNewUser.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = "<span class='success'>User added successfully!</span>";

                const newUserHTML = `
                    <div class="team-member team-member-content">
                        <div class="team-member-id">${data.user.id}</div>
                        <div class="team-member-first-name">${data.user.first_name}</div>
                        <div class="team-member-last-name">${data.user.last_name}</div>
                        <div class="team-member-email">${data.user.email}</div>
                        <div class="team-member-role">${data.user.role}</div>
                        <div class="team-member-created-at">${data.user.created_at}</div>
                        <div class="team-member-updated-at">${data.user.updated_at}</div>
                        <div class="team-member-manage">
                            <div class="team-member-action-button">
                                Manage <i class="icon-down-open"></i>
                            </div>
                        </div>
                    </div>
                `;

                const teamList = document.querySelectorAll(".team-member.team-member-content");
                if (teamList.length > 0) {
                    teamList[teamList.length - 1].insertAdjacentHTML("afterend", newUserHTML);
                } else {
                    const header = document.querySelector(".team-member:not(.team-member-content)");
                    header.insertAdjacentHTML("afterend", newUserHTML);
                }

                closeUserWindow();

                setTimeout(() => {
                    window.location.reload();
                }, 1500);

            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message}</span>`;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
        });
});
