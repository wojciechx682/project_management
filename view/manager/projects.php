<main>
    <div id="main">
        <h3>Projects<button class="add-project-button add-book-button btn-link btn-link-static btn-link-tasks" onclick="toggleProjectWindow()">ADD NEW</button></h3>

        <hr class="projects-hr">

        <div class="project-list">

        <?php
            /*query("SELECT project.id, project.name, project.description, project.start_date, project.end_date, project.status, project.created_at, project.updated_at, team.name AS team_name FROM project JOIN team ON project.team_id = team.id", "getAllProjects", []);*/
            query("SELECT p.id, p.name, p.description, p.start_date, p.end_date, p.status, p.created_at, p.updated_at, t.name AS team_name
                        FROM project p
                        JOIN team t ON p.team_id = t.id
                        JOIN team_user tu ON t.id = tu.team_id
                        WHERE tu.user_id=?", "getAllProjects", $_SESSION["id"]);
        ?>

        </div>

        <div id="result">
            <?php
                // Wyświetlanie komunikatów, jeśli istnieją
                if (isset($_SESSION["error_message"])) {
                    echo '<span class="error">' . htmlspecialchars($_SESSION["error_message"]) . '</span>';
                    unset($_SESSION["error_message"]);
                }
                if (isset($_SESSION["info_message"])) {
                    echo '<span class="info">' . htmlspecialchars($_SESSION["info_message"]) . '</span>';
                    unset($_SESSION["info_message"]);
                }
            ?>
        </div>
    </div>
</main>