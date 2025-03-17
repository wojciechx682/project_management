<main>
    <div id="main">
        <h3>Projects</h3>
        <hr>
        <?php
            query("SELECT project.id, project.name, project.description, project.start_date, project.end_date, project.status, project.created_at, project.updated_at, team.name AS team_name FROM project JOIN team ON project.team_id = team.id", "getAllProjects", "")
        ?>
    </div>
</main>