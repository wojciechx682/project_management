<header>
    <div id="header">
        <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "Team Member"): ?>
        <div id="notification-app" class="notification-app" data-endpoint="../user/get-notifications.php" data-mark-read="../user/mark-notification-read.php">
            <button type="button" id="notification-toggle" class="notification-toggle" aria-expanded="false" aria-label="Notifications" title="Notifications">
                <span class="notification-bell-icon" aria-hidden="true"><i class="icon-bell"></i></span>
                <span id="notification-badge" class="notification-badge hidden"></span>
            </button>
            <div id="notification-panel" class="notification-panel hidden" role="region" aria-label="Notification list">
                <div class="notification-panel-header">Notifications</div>
                <div id="notification-list" class="notification-list"></div>
            </div>
        </div>
        <?php endif; ?>
        <input type="search" id="global-search" placeholder="Search projects, teams, users...">
        <div id="search-results" class="hidden"></div> <!-- search-results to dropdown/lista, gdzie będziemy wrzucać wyniki. -->
        <button class="btn-link btn-link-static">
            <a href="..\logout.php">
                Log out
            </a>
        </button>
    </div>
</header>
