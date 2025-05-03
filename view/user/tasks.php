<main>
    <div id="main">
        <h3>Tasks</h3>
        <hr>
        <?php
        query("SELECT task.id, task.title, task.description, task.priority, task.status, task.due_date, task.project_id, task.assigned_user_id, task.created_at, task.updated_at, user.first_name, user.last_name FROM task JOIN user ON task.assigned_user_id = user.id", "getTaskDetails", []);

        /*query("SELECT project.id AS project_id, project.name AS project_name, project.description AS project_description, project.start_date, project.end_date, project.status AS project_status, project.created_at AS project_created, project.updated_at AS project_updated, team.name AS team_name, task.id AS task_id, task.title AS task_title, task.description AS task_description, task.priority AS task_priority, task.status AS task_status, task.due_date AS task_due_date, task.assigned_user_id AS assigned_user, task.created_at AS task_created, task.updated_at AS task_updated FROM project JOIN team ON project.team_id = team.id LEFT JOIN task ON task.project_id = project.id WHERE project.id = '%s'", "getProjectDetails", $_SESSION["selected_project_id"]);*/
        ?>
    </div>
</main>