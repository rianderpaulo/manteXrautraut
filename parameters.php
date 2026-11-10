
<?php

session_start();
require_once "config/db.php";

if (!isset($_SESSION["user_id"]) ||
    $_SESSION["role"] !== "reviewer") {

    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["parameter_name"]);
    $unit = trim($_POST["standard_unit"]);
    $description = trim($_POST["description"]);

    if ($name === "" || $unit === "") {

        $message = "Parameter name and unit are required.";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO test_parameters
            (parameter_name, standard_unit, description)
            VALUES (?, ?, ?)"
        );

        $stmt->bind_param("sss", $name, $unit, $description);

        if ($stmt->execute()) {
            $message = "Test parameter added successfully!";
        } else {
            $message = "Failed to add parameter. It may already exist.";
        }

        $stmt->close();
    }
}

$result = $conn->query(
    "SELECT * FROM test_parameters ORDER BY parameter_id DESC"
);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Parameters</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <h2>Laboratory Test Parameters</h2>

    <p><?php echo htmlspecialchars($message); ?></p>

    <h3>Add Test Parameter</h3>

    <form method="POST">

        <label>Parameter Name</label>
        <input type="text" name="parameter_name" required>

        <label>Standard Unit</label>
        <input type="text" name="standard_unit" required>

        <label>Description</label>
        <textarea name="description"></textarea>

        <button type="submit">Add Parameter</button>

    </form>

    <h3>Available Parameters</h3>

    <table>

        <tr>
            <th>ID</th>
            <th>Parameter</th>
            <th>Unit</th>
            <th>Description</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>

        <tr>
            <td><?php echo $row["parameter_id"]; ?></td>
            <td><?php echo htmlspecialchars($row["parameter_name"]); ?></td>
            <td><?php echo htmlspecialchars($row["standard_unit"]); ?></td>
            <td><?php echo htmlspecialchars($row["description"] ?? ""); ?></td>
        </tr>

        <?php endwhile; ?>

    </table>

    <p><a href="index.php">Back to Dashboard</a></p>

</div>

</body>
</html>
