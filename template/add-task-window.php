<div id="add-task" class="hidden">

    <h2>Add new task</h2>

    <i class="icon-cancel" onclick="closeTaskWindow()"></i><hr>

    <form id="add-task-form">

        <div class="task-details">
            <label for="task-title" class="task-details-name-left">Title</label>
            <input type="text" id="task-title" name="title" class="task-details-name" maxlength="255" required>
        </div>

        <div class="task-details">
            <label for="task-description" class="task-details-desc-left project-details-desc-left">Description</label>
            <textarea id="task-description" name="description" class="task-details-desc project-details-desc" required maxlength="90"></textarea>
        </div>

        <div class="task-details">
            <label for="task-priority" class="task-details-priority-left project-details-status-left">Priority</label>
            <select id="task-priority" name="priority" class="task-details-priority project-details-status" required>
                <option value="">Select priority</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
        </div>

        <div class="task-details">
            <label for="task-status" class="task-details-status-left project-details-status-left">Status</label>
            <select id="task-status" name="status" class="task-details-status project-details-status" required>
                <option value="">Select status</option>
                <option value="not_started">Not Started</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div class="task-details">
            <label for="task-due-date" class="task-details-due-date-left project-details-start-date-left">Due Date</label>
            <input type="date" id="task-due-date" name="due_date" class="task-details-due-date project-details-start-date" required>
        </div>

        <div class="task-details">
            <label for="task-assigned-user-id" class="task-details-assigned-user-left">Assigned User</label>
            <select id="task-assigned-user-id" name="assigned_user_id" class="task-details-assigned-user team-details-name" required>
                <?php
                    query("SELECT user.id, user.first_name, user.last_name FROM user WHERE user.role='Team Member'", "createUserSelectList", []);
                ?>
            </select>
        </div>

        <div class="task-details task-details-button">
            <button type="submit" class="btn-link btn-link-static btn-submit-project">Add</button>
        </div>

    </form>

</div>
