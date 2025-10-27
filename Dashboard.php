<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit;
}

$employee_id = $_SESSION['employee_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
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

        /* Responsive */
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
    </style>
</head>
<body>

    <!-- Top Navigation -->
    <div class="top-nav">
        <span class="menu-toggle" onclick="toggleMenu()">☰</span>
        <h1>Dashboard</h1>
        <a href="Login.php">Logout</a>
    </div>

    <!-- Side Navigation -->
    <div class="side-nav" id="sideNav">
        <a href="Dashboard.php">Home</a>
        <a href="Report_Login.php">Reports</a>
        <a href="Form_Login.php">Form</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Welcome, <?= htmlspecialchars($employee_id) ?>!</h2>
        <p>You are now logged in. Use the side navigation to view reports and forms.</p>
    </div>

    <script>
        function toggleMenu() {
            document.getElementById('sideNav').classList.toggle('active');
        }
    </script>

</body>
</html>
