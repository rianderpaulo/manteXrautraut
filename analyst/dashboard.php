
<?php
session_start();

if (!isset($_SESSION["user_id"]) ||
    $_SESSION["role"] !== "analyst") {

    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Analyst Dashboard</title>
</head>
<body>

<h1>Analyst Dashboard</h1>

<h3>Welcome, <?php
echo htmlspecialchars($_SESSION["full_name"]);
?>!</h3>

<p>You are logged in as an Analyst.</p>

<ul>
    <li><a href="assigned_tests.php">My Assigned Tests</a></li>
</ul>

<a href="../logout.php">Logout</a>

</body>
</html>
