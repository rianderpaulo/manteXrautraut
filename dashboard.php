<?php
session_start();

$isLoggedIn = isset($_SESSION["user_id"], $_SESSION["full_name"], $_SESSION["role"])
    && in_array($_SESSION["role"], ["analyst", "reviewer"], true);

$role = $isLoggedIn ? $_SESSION["role"] : "";
$dashboardUrl = $role === "analyst"
    ? "analyst/dashboard.php"
    : "reviewer/dashboard.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratory System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="auth-dashboard">
        <div class="auth-dashboard__icon" aria-hidden="true">L</div>
        <p class="auth-dashboard__eyebrow">LAB TRACKING SYSTEM</p>

        <?php if ($isLoggedIn): ?>
            <h1>Welcome, <?= htmlspecialchars($_SESSION["full_name"], ENT_QUOTES, "UTF-8") ?></h1>
            <p class="auth-dashboard__description">
                You are signed in as a <?= htmlspecialchars(ucfirst($role), ENT_QUOTES, "UTF-8") ?>.
                Continue to your workspace or sign out.
            </p>
            <div class="auth-dashboard__actions">
                <a class="auth-dashboard__button" href="<?= htmlspecialchars($dashboardUrl, ENT_QUOTES, "UTF-8") ?>">
                    Open my dashboard
                </a>
                <a class="auth-dashboard__button auth-dashboard__button--secondary" href="logout.php">
                    Log out
                </a>
            </div>
        <?php else: ?>
            <h1>Welcome to Lab Tracking</h1>
            <p class="auth-dashboard__description">
                Sign in to continue to your laboratory workspace, or create an account to get started.
            </p>
            <div class="auth-dashboard__actions">
                <a class="auth-dashboard__button" href="login.php">Log in</a>
                <a class="auth-dashboard__button auth-dashboard__button--secondary" href="register.php">
                    Create an account
                </a>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
