
<?php
require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST["fullname"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if ($fullname == "" || $username == "" || $password == "") {
        $message = "Please complete all fields.";
    } else {

        $check = $conn->prepare(
            "SELECT user_id FROM users WHERE username = ?"
        );
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Username already exists.";
        } else {

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Public registration creates analysts only.
            $role = "analyst";

            $stmt = $conn->prepare(
                "INSERT INTO users
                (full_name, username, password, role)
                VALUES (?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssss",
                $fullname,
                $username,
                $hashedPassword,
                $role
            );

            if ($stmt->execute()) {
                $message = "Registration successful! You can now log in.";
            } else {
                $message = "Registration failed.";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Laboratory System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="auth-card">
    <div class="auth-dashboard__icon" aria-hidden="true">L</div>
    <p class="auth-dashboard__eyebrow">LAB TRACKING SYSTEM</p>
    <h1>Create an account</h1>
    <p class="auth-card__description">Register to get started with the laboratory system.</p>

    <?php if ($message !== ""): ?>
        <p class="auth-message <?php echo strpos($message, "successful") !== false ? "auth-message--success" : ""; ?>"
           role="status"><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="fullname">Full name</label>
        <input id="fullname" type="text" name="fullname" autocomplete="name" required>

        <label for="username">Username</label>
        <input id="username" type="text" name="username" autocomplete="username" required>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" autocomplete="new-password" required>

        <button class="auth-card__submit" type="submit">Create account</button>
    </form>

    <p class="auth-card__footer">Already have an account? <a href="login.php">Log in</a></p>
</main>

</body>
</html>
