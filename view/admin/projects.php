<main>
    <div id="main">
        <h3>Projects<button class="add-project-button add-book-button btn-link btn-link-static btn-link-tasks" onclick="toggleProjectWindow()">ADD NEW</button></h3>

        <hr class="projects-hr">

        <div class="project-list">

            <?php

            $projectsPerPage = 13;
            $page = isset($_GET["page"]) && is_numeric($_GET["page"]) ? (int) $_GET["page"] : 1;
            if ($page < 1) $page = 1;
            $offset = ($page - 1) * $projectsPerPage;

            $totalProjects = query(
                "SELECT COUNT(*) FROM project",
                "getProjectsCount",
                []
            );

            $totalPages = $totalProjects ? ceil($totalProjects / $projectsPerPage) : 0;

            query(
                "SELECT
                    p.id,
                    p.name,
                    p.description,
                    p.start_date,
                    p.end_date,
                    p.status,
                    p.created_at,
                    t.name AS team_name,
                    (
                        SELECT CONCAT(u.first_name, ' ', u.last_name)
                        FROM team_user tu
                        JOIN user u ON u.id = tu.user_id
                        WHERE tu.team_id = t.id AND u.role = 'Project Manager'
                        LIMIT 1
                    ) AS leader_name,
                    (
                        SELECT COUNT(*)
                        FROM task tk
                        WHERE tk.project_id = p.id
                    ) AS tasks_count
                FROM project p
                JOIN team t ON t.id = p.team_id
                ORDER BY p.created_at DESC
                LIMIT $projectsPerPage OFFSET $offset",
                "getAllProjectsForAdmin",
                []
            );

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
