<main>
    <div id="main">
        <h3 style="position: absolute;">Team details</h3>
        <button class="btn-link btn-link-static btn-link-tasks" onclick="toggleEditTeamWindow(<?php echo $_SESSION['selected_team_id']; ?>)">Edit</button>
        <hr>

        <?php
            query("SELECT t.id, t.name, t.created_at, COUNT(tu2.user_id) AS members_count, CONCAT(u.first_name, ' ', u.last_name) AS leader_name FROM team t LEFT JOIN team_user tu2 ON tu2.team_id = t.id LEFT JOIN user u ON u.id = ( SELECT tu3.user_id FROM team_user tu3 JOIN user u2 ON tu3.user_id = u2.id WHERE tu3.team_id = t.id AND u2.role = 'Project Manager' LIMIT 1 ) WHERE t.id = ? GROUP BY t.id, t.name, t.created_at, leader_name ORDER BY t.created_at DESC", "getTeamDetails", $_SESSION["selected_team_id"]);

            echo '<h3 style="position: absolute;">Members</h3>';
            echo '<button class="add-book-button btn-link btn-link-static btn-link-tasks" onclick="toggleUserWindow()">ADD NEW</button>';
            echo '<hr>';

            query("SELECT u.id AS user_id, u.first_name, u.last_name, u.email, u.role, u.created_at, u.updated_at FROM team_user tu JOIN user u ON tu.user_id = u.id WHERE tu.team_id = ?", "getTeamMembers", $_SESSION["selected_team_id"]);
        ?>

        <div id="result"></div>

    </div>
</main>