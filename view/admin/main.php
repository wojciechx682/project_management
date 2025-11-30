<div id="main">

    <!--<h3>Welcome Manager</h3>-->

    <!--<div id="projects">
        <div id="projects-left">
            <a href="projects.php">
                <i class="icon-th-large"></i>
            </a>
        </div>
        <div id="projects-right">
            <div class="tile-title">
                <a href="projects.php">Projects</a>
            </div>
            
        </div>
    </div>-->

    <a href="projects.php" class="tile-link">
        <div id="projects" class="tile">
            <div id="projects-left">
                <i class="icon-th-large"></i>
            </div>
            <div id="projects-right">
                <div class="tile-title">Projects</div>
                <?php
                    // Liczba WSZYSTKICH projetków w systemie (admin)
                    query("SELECT COUNT(*) AS numberOfProjects FROM project", "displayNumberOfProjects", []);

                    // Liczba WSZYSTKICH zespołów w systemie
                    query("SELECT COUNT(*) AS numberOfTasks FROM task", "displayNumberOfTasks", []);
                ?>
            </div>
        </div>
    </a>


    <!--<div id="teams">
        <div id="teams-left">
            <a href="teams.php">
                <i class="icon-adult"></i>
            </a>
        </div>
        <div id="teams-right">
            <div class="tile-title">
                <a href="teams.php">Teams</a>
            </div>
           
        </div>
    </div>-->

    <a href="teams.php" class="tile-link">
        <div id="teams" class="tile">
            <div id="teams-left">
                <i class="icon-adult"></i>
            </div>
            <div id="teams-right">
                <div class="tile-title">Teams</div>
                <?php
                    // Liczba WSZYSTKICH zespołów w systemie
                    query("SELECT COUNT(*) AS numberOfTeams
                         FROM team", "displayNumberOfTeams", []);

                    // Liczba unikalnych użytkowników przypisanych do jakiegokolwiek zespołu
                    query("SELECT COUNT(DISTINCT user_id) AS numberOfMembers
                         FROM team_user",
                        "displayNumberOfMembers",
                            []);
                ?>
            </div>
        </div>
    </a>

</div>