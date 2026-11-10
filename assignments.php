
<?php

session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"]) ||
    $_SESSION["role"] !== "reviewer") {

    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $sample_id = (int) $_POST["sample_id"];
    $parameter_id = (int) $_POST["parameter_id"];
    $analyst_id = (int) $_POST["analyst_id"];

    $check = $conn->prepare(
        "SELECT user_id FROM users
         WHERE user_id = ? AND role = 'analyst'"
    );

    $check->bind_param("i", $analyst_id);
    $check->execute();

    $analystResult = $check->get_result();

    if ($analystResult->num_rows !== 1) {

        $message = "Please select a valid analyst.";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO sample_tests
            (sample_id, parameter_id, analyst_id)
            VALUES (?, ?, ?)"
        );

        $stmt->bind_param(
            "iii",
            $sample_id,
            $parameter_id,
            $analyst_id
        );

        if ($stmt->execute()) {
            $message = "Test assigned successfully!";
        } else {
            $message = "Assignment failed. Check if this test was already assigned.";
        }

        $stmt->close();
    }

    $check->close();
}

$samples = $conn->query(
    "SELECT sample_id, sample_code FROM samples"
);

$parameters = $conn->query(
    "SELECT parameter_id, parameter_name FROM test_parameters"
);

$analysts = $conn->query(
    "SELECT user_id, full_name FROM users
     WHERE role = 'analyst'"
);

$assignments = $conn->query(
    "SELECT st.sample_test_id,
            s.sample_code,
            c.client_name,
            tp.parameter_name,
            u.full_name,
            st.verification_status
     FROM sample_tests st
     JOIN samples s ON st.sample_id = s.sample_id
     JOIN clients c ON s.client_id = c.client_id
     JOIN test_parameters tp
        ON st.parameter_id = tp.parameter_id
     JOIN users u ON st.analyst_id = u.user_id
     ORDER BY st.sample_test_id DESC"
);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Assignments</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <h2>Assign Laboratory Tests</h2>

    <p><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">

        <label>Select Sample</label>

        <select name="sample_id" required>
            <option value="">Choose Sample</option>

            <?php while ($row = $samples->fetch_assoc()): ?>

                <option value="<?php echo $row["sample_id"]; ?>">
                    <?php echo htmlspecialchars($row["sample_code"]); ?>
                </option>

            <?php endwhile; ?>

        </select>

        <label>Select Test Parameter</label>

        <select name="parameter_id" required>
            <option value="">Choose Parameter</option>

            <?php while ($row = $parameters->fetch_assoc()): ?>

                <option value="<?php echo $row["parameter_id"]; ?>">
                    <?php echo htmlspecialchars($row["parameter_name"]); ?>
                </option>

            <?php endwhile; ?>

        </select>

        <label>Assign Analyst</label>

        <select name="analyst_id" required>
            <option value="">Choose Analyst</option>

            <?php while ($row = $analysts->fetch_assoc()): ?>

                <option value="<?php echo $row["user_id"]; ?>">
                    <?php echo htmlspecialchars($row["full_name"]); ?>
                </option>

            <?php endwhile; ?>

        </select>

        <button type="submit">Assign Test</button>

    </form>

    <h3>Assigned Tests</h3>

    <table>

        <tr>
            <th>ID</th>
            <th>Sample</th>
            <th>Client</th>
            <th>Parameter</th>
            <th>Analyst</th>
            <th>Status</th>
        </tr>

        <?php while ($row = $assignments->fetch_assoc()): ?>

        <tr>
            <td><?php echo $row["sample_test_id"]; ?></td>
            <td><?php echo htmlspecialchars($row["sample_code"]); ?></td>
            <td><?php echo htmlspecialchars($row["client_name"]); ?></td>
            <td><?php echo htmlspecialchars($row["parameter_name"]); ?></td>
            <td><?php echo htmlspecialchars($row["full_name"]); ?></td>
            <td><?php echo htmlspecialchars($row["verification_status"]); ?></td>
        </tr>

        <?php endwhile; ?>

    </table>

    <p><a href="index.php">Back to Dashboard</a></p>

</div>

</body>
</html>
