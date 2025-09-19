<nav>
    <div id="nav">
        <div id="top-nav">
            <a href="profile.php" class="profile-link">
                <img src="../assets/img/user1.png" alt="User profile picture">
                <div id="user-info">
                    <?php
                        echo $_SESSION["first_name"] . " " . $_SESSION["last_name"];
                        echo "<br>";
                        echo '<span id="role">' . $_SESSION["role"] . '</span>';
                    ?>
                </div>
            </a>
        </div>

        <a href="manager.php">
            <h3>
                <i class="icon-home"></i>Home
            </h3>
        </a>
        <hr class="nav-hr">
        <a href="projects.php">
            <h3>
                <i class="icon-th-large"></i>Projects
            </h3>
        </a>
        <hr class="nav-hr">
        <a href="#">
            <h3>
                <i class="icon-tasks"></i>Tasks
            </h3>
        </a>
        <hr class="nav-hr">
        <a href="teams.php">
            <h3>
                <i class="icon-adult"></i>Teams
            </h3>
        </a>
        <hr class="nav-hr">


    </div>
</nav>
