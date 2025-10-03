<div id="add-user" class="hidden">
    <h2>Add new team member</h2>
    <i class="icon-cancel" onclick="closeUserWindow()"></i>
    <hr>

    <form id="add-user-form">
        <div class="team-details">
            <label for="user-id" class="team-details-name-left">Select User</label>
            <select id="user-id" name="user_id" class="team-details-name" required>
                <!-- Opcje będą ładowane z bazy -->
            </select>
        </div>

        <div class="project-details project-details-button">
            <button type="submit" class="btn-link btn-link-static btn-submit-project">Add</button>
        </div>
    </form>
</div>
