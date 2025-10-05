let searchTimeout;

document.getElementById("global-search").addEventListener("input", function () {

    clearTimeout(searchTimeout);

    const query = this.value.trim();
    const resultsDiv = document.getElementById("search-results");

    if (query.length < 2) {
        resultsDiv.classList.add("hidden");
        resultsDiv.innerHTML = "";
        return;
    }

    // debounce – czekaj 400 ms po ostatnim wpisanym znaku
    searchTimeout = setTimeout(() => {
        fetch(`../manager/search.php?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                resultsDiv.innerHTML = "";

                if (data.projects.length === 0 && data.teams.length === 0 && data.users.length === 0) {
                    resultsDiv.innerHTML = "<div class='no-results'>No results found</div>";
                } else {
                    // Projekty
                    if (data.projects.length > 0) {
                        resultsDiv.innerHTML += "<h4>Projects</h4>";
                        data.projects.forEach(p => {
                            resultsDiv.innerHTML += `
                                <div class="search-item">
                                    <form action="project-details.php" method="post">
                                        <input type="hidden" name="project-id" value="${p.id}">
                                        <button type="submit">${p.name}</button>
                                    </form>
                                </div>`;
                        });
                    }

                    // Zespoły
                    if (data.teams.length > 0) {
                        resultsDiv.innerHTML += "<h4>Teams</h4>";
                        data.teams.forEach(t => {
                            resultsDiv.innerHTML += `
                                <div class="search-item">
                                    <form action="team-details.php" method="post">
                                        <input type="hidden" name="team-id" value="${t.id}">
                                        <button type="submit">${t.name}</button>
                                    </form>
                                </div>`;
                        });
                    }

                    // Użytkownicy
                    if (data.users.length > 0) {
                        resultsDiv.innerHTML += "<h4>Users</h4>";
                        data.users.forEach(u => {
                            resultsDiv.innerHTML += `
                                <div class="search-item">
                                    <form action="profile.php" method="post">
                                        <input type="hidden" name="user-id" value="${u.id}">
                                        <button type="submit">${u.first_name} ${u.last_name}</button>
                                    </form>
                                </div>`;
                        });
                    }
                }

                resultsDiv.classList.remove("hidden");
            })
            .catch(error => {
                console.error("Search error:", error);
                resultsDiv.innerHTML = "<div class='error'>Error while searching</div>";
                resultsDiv.classList.remove("hidden");
            });
    }, 400);
});
