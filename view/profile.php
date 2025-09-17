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

        <div id="result"></div>

        <form id="password-form" action="../update-password.php" method="post">
            <label for="currentPassword">Current Password</label>
            <span class="password-field">
                <input
                        type="password"
                        id="currentPassword"
                        name="currentPassword"
                        required
                        autocomplete="current-password"
                >
                <button
                        type="button"
                        class="toggle-password"
                        id="toggleCurrent"
                        aria-label="Pokaż hasło"
                        aria-pressed="false"
                        hidden
                        title="Show/hide password"
                >
                    <i class="icon-eye" aria-hidden="true"></i>
                </button>
            </span>

                <label for="newPassword">New Password</label>
                <span class="password-field">
            <input
                    type="password"
                    id="newPassword"
                    name="newPassword"
                    required
                    autocomplete="new-password"
            >
            <button
                    type="button"
                    class="toggle-password"
                    id="toggleNew"
                    aria-label="Pokaż hasło"
                    aria-pressed="false"
                    hidden
                    title="Show/hide password"
            >
                <i class="icon-eye" aria-hidden="true"></i>
            </button>
        </span>

                <label for="confirmPassword">Confirm New Password</label>
                <span class="password-field">
            <input
                    type="password"
                    id="confirmPassword"
                    name="confirmPassword"
                    required
                    autocomplete="new-password"
            >
            <button
                    type="button"
                    class="toggle-password"
                    id="toggleConfirm"
                    aria-label="Pokaż hasło"
                    aria-pressed="false"
                    hidden
                    title="Show/hide password"
            >
                <i class="icon-eye" aria-hidden="true"></i>
            </button>
        </span>

            <button type="submit">Set Password</button>
        </form>
        <div id="password-result"></div>
    </div>
</div>