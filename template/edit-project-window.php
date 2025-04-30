<div id="edit-project" class="hidden">

    <h2>Edit project</h2>

    <i class="icon-cancel" onclick="closeEditProjectWindow()"></i>
    <hr>

    <form id="edit-project-form">

        <input type="hidden" id="edit-project-id" name="id">

        <div class="project-details">
            <label for="edit-project-title" class="project-details-name-left">Title</label>
            <input type="text" id="edit-project-title" name="title" class="project-details-name" maxlength="255" required>
        </div>

        <div class="project-details">
            <label for="edit-project-description" class="project-details-desc-left">Description</label>
            <textarea id="edit-project-description" name="description" class="project-details-desc" required maxlength="90"></textarea>
        </div>

        <div class="project-details">
            <label for="edit-project-status" class="project-details-status-left">Status</label>
            <select id="edit-project-status" name="status" class="project-details-status" required>
                <option value="">Select status</option>
                <option value="planned">Planned</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div class="project-details">
            <label for="edit-project-start-date" class="project-details-start-date-left">Start Date</label>
            <input type="date" id="edit-project-start-date" name="start_date" class="project-details-start-date" required>
        </div>

        <div class="project-details">
            <label for="edit-project-end-date" class="project-details-end-date-left">End Date</label>
            <input type="date" id="edit-project-end-date" name="end_date" class="project-details-end-date" required>
        </div>

        <div class="project-details">
            <label for="edit-project-team-id" class="team-details-name-left">Team</label>
            <select id="edit-project-team-id" name="team_id" class="team-details-name" required>
                <option value="1">PM App Team</option>
                <?php
                    //query("SELECT team.id, team.name FROM team", "createTeamSelectList", "");
                ?>
            </select>
        </div>

        <div class="project-details project-details-button">
            <button type="submit" class="btn-link btn-link-static btn-submit-project">Save</button>
        </div>
    </form>
</div>