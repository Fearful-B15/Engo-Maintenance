<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit;
}

$employee_id = $_SESSION['employee_id'];

// Database connection
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "engo_main";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $house = $_POST['house'] ?? '';
    $problem = $_POST['problem'] ?? '';
    $room = $_POST['room'] ?? '';
    $urgency = $_POST['urgency'] ?? '';
    $description = $_POST['description'] ?? '';

    if ($house && $problem && $room && $urgency) {
        $stmt = $conn->prepare("INSERT INTO maintenance_reports (house, problem, room, urgency, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $house, $problem, $room, $urgency, $description);

        if ($stmt->execute()) {
            $message = "✅ Report successfully submitted!";
        } else {
            $message = "❌ Error: " . $conn->error;
        }

        $stmt->close();
    } else {
        $message = "⚠️ Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Maintenance Report Form</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        background: #d6d6d6;
        color: #000;
    }

    /* Top navigation */
    .top-nav {
        height: 80px;
        background: #3264A1;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .top-nav h1 { margin:0; font-size: 24px; }
    .top-nav a { color:white; text-decoration:none; font-weight:bold; }

    /* Main content */
    .main-content {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 30px 15px;
    }

    h2 {
        color: #3264A1;
        text-align: center;
        margin-bottom: 20px;
    }

    form {
        background: #ffffff;
        padding: 30px;
        border-radius: 10px;
        width: 100%;
        max-width: 600px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        box-sizing: border-box;
    }

    label {
        font-weight: bold;
        display: block;
        margin-top: 15px;
        font-size: 16px;
    }

    select, input[type="text"], textarea {
        width: 100%;
        padding: 12px;
        margin-top: 8px;
        font-size: 15px;
        border-radius: 6px;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }

    textarea { resize: vertical; min-height: 80px; }

    button {
        margin-top: 25px;
        padding: 14px;
        background: #3264A1;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
        transition: all 0.2s ease-in-out;
    }

    button:hover {
        background: #ffffff;
        color: #000;
        border: 1px solid #3264A1;
    }

    .message {
        margin-bottom: 20px;
        font-weight: bold;
        text-align: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .main-content { padding: 20px; }
        form { padding: 25px; }
    }

    @media (max-width: 480px) {
        h2 { font-size: 1.3rem; }
        label { font-size: 14px; }
        select, input[type="text"], textarea { font-size: 14px; padding: 10px; }
        button { font-size: 15px; padding: 12px; }
    }
</style>
</head>
<body>

<div class="top-nav">
    <h1>Maintenance Form</h1>
    <a href="Index.php">Logout</a>
</div>

<div class="main-content">
    <form method="POST" action="">
        <h2>Submit Maintenance Report</h2>

        <?php if (!empty($message)): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <label for="house">House:</label>
        <select name="house" id="house" required>
            <option value="">-- Select House --</option>
            <option>Blydskap</option>
            <option>Dagbreek</option>
            <option>Doephuis</option>
            <option>Lentedou</option>
            <option>Môredou</option>
            <option>Vastrap</option>
            <option>Vredehoek</option>
            <option>Welverdiend</option>
            <option>Herberg</option>
            <option>Bolokanang</option>
            <option>Store Room</option>
            <option>Office</option>
            <option>Swimming Pool</option>
            <option>Hall</option>
            <option>Vehicle</option>
        </select>

        <label for="room">Room:</label>
        <select name="room" id="room" required>
            <option value="">-- Select Room --</option>
            <option>Room 1</option>
            <option>Room 2</option>
            <option>Room 3</option>
            <option>Room 4</option>
            <option>Room 5</option>
            <option>Room 6</option>
            <option>Room 7</option>
            <option>Room 8</option>
            <option>Bathroom</option>
            <option>NA</option>
        </select>

        <label for="urgency">Urgency:</label>
        <select name="urgency" id="urgency" required>
            <option value="">-- Select Urgency --</option>
            <option value="Red-Critical">Red - Critical</option>
            <option value="Orange-High">Orange - High</option>
            <option value="Yellow-Medium">Yellow - Medium</option>
            <option value="Green-Low">Green - Low</option>
        </select>

        <label for="problem">Problem:</label>
        <input type="text" id="problem" name="problem" placeholder="Describe the problem..." required>

        <label for="description">Additional Description (optional):</label>
        <textarea id="description" name="description" placeholder="Provide more details..."></textarea>

        <button type="submit">Submit Report</button>
    </form>
</div>

</body>
</html>

<?php $conn->close(); ?>
