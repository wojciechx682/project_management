<div id="main">
    <!--<h3>Welcome Manager</h3>-->

    <div id="projects">
        <div id="projects-left">
            <i class="icon-th-large"></i>
        </div>
        <div id="projects-right">
            <div class="tile-title">
                <a href="projects.php">Projects</a>
            </div>
            <?php
                query("SELECT COUNT(*) AS numberOfProjects FROM project", "displayNumberOfProjects", []);

                query("SELECT COUNT(*) AS numberOfTasks FROM task", "displayNumberOfTasks", []);
            ?>
        </div>
    </div>
</div>