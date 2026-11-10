
<?php
session_start();

if (!isset($_SESSION["user_id"]) ||
    $_SESSION["role"] !== "reviewer") {

    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviewer Dashboard - Lab Tracking</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<main class="dashboard role-dashboard">
    <header class="role-dashboard__header">
        <div>
            <p class="auth-dashboard__eyebrow">LAB TRACKING SYSTEM</p>
            <h1>Reviewer Dashboard</h1>
        </div>
        <span class="role-dashboard__role">Reviewer</span>
    </header>

    <section class="role-dashboard__welcome">
        <h2>Welcome, <?= htmlspecialchars($_SESSION["full_name"], ENT_QUOTES, "UTF-8") ?></h2>
        <p>Review and manage laboratory test results from your workspace.</p>
    </section>

    <section class="role-dashboard__section" aria-labelledby="review-heading">
        <h2 id="review-heading">Your workspace</h2>
        <a class="role-dashboard__link" href="review.php">
            <span class="role-dashboard__link-icon" aria-hidden="true">✓</span>
            <span>
                <strong>Review Test Results</strong>
                <small>View and review submitted test results</small>
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
