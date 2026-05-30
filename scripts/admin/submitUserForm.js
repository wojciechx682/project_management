
document.querySelector("form.accept-user-form").addEventListener("submit", function(event) {
    event.preventDefault();

    const form = this;
    const userId = form.elements["user-id"].value;
    const action = form.elements["action"].value;

    const modal = document.querySelector(".accept-user");
    const mainContainer = document.getElementById("main-container");
    const resultDiv = document.getElementById("result");

    const formData = new FormData();
    formData.append("user-id", userId);
    formData.append("action", action);

    fetch("updateUserStatus.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            modal.classList.add("hidden");
            mainContainer.classList.remove("unreachable");

            if (data.success) {
                resultDiv.innerHTML = `<span class='success'>${data.message || 'User status updated'}</span>`;
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to update user'}</span>`;
            }
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(() => {
            modal.classList.add("hidden");
            mainContainer.classList.remove("unreachable");
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
            setTimeout(() => window.location.reload(), 1500);
        });
});
