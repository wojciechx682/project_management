<main>
    <div id="main">
        <h3>Teams<button class="add-project-button add-book-button btn-link btn-link-static btn-link-tasks" onclick="toggleTeamWindow()">ADD NEW</button></h3>

        <hr class="projects-hr">

        <div class="team-list">

        <?php
            //query("SELECT t.id, t.name, t.created_at, COUNT(tu2.user_id) AS members_count FROM team t JOIN team_user tu ON tu.team_id = t.id LEFT JOIN team_user tu2 ON tu2.team_id = t.id WHERE tu.user_id = ? GROUP BY t.id, t.name, t.created_at ORDER BY t.created_at ASC", "getAllTeamsForProjectManager", [$_SESSION['id']]);

            // Ile rekordów na stronę
            $teamsPerPage = 13;

            // Bieżąca strona (1..N), sanity check
            $page = isset($_GET["page"]) && is_numeric($_GET["page"]) ? (int) $_GET["page"] : 1;
            if ($page < 1) $page = 1;

            // Policz wszystkie zespoły, w których użytkownik jest członkiem (distinct po t.id)
            $totalTeams = query(
                "SELECT COUNT(DISTINCT t.id) 
                   FROM team t 
                   JOIN team_user tu ON tu.team_id = t.id 
                  WHERE tu.user_id = ?",
                "getTeamsCount",
                [$_SESSION['id']]
            );

            // Całkowita liczba stron
            $totalPages = (int) ceil($totalTeams / $teamsPerPage);

            // Jeśli ktoś poda za dużą stronę, zetnij do maksimum (gdy są jakiekolwiek rekordy)
            if ($totalPages > 0 && $page > $totalPages) {
                $page = $totalPages;
            }

            // Offset
            $offset = ($page - 1) * $teamsPerPage;

            // Pobierz zespoły dla danej strony.
            // Używam DISTINCT i podzapytania do policzenia members_count,
            // żeby uniknąć zafałszowania wyników przez JOIN + GROUP BY.
            // (Zwracamy te same kolumny co wcześniej oczekuje callback.)
            query(
                "SELECT DISTINCT 
                        t.id, 
                        t.name, 
                        t.created_at,
                        (SELECT COUNT(*) FROM team_user tu2 WHERE tu2.team_id = t.id) AS members_count
                   FROM team t
                   JOIN team_user tu ON tu.team_id = t.id
                  WHERE tu.user_id = ?
               ORDER BY t.created_at ASC
                  LIMIT $teamsPerPage OFFSET $offset",
                "getAllTeamsForProjectManager",
                [$_SESSION['id']]
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