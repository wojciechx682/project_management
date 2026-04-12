<main>
    <div id="main">
        <h3>Tasks
            <a id="task-kanban" href="tasks-kanban.php">
                <i class="icon-columns"></i>
            </a>
        </h3>

        <hr class="divider-hr">

        <?php
            /*query("SELECT task.id, task.title, task.description, task.priority, task.status, task.due_date, task.project_id, task.assigned_user_id, task.created_at, task.updated_at, user.first_name, user.last_name FROM task JOIN user ON task.assigned_user_id = user.id", "getTaskDetails", []);*/

            query("SELECT task.id, task.title, task.description, task.priority, task.status, task.due_date, task.project_id, task.assigned_user_id, task.created_at, task.updated_at, user.first_name, user.last_name
                         FROM task
                         JOIN user ON task.assigned_user_id = user.id
                         WHERE task.assigned_user_id = ?
                         ORDER BY task.due_date ASC",
                    "getTaskDetailsForUser",
                    [$_SESSION['id']]
            );


        ?>

        <div id="result"></div>

    </div>
</main>