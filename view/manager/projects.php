<main>
    <div id="main">
        <h3>Projects<button class="add-project-button add-book-button btn-link btn-link-static btn-link-tasks" onclick="toggleProjectWindow()">ADD NEW</button></h3>

        <hr class="projects-hr">

        <div class="project-list">

        <?php
            query("SELECT project.id, project.name, project.description, project.start_date, project.end_date, project.status, project.created_at, project.updated_at, team.name AS team_name FROM project JOIN team ON project.team_id = team.id", "getAllProjects", "");
        ?>

        </div>

        <div id="result"></div>
    </div>
</main>