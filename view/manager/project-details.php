<main>
    <div id="main">
        <h3 style="position: absolute;">Project details</h3>
        <button class="btn-link btn-link-static btn-link-tasks" onclick="toggleEditProjectWindow(<?php echo $_SESSION['selected_project_id']; ?>)">Edit</button>
        <hr>
        <?php
            query("SELECT project.id, project.name, project.description, project.start_date, project.end_date, project.status, project.created_at, project.updated_at, team.name AS team_name, team.id FROM project JOIN team ON project.team_id = team.id WHERE project.id=?", "getProjectDetails", $_SESSION["selected_project_id"]);

            echo '<h3 style="position: absolute;">Tasks</h3>';
            echo '<button class="add-book-button btn-link btn-link-static btn-link-tasks" onclick="toggleTaskWindow()">ADD NEW</button>';
            echo '<hr>';

            query("SELECT task.id, task.title, task.description, task.priority, task.status, task.due_date, task.project_id, task.assigned_user_id, task.created_at, task.updated_at, user.first_name, user.last_name FROM task JOIN user ON task.assigned_user_id = user.id WHERE task.project_id=?", "getTaskDetails", $_SESSION["selected_project_id"]);
        ?>

        <div id="result"></div>

    </div>
</main>