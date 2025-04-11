<nav>
    <div id="nav">
        <div id="top-nav">
            <img src="../assets/img/user1.png">

            <div id="user-info">
                <?php
                    echo $_SESSION["first_name"] . " " . $_SESSION["last_name"];
                    echo "<br>";
                    echo '<span id="role">' . $_SESSION["role"] . '</span>';
                ?>
            </div>

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
        <a href="#">
            <h3>
                <i class="icon-adult"></i>Users
            </h3>
        </a>
        <hr class="nav-hr">


    </div>
</nav>
