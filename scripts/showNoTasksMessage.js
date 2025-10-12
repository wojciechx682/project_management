window.addEventListener("load", () => {
    let tasks = document.querySelector(".task");
    if (!tasks) {
        const main = document.getElementById("main");
        const message = document.createElement("div");
        message.textContent = "No tasks assigned";
        main.appendChild(message);
    }
});