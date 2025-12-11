<main>
    <div id="main">
        <h3>Projects<button class="add-project-button add-book-button btn-link btn-link-static btn-link-tasks" onclick="toggleProjectWindow()">ADD NEW</button></h3>

        <hr class="projects-hr">

        <div class="project-list">

        <?php

            $projectsPerPage = 13;
            $page = isset($_GET["page"]) && is_numeric($_GET["page"]) ? (int) $_GET["page"] : 1;
            if ($page < 1) $page = 1;
            $offset = ($page - 1) * $projectsPerPage; // przesunięcie, czyli ile projektów pominąć żeby wczytać te z wybranej strony - np. strona 1: offset 0, strona 2: offset 13, strona 3: offset 26

            /*query("SELECT
                            project.id, 
                            project.name, 
                            project.description, 
                            project.start_date, 
                            project.end_date, 
                            project.status, 
                            project.created_at, 
                            project.updated_at, 
                            team.id AS team_id,
                            team.name AS team_name
                        FROM project
                        JOIN team ON project.team_id = team.id
                        JOIN team_user ON team_user.team_id = team.id
                        WHERE team_user.user_id = ?", "getAllProjects", [$_SESSION['id']]);*/


            // Zapytanie zlicza ile wszystkich projektów należy do zespołów, w których jesteś
            $totalProjects = query("SELECT COUNT(*) FROM project 
                                          JOIN team ON project.team_id = team.id
                                          JOIN team_user ON team_user.team_id = team.id
                                          WHERE team_user.user_id = ?",
                                    "getProjectsCount",
                                        [$_SESSION['id']]);

            $totalPages = ceil($totalProjects / $projectsPerPage); // Zaokrąglasz w górę liczbę stron. Np. 27 projektów → 3 strony (bo 13+13+1)

            // paginacja
            query("SELECT 
                            project.id, 
                            project.name, 
                            project.description, 
                            project.start_date, 
                            project.end_date, 
                            project.status, 
                            project.created_at, 
                            project.updated_at, 
                            team.id AS team_id,
                            team.name AS team_name
                        FROM project
                        JOIN team ON project.team_id = team.id
                        JOIN team_user ON team_user.team_id = team.id
                        WHERE team_user.user_id = ?
                        LIMIT $projectsPerPage OFFSET $offset",
                "getAllProjects",
                [$_SESSION['id']]);

        ?>

        </div>

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>"><i class="icon-angle-left"></i></a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo ($i === $page) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>"><i class="icon-angle-right"></i></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div id="result"></div>
    </div>
</main>