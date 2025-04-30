<main>
    <div id="main">
        <h3 style="position: absolute;">Project details</h3>
        <button class="btn-link btn-link-static btn-link-tasks" onclick="toggleEditProjectWindow(<?php echo $_SESSION['selected_project_id']; ?>)">Edit</button>
        <hr>
        <?php
            query("SELECT project.id, project.name, project.description, project.start_date, project.end_date, project.status, project.created_at, project.updated_at, team.name AS team_name FROM project JOIN team ON project.team_id = team.id WHERE project.id = '%s'", "getProjectDetails", $_SESSION["selected_project_id"]);

            //echo '<h3>Tasks<button class="add-book-button btn-link btn-link-static btn-link-tasks">ADD NEW</button></h3><hr>';
            echo '<h3>Tasks</h3><hr>';

            query("SELECT task.id, task.title, task.description, task.priority, task.status, task.due_date, task.project_id, task.assigned_user_id, task.created_at, task.updated_at, user.first_name, user.last_name FROM task JOIN user ON task.assigned_user_id = user.id WHERE task.project_id = '%s'", "getTaskDetails", $_SESSION["selected_project_id"]);

            /*query("SELECT project.id AS project_id, project.name AS project_name, project.description AS project_description, project.start_date, project.end_date, project.status AS project_status, project.created_at AS project_created, project.updated_at AS project_updated, team.name AS team_name, task.id AS task_id, task.title AS task_title, task.description AS task_description, task.priority AS task_priority, task.status AS task_status, task.due_date AS task_due_date, task.assigned_user_id AS assigned_user, task.created_at AS task_created, task.updated_at AS task_updated FROM project JOIN team ON project.team_id = team.id LEFT JOIN task ON task.project_id = project.id WHERE project.id = '%s'", "getProjectDetails", $_SESSION["selected_project_id"]);*/
        ?>

        <div id="result"></div>

    </div>
</main>