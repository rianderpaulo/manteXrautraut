
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
</head>
<body>

<h2>Laboratory System Login</h2>

<p><?php echo htmlspecialchars($message); ?></p>

<form method="POST">

    <label>Username:</label><br>
    <input type="text" name="username" required>
    <br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required>
    <br><br>

    <button type="submit">Login</button>

</form>

<p>Don't have an account?
<a href="register.php">Register here</a></p>

</body>
</html>
