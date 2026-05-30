
document.querySelector("form.accept-user-form").addEventListener("submit", function(event) {
    event.preventDefault();

    const form = this;
    const userId = form.elements["user-id"].value;
    const action = form.elements["action"].value;

    const modal = document.querySelector(".accept-user");
    const mainContainer = document.getElementById("main-container");

    const formData = new FormData();
    formData.append("user-id", userId);
    formData.append("action", action);

    fetch("updateUserStatus.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success === true) {
                // Zamknij modal
                modal.classList.add("hidden");
                mainContainer.classList.remove("unreachable");

                // Usuń wiersz użytkownika z DOM
                document.querySelectorAll(".user-id").forEach(function(el) {
                    if (el.textContent.trim() === userId) {
                        const userRow = el.closest(".users-content");
                        if (userRow) {
                            userRow.remove();
                        }
                    }
                });

                // Po usunięciu sprawdź, czy są jeszcze jacyś użytkownicy
                const remainingUsers = document.querySelectorAll(".users-content");
                if (remainingUsers.length === 0) {
                    // Usuń nagłówek tabeli
                    const header = document.querySelector(".users:not(.users-content)");
                    if (header) {
                        header.remove();
                    }

                    // Wyświetl komunikat "No pending users"
                    const main = document.getElementById("main");
                    const message = document.createElement("div");
                    message.textContent = "No pending users";
                    main.appendChild(message);
                }

            } else {
                // Zamknij modal nawet jeśli nieudane
                modal.classList.add("hidden");
                mainContainer.classList.remove("unreachable");
            }
        })
        .catch(() => {
            // W przypadku błędu również po prostu zamknij modal
            modal.classList.add("hidden");
            mainContainer.classList.remove("unreachable");
        });
});
