<main>
    <div id="main">
        <h3 style="position: absolute;">Task details</h3>
        <br><br>
        <hr style="margin: 15px 0 15px 0;">
        <?php
            query("SELECT task.id, task.title, task.description, task.priority, task.status, task.due_date, task.project_id, task.assigned_user_id, task.created_at, task.updated_at, user.first_name, user.last_name, project.name FROM task JOIN user ON task.assigned_user_id = user.id JOIN project ON project.id = task.project_id WHERE task.project_id=? AND task.id=?", "getTasks", [$_SESSION["selected_project_id"], $_SESSION["selected_task_id"]]);

            echo '<h3 style="position: absolute;">Comments</h3>';
            echo '<br><br>';
            echo '<hr style="margin: 15px 0 15px 0;">';

            echo  '<div id="comments"><div class="comments-container">';

            query("SELECT 
                            comment.id AS comment_id,
                            comment.content,
                            comment.created_at,
                            user.first_name,
                            user.last_name
                         FROM comment
                         JOIN user ON comment.user_id = user.id
                         WHERE comment.task_id = ?
                         ORDER BY comment.created_at DESC", "getTaskComments", $_SESSION["selected_task_id"]);

            echo '</div>';
            echo '</div>';
        ?>

        <div id="result"></div>

    </div>
</main>