<div id="main">
    <div id="profile-container">
        <form id="profile-form" action="../update-profile.php" method="post">
            <label for="first_name">First Name</label>
            <input type="text" id="firstName" name="firstName"
                   value="<?= htmlspecialchars($_SESSION['first_name']) ?>" required>

            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" name="lastName"
                   value="<?= htmlspecialchars($_SESSION['last_name']) ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   value="<?= htmlspecialchars($_SESSION['email']) ?>" required>

            <label for="role">Role</label>
            <input type="text" id="role" value="<?= htmlspecialchars($_SESSION['role']) ?>" readonly>

            <label for="joined">Joined</label>
            <input type="text" id="joined" value="<?= htmlspecialchars($_SESSION['created_at']) ?>" readonly>

            <label for="updated">Last Updated</label>
            <input type="text" id="updated" value="<?= htmlspecialchars($_SESSION['updated_at']) ?>" readonly>

            <button type="submit">Update Profile</button>
        </form>
        <?php
            if (isset($_SESSION["profile-successful"])){
                echo '<span class="success">'.$_SESSION["profile-successful"].'</span>';
                unset($_SESSION["profile-successful"]);
            }
            if (isset($_SESSION["profile-error"])) {
                echo '<span class="error">'.$_SESSION["profile-error"].'</span>';
                unset($_SESSION["profile-error"]);
            }
        ?>
    </div>
</div>