<!-- template/change-status-window.php -->
<div id="change-status" class="hidden">

    <h2>Change task status</h2>
    <i class="icon-cancel" onclick="closeChangeStatusWindow()"></i>
    <hr>

    <form id="change-status-form">
        <!-- Hidden: uzupełnij JS-em przy otwieraniu modala -->
        <input type="hidden" id="edit-task-id" name="id">

        <div class="team-details"> <!-- zamienić na class="task-details" (?) -->
            <label for="task-title" class="team-details-name-left">Task</label>
            <!-- tylko podgląd; wypełnij dynamicznie JS-em -->
            <span id="task-title" class="team-details-name">—</span>
        </div>

        <div class="team-details">
            <label for="current-status" class="team-details-name-left">Current status</label>
            <!-- tylko podgląd; wypełnij dynamicznie JS-em -->
            <span id="current-status" class="team-details-name">—</span>
        </div>

        <div class="team-details">
            <label for="new-status" class="team-details-name-left">New status</label>
            <select id="new-status" name="status" class="team-details-name" required>
                <option value="" disabled selected>Select status</option>
                <option value="not_started">Not started</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <!-- Opcjonalnie: krótki komentarz do zmiany statusu -->
        <!--
        <div class="team-details">
            <label for="status-note" class="team-details-name-left">Note (optional)</label>
            <textarea id="status-note" name="note" class="team-details-name" rows="3" placeholder="Optional note…"></textarea>
        </div>
        -->

        <div class="project-details project-details-button">
            <button type="submit" class="btn-link btn-link-static btn-submit-project">Update</button>
        </div>
    </form>
</div>
