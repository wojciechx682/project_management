<div id="main">
    <div id="projects">
        <i class="icon-th-large"></i>
        <span>Projects</span>
        <hr>
        <?php
            query("SELECT COUNT(*) AS total_projects FROM project", "getTotalProjects", []);
        ?> project
    </div>
</div>