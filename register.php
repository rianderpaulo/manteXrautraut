
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
</head>
<body>

<h2>Create Analyst Account</h2>

<p><?php echo htmlspecialchars($message); ?></p>

<form method="POST">

    <label>Full Name:</label><br>
    <input type="text" name="fullname" required>
    <br><br>

    <label>Username:</label><br>
    <input type="text" name="username" required>
    <br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required>
    <br><br>

    <button type="submit">Register</button>

</form>

<p>Already have an account?
<a href="login.php">Login here</a></p>

</body>
</html>
