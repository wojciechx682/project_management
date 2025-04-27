<div id="add-project" class="hidden">

    <h2>Add new project</h2>

    <i class="icon-cancel" onclick="closeProjectWindow()"></i>
    <hr>

    <form id="add-project-form">

        <div class="project-details">
            <label for="project-title" class="project-details-name-left">Title</label>
            <input type="text" id="project-title" name="title" class="project-details-name" required>
        </div>

        <div class="project-details">
            <label for="project-description" class="project-details-desc-left">Description</label>
            <textarea id="project-description" name="description" class="project-details-desc" required maxlength="1000"></textarea>
        </div>

        <div class="project-details">
            <label for="project-priority" class="project-details-priority-left">Priority</label>
            <select id="project-priority" name="priority" class="project-details-priority" required>
                <option value="">Select priority</option>
                <option value="low">Low</option>
                <option value="normal">Normal</option>
                <option value="high">High</option>
            </select>
        </div>

        <div class="project-details">
            <label for="project-status" class="project-details-status-left">Status</label>
            <select id="project-status" name="status" class="project-details-status" required>
                <option value="">Select status</option>
                <option value="planned">Planned</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div class="project-details">
            <label for="project-start-date" class="project-details-start-date-left">Start Date</label>
            <input type="date" id="project-start-date" name="start_date" class="project-details-start-date" required>
        </div>

        <div class="project-details">
            <label for="project-end-date" class="project-details-end-date-left">End Date</label>
            <input type="date" id="project-end-date" name="end_date" class="project-details-end-date" required>
        </div>

        <div class="project-details">
            <label for="project-team-name" class="team-details-name-left">Team Name</label>
            <input type="text" id="project-team-name" name="team_name" class="team-details-name" required>
        </div>

        <div class="project-details">
            <label for="assigned-user" class="assigned-user-left">Assigned User</label>
            <input type="text" id="assigned-user" name="assigned_user" class="assigned-user" required>
        </div>

        <div class="project-details">
            <label for="created-at" class="project-details-created-at-left">Created At</label>
            <input type="date" id="created-at" name="created_at" class="project-details-created-at" required>
        </div>

        <!--<div class="project-details">
            <label for="updated-at" class="team-details-name-left">Updated At</label>
            <input type="date" id="updated-at" name="updated_at" class="team-details-name" required>
        </div>-->

        <div class="project-details project-details-button">
            <button type="submit" class="btn-link btn-link-static btn-submit-project">Add</button>
        </div>

    </form>

</div>
