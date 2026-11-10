
<?php
session_start();

if (!isset($_SESSION["user_id"]) ||
    $_SESSION["role"] !== "reviewer") {

    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reviewer Dashboard</title>
</head>
<body>

<h1>Reviewer Dashboard</h1>

<h3>Welcome, <?php
echo htmlspecialchars($_SESSION["full_name"]);
?>!</h3>

<p>You are logged in as a Reviewer.</p>

<ul>
    <li><a href="review.php">Review Test Results</a></li>
</ul>

<a href="../logout.php">Logout</a>

</body>
</html>
