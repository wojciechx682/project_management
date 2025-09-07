<div id="main">
    <!--<h3>Welcome Manager</h3>-->

    <div id="projects">
        <div id="projects-left">
            <a href="projects.php">
                <i class="icon-th-large"></i>
            </a>
        </div>
        <div id="projects-right">
            <div class="tile-title">
                <a href="projects.php">Projects</a>
            </div>
            <?php
                query("SELECT COUNT(*) AS numberOfProjects FROM project JOIN team_user ON team_user.team_id = project.team_id WHERE team_user.user_id = ?", "displayNumberOfProjects", [$_SESSION["id"]]);

                query("SELECT COUNT(*) AS numberOfTasks FROM task JOIN project ON project.id = task.project_id JOIN team_user ON team_user.team_id = project.team_id WHERE team_user.user_id = ?", "displayNumberOfTasks", [$_SESSION["id"]]);
            ?>
        </div>
    </div>
</div>