function toggleUserWindow() {
    let userWindow = document.querySelector("#add-user");
    let mainContainer = document.getElementById("main-container");
    if (!userWindow) return;

    fetch("getAvailableUsers.php")
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById("user-id");
            select.innerHTML = "";
            const users = (data.success && data.data && data.data.users) ? data.data.users : [];

            if (users.length > 0) {
                users.forEach(user => {
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

    userWindow.classList.toggle("hidden");
    if (mainContainer) mainContainer.classList.toggle("unreachable");
}

function closeUserWindow() {
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
            closeUserWindow();
            if (data.success) {
                resultDiv.innerHTML = `<span class='success'>${data.message || 'User added successfully!'}</span>`;
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to add user'}</span>`;
            }
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error("Error:", error);
            closeUserWindow();
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
            setTimeout(() => window.location.reload(), 1500);
        });
});
