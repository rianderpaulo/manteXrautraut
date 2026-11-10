
<?php

session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["client_name"]);
    $contact = trim($_POST["contact_number"]);
    $email = trim($_POST["email"]);
    $address = trim($_POST["address"]);

    if ($name === "") {

        $message = "Client name is required.";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO clients
            (client_name, contact_number, email, address)
            VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssss",
            $name,
            $contact,
            $email,
            $address
        );

        if ($stmt->execute()) {
            $message = "Client added successfully!";
        } else {
            $message = "Unable to add client.";
        }

        $stmt->close();
    }
}

$result = $conn->query(
    "SELECT * FROM clients ORDER BY client_id DESC"
);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Client Management</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <h2>Client Management</h2>

    <p><?php echo htmlspecialchars($message); ?></p>

    <h3>Add New Client</h3>

    <form method="POST">

        <label>Client Name</label>
        <input type="text" name="client_name" required>

        <label>Contact Number</label>
        <input type="text" name="contact_number">

        <label>Email</label>
        <input type="email" name="email">

        <label>Address</label>
        <textarea name="address"></textarea>

        <button type="submit">Save Client</button>

    </form>

    <h3>Registered Clients</h3>

    <table>
        <tr>
            <th>ID</th>
            <th>Client Name</th>
            <th>Contact</th>
            <th>Email</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>

        <tr>
            <td><?php echo $row["client_id"]; ?></td>
            <td><?php echo htmlspecialchars($row["client_name"]); ?></td>
            <td><?php echo htmlspecialchars($row["contact_number"] ?? ""); ?></td>
            <td><?php echo htmlspecialchars($row["email"] ?? ""); ?></td>
        </tr>

        <?php endwhile; ?>

    </table>

    <p><a href="index.php">Back to Dashboard</a></p>

</div>

</body>
</html>
