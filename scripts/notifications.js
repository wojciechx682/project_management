(function () {
    const wrap = document.getElementById("notification-app");
    if (!wrap) return;

    const btn = document.getElementById("notification-toggle");
    const panel = document.getElementById("notification-panel");
    const listEl = document.getElementById("notification-list");
    const badge = document.getElementById("notification-badge");
    const endpoint = wrap.getAttribute("data-endpoint") || "get-notifications.php";
    const markUrl = wrap.getAttribute("data-mark-read") || "mark-notification-read.php";

    let open = false;

    function setBadge(n) {
        if (!badge) return;
        if (n > 0) {
            badge.textContent = n > 99 ? "99+" : String(n);
            badge.classList.remove("hidden");
        } else {
            badge.textContent = "";
            badge.classList.add("hidden");
        }
    }

    function esc(s) {
        const d = document.createElement("div");
        d.textContent = s == null ? "" : String(s);
        return d.innerHTML;
    }

    function render(items) {
        if (!listEl) return;
        if (!items || items.length === 0) {
            listEl.innerHTML = '<div class="notification-empty">No notifications yet.</div>';
            return;
        }
        listEl.innerHTML = items
            .map(function (it) {
                const read = it.read_at ? " is-read" : "";
                return (
                    '<div class="notification-item' +
                    read +
                    '" data-id="' +
                    esc(it.id) +
                    '">' +
                    '<div class="notification-item-title">' +
                    esc(it.title) +
                    "</div>" +
                    (it.body
                        ? '<div class="notification-item-body">' + esc(it.body) + "</div>"
                        : "") +
                    '<div class="notification-item-meta">' +
                    esc(it.created_at) +
                    "</div>" +
                    "</div>"
                );
            })
            .join("");
    }

    function bindItemClicks() {
        if (!listEl) return;
        listEl.querySelectorAll(".notification-item").forEach(function (el) {
            el.addEventListener("click", function () {
                const id = parseInt(el.getAttribute("data-id"), 10);
                if (!id || el.classList.contains("is-read")) return;
                fetch(markUrl, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ id: id }),
                    credentials: "same-origin",
                })
                    .then(function (r) {
                        return r.json();
                    })
                    .then(function (data) {
                        if (data.success) {
                            el.classList.add("is-read");
                            load();
                        }
                    })
                    .catch(function () {});
            });
        });
    }

    function load() {
        fetch(endpoint, { credentials: "same-origin" })
            .then(function (r) {
                return r.json();
            })
            .then(function (data) {
                if (!data.success) return;
                setBadge(data.unread_count || 0);
                render(data.items || []);
                bindItemClicks();
            })
            .catch(function () {});
    }

    if (btn && panel) {
        btn.addEventListener("click", function (e) {
            e.stopPropagation();
            open = !open;
            panel.classList.toggle("hidden", !open);
            if (open) load();
        });
        document.addEventListener("click", function () {
            if (open) {
                open = false;
                panel.classList.add("hidden");
            }
        });
        panel.addEventListener("click", function (e) {
            e.stopPropagation();
        });
    }

    load();
    setInterval(load, 60000);
})();
