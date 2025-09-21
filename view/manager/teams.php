<main>
    <div id="main">
        <h3>Teams<button class="add-project-button add-book-button btn-link btn-link-static btn-link-tasks" onclick="toggleTeamWindow()">ADD NEW</button></h3>

        <hr class="projects-hr">

        <div class="team-list">

        <?php
            query("SELECT t.id, t.name, t.created_at, COUNT(tu2.user_id) AS members_count FROM team t JOIN team_user tu ON tu.team_id = t.id LEFT JOIN team_user tu2 ON tu2.team_id = t.id WHERE tu.user_id = ? GROUP BY t.id, t.name, t.created_at ORDER BY t.created_at ASC", "getAllTeamsForProjectManager", [$_SESSION['id']]);
        ?>

        </div>

        <div id="result"></div>
    </div>
</main>