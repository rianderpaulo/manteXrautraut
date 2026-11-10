
<?php
session_start();
require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT user_id, full_name, username,
        password, role
        FROM users
        WHERE username = ?"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["full_name"] = $user["full_name"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] == "analyst") {
                header("Location: analyst/dashboard.php");
            } else {
                header("Location: reviewer/dashboard.php");
            }

            exit;

        } else {
            $message = "Incorrect password.";
        }

    } else {
        $message = "Account not found.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Laboratory System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="auth-card">
    <div class="auth-dashboard__icon" aria-hidden="true">LTS</div>
    <p class="auth-dashboard__eyebrow">LAB TRACKING SYSTEM</p>
    <h1>Welcome back</h1>
    <p class="auth-card__description">Log in to continue to your laboratory workspace.</p>

    <?php if (isset($_GET["logged_out"])): ?>
        <p class="auth-message auth-message--success" role="status">You have been logged out.</p>
    <?php endif; ?>
    <?php if ($message !== ""): ?>
        <p class="auth-message" role="alert"><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="username">Username</label>
        <input id="username" type="text" name="username" autocomplete="username" required>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" autocomplete="current-password" required>

        <button class="auth-card__submit" type="submit">Log in</button>
    </form>

    <p class="auth-card__footer">Don't have an account? <a href="register.php">Create one</a></p>
</main>

</body>
</html>
