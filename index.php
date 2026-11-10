
<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] === "analyst") {
    header("Location: analyst/dashboard.php");
    exit;
}

if ($_SESSION["role"] === "reviewer") {
    header("Location: reviewer/dashboard.php");
    exit;
}

session_destroy();
header("Location: login.php");
exit;

?>
