<main>
    <div id="main">
        <h3>Users</h3>
        <hr>
        <?php
            query("SELECT user.id, user.first_name, user.last_name, user.email, user.role, user.created_at, user.is_approved FROM user WHERE user.is_approved = 0", "getPendingUsers", []);
        ?>
    </div>
</main>