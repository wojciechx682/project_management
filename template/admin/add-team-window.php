<div id="add-team" class="hidden">

    <h2>Add new Team</h2>

    <i class="icon-cancel" onclick="closeTeamWindow()"></i>
    <hr>

    <form id="add-team-form">

        <div class="team-details">
            <label for="team-name" class="team-details-name-left">Team name</label>
            <input type="text" id="team-name" name="title" class="team-details-name" maxlength="255" required>

            <label for="user-id" class="team-details-name-left">Select Leader</label>
            <select id="user-id" name="user_id" class="team-details-name" required>
                <!-- Opcje będą ładowane z bazy -->
            </select>
        </div>

        <div class="project-details project-details-button">
            <button type="submit" class="btn-link btn-link-static btn-submit-project">Add</button>
        </div>

    </form>

</div>
