<main>
    <div id="main">
        <h3 style="position: absolute;">Team details</h3>
        <button class="btn-link btn-link-static btn-link-tasks" onclick="toggleEditProjectWindow(<?php echo $_SESSION['selected_project_id']; ?>)">Edit</button>
        <hr>
        <?php
            query("SELECT t.id, t.name, t.created_at, COUNT(tu2.user_id) AS members_count, CONCAT(u.first_name, ' ', u.last_name) AS leader_name FROM team t LEFT JOIN team_user tu2 ON tu2.team_id = t.id LEFT JOIN user u ON u.id = ( SELECT tu3.user_id FROM team_user tu3 JOIN user u2 ON tu3.user_id = u2.id WHERE tu3.team_id = t.id AND u2.role = 'Project Manager' LIMIT 1 ) WHERE t.id = ? GROUP BY t.id, t.name, t.created_at, leader_name ORDER BY t.created_at DESC", "getTeamDetails", $_SESSION["selected_team_id"]);

            echo '<h3 style="position: absolute;">Tasks</h3>';
            echo '<button class="add-book-button btn-link btn-link-static btn-link-tasks" onclick="toggleTaskWindow()">ADD NEW</button>';
            echo '<hr>';

            query("SELECT u.id AS user_id, u.first_name, u.last_name, u.email, u.role, u.created_at, u.updated_at FROM team_user tu JOIN user u ON tu.user_id = u.id WHERE tu.team_id = ? ORDER BY u.created_at ASC;", "getTeamMembers", $_SESSION["selected_team_id"]);

                /*query("SELECT project.id AS project_id, project.name AS project_name, project.description AS project_description, project.start_date, project.end_date, project.status AS project_status, project.created_at AS project_created, project.updated_at AS project_updated, team.name AS team_name, task.id AS task_id, task.title AS task_title, task.description AS task_description, task.priority AS task_priority, task.status AS task_status, task.due_date AS task_due_date, task.assigned_user_id AS assigned_user, task.created_at AS task_created, task.updated_at AS task_updated FROM project JOIN team ON project.team_id = team.id LEFT JOIN task ON task.project_id = project.id WHERE project.id = '%s'", "getProjectDetails", $_SESSION["selected_project_id"]);*/
        ?>

        <div id="result"></div>

    </div>
</main>