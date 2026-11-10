
<?php
session_start();

if (!isset($_SESSION["user_id"]) ||
    $_SESSION["role"] !== "analyst") {

    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analyst Dashboard - Lab Tracking</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<main class="dashboard role-dashboard">
    <header class="role-dashboard__header">
        <div>
            <p class="auth-dashboard__eyebrow">LAB TRACKING SYSTEM</p>
            <h1>Analyst Dashboard</h1>
        </div>
        <span class="role-dashboard__role">Analyst</span>
    </header>

    <section class="role-dashboard__welcome">
        <h2>Welcome, <?= htmlspecialchars($_SESSION["full_name"], ENT_QUOTES, "UTF-8") ?></h2>
        <p>Manage your assigned laboratory work from here.</p>
    </section>

    <section class="role-dashboard__section" aria-labelledby="work-heading">
        <h2 id="work-heading">Your workspace</h2>
        <a class="role-dashboard__link" href="assigned_tests.php">
            <span class="role-dashboard__link-icon" aria-hidden="true">✓</span>
            <span>
                <strong>My Assigned Tests</strong>
                <small>View and update your assigned tests</small>
            </span>
            <span class="role-dashboard__arrow" aria-hidden="true">&rarr;</span>
        </a>
    </section>

    <footer class="role-dashboard__footer">
        <a class="auth-dashboard__button auth-dashboard__button--secondary" href="../logout.php">Log out</a>
    </footer>
</main>

</body>
</html>
