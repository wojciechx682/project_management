<div id="edit-team-window" class="hidden">

    <h2>Edit team</h2>

    <i class="icon-cancel" onclick="closeEditTeamWindow()"></i>
    <hr>

    <form id="edit-team-form">

        <input type="hidden" id="edit-team-id" name="team_id">

        <!--<div class="team-details">
            <label for="team-name" class="team-details-name-left">Team name</label>
            <input type="text" id="team-name" name="title" class="team-details-name" maxlength="255" required>
        </div>-->

        <div class="team-details">
            <label for="edit-team-name" class="team-details-name-left">Team name</label>
            <input type="text" id="edit-team-name" name="team_name" class="team-details-name" maxlength="255" required>
        </div>

        <div class="project-details project-details-button">
            <button type="submit" class="btn-link btn-link-static btn-submit-project">Save</button>
        </div>

    </form>

</div>