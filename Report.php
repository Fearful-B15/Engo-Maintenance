<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: Report_Login.php");
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

// Handle "Resolved" deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve_id'])) {
    $resolve_id = intval($_POST['resolve_id']);
    $stmt = $conn->prepare("DELETE FROM maintenance_reports WHERE id = ?");
    $stmt->bind_param("i", $resolve_id);
    $stmt->execute();
    $stmt->close();
    header("Location: Dashboard.php");
    exit;
}

// Fetch maintenance reports
$sql = "SELECT * FROM maintenance_reports ORDER BY date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Mobile responsive -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background: #d6d6d6;
            color: #000;
        }

        /* Top navigation */
        .top-nav {
            height: 60px;
            background: #3264A1;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .top-nav h1 {
            margin: 0;
            font-size: 24px;
        }
        .top-nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .menu-toggle {
            display: none;
            font-size: 24px;
            cursor: pointer;
        }

        /* Side navigation */
        .side-nav {
            width: 200px;
            background: #3264A1;
            position: fixed;
            top: 60px;
            bottom: 0;
            padding-top: 20px;
            transition: transform 0.3s ease;
        }
        .side-nav a {
            display: block;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            margin-bottom: 5px;
        }
        .side-nav a:hover {
            background: #ffffff;
            color: #000;
        }

        /* Main content */
        .main-content {
            margin-left: 200px;
            padding: 20px;
            margin-top: 60px;
        }

        h2 {
            color: #3264A1;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            overflow-x: auto;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        table th {
            background: #3264A1;
            color: white;
        }
        table tr:nth-child(even) {
            background: #f2f2f2;
        }

        /* Resolved button */
        .resolve-btn {
            padding: 5px 10px;
            background: green;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .side-nav {
                transform: translateX(-220px);
                z-index: 10;
            }
            .side-nav.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .menu-toggle {
                display: block;
                color: white;
            }
        }

        @media (max-width: 480px) {
            table th, table td {
                padding: 8px;
                font-size: 14px;
            }
            h2 {
                font-size: 1.3rem;
            }
        }

        /* Table container for horizontal scroll on small screens */
        .table-container {
            overflow-x: auto;
        }
    </style>
</head>
<body>

    <!-- Top Navigation -->
    <div class="top-nav">
        <span class="menu-toggle" onclick="toggleMenu()">☰</span>
        <h1>Dashboard</h1>
        <a href="Index.php">Logout</a>
    </div>

    <!-- Side Navigation -->
    <div class="side-nav" id="sideNav">
        <a href="Dashboard.php">Home</a>
        <a href="Report.php">Reports</a>
        <a href="Form_Login.php">Form</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Welcome, <?= htmlspecialchars($employee_id) ?>!</h2>
        <p>Below are the latest maintenance reports:</p>

        <?php if ($result && $result->num_rows > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>House</th>
                            <th>Room</th>
                            <th>Urgency</th>
                            <th>Problem</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['date']) ?></td>
                                <td><?= htmlspecialchars($row['house']) ?></td>
                                <td><?= htmlspecialchars($row['room']) ?></td>
                                <td><?= htmlspecialchars($row['urgency']) ?></td>
                                <td><?= htmlspecialchars($row['problem']) ?></td>
                                <td><?= htmlspecialchars($row['description']) ?></td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="resolve_id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="resolve-btn" onclick="return confirm('Mark this report as resolved?')">Resolved</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p style="color:#555; font-style:italic;">No maintenance reports found.</p>
        <?php endif; ?>
    </div>

    <script>
        function toggleMenu() {
            document.getElementById('sideNav').classList.toggle('active');
        }
    </script>

</body>
</html>
<?php $conn->close(); ?>
