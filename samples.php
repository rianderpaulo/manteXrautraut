
<?php

session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $client_id = (int) $_POST["client_id"];
    $sample_code = trim($_POST["sample_code"]);
    $collection_date = $_POST["collection_date"];
    $source_location = trim($_POST["source_location"]);
    $receipt_date = $_POST["receipt_date"];

    if ($client_id <= 0 || $sample_code === "" ||
        $source_location === "") {

        $message = "Please complete the required fields.";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO samples
            (client_id, sample_code, collection_date,
             source_location, receipt_date)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "issss",
            $client_id,
            $sample_code,
            $collection_date,
            $source_location,
            $receipt_date
        );

        if ($stmt->execute()) {
            $message = "Sample registered successfully!";
        } else {
            $message = "Failed to register sample. Check if sample code already exists.";
        }

        $stmt->close();
    }
}

$clients = $conn->query(
    "SELECT client_id, client_name FROM clients ORDER BY client_name"
);

$samples = $conn->query(
    "SELECT samples.*, clients.client_name
     FROM samples
     JOIN clients ON samples.client_id = clients.client_id
     ORDER BY samples.sample_id DESC"
);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Sample Management</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <h2>Water Sample Management</h2>

    <p><?php echo htmlspecialchars($message); ?></p>

    <h3>Register New Sample</h3>

    <form method="POST">

        <label>Client</label>

        <select name="client_id" required>
            <option value="">Select Client</option>

            <?php while ($client = $clients->fetch_assoc()): ?>

                <option value="<?php echo $client["client_id"]; ?>">
                    <?php echo htmlspecialchars($client["client_name"]); ?>
                </option>

            <?php endwhile; ?>

        </select>

        <label>Sample Code</label>
        <input type="text" name="sample_code" required>

        <label>Collection Date</label>
        <input type="date" name="collection_date" required>

        <label>Source Location</label>
        <input type="text" name="source_location" required>

        <label>Receipt Date</label>
        <input type="date" name="receipt_date" required>

        <button type="submit">Register Sample</button>

    </form>

    <h3>Registered Samples</h3>

    <table>
        <tr>
            <th>Sample Code</th>
            <th>Client</th>
            <th>Collection Date</th>
            <th>Source</th>
            <th>Receipt Date</th>
        </tr>

        <?php while ($row = $samples->fetch_assoc()): ?>

        <tr>
            <td><?php echo htmlspecialchars($row["sample_code"]); ?></td>
            <td><?php echo htmlspecialchars($row["client_name"]); ?></td>
            <td><?php echo htmlspecialchars($row["collection_date"]); ?></td>
            <td><?php echo htmlspecialchars($row["source_location"]); ?></td>
            <td><?php echo htmlspecialchars($row["receipt_date"]); ?></td>
        </tr>

        <?php endwhile; ?>

    </table>

    <p><a href="index.php">Back to Dashboard</a></p>

</div>

</body>
</html>
