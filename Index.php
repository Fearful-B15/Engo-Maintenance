<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = '';

// Database connection details
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "engo_main";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($id && $password) {
        $stmt = $conn->prepare("SELECT ID, Password FROM employees WHERE ID = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if ($password === $row['Password']) {
                $_SESSION['employee_id'] = $row['ID'];
                header('Location:Dashboard.php');
                exit;
            } else {
                $error = 'Invalid ID or password.';
            }
        } else {
            $error = 'Invalid ID or password.';
        }
        $stmt->close();
    } else {
        $error = 'Please enter both ID and password.';
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #d6d6d6;
            color: #000;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 30px 25px;
            background: #3264A1;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            box-sizing: border-box;
        }

        .login-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .login-header .logo {
            width: 70%;
            max-width: 280px;
            height: auto;
        }

        h2 {
            text-align: center;
            color: #fff;
            margin-bottom: 20px;
        }

        .error {
            color: #ffb3b3;
            background: rgba(0, 0, 0, 0.2);
            padding: 8px;
            border-radius: 5px;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #fff;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #fff;
            color: #3264A1;
            border: none;
            cursor: pointer;
            font-weight: bold;
            border-radius: 6px;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #264d7e;
            color: #fff;
        }

        /* ✅ Responsive Design */
        @media (max-width: 600px) {
            body {
                padding: 10px;
                background: #f2f2f2;
            }

            .login-container {
                width: 100%;
                padding: 20px;
                border-radius: 8px;
                box-shadow: none;
            }

            .login-header .logo {
                width: 80%;
                max-width: 250px;
            }

            h2 {
                font-size: 1.4rem;
            }

            input[type="text"],
            input[type="password"] {
                font-size: 15px;
            }

            .btn {
                font-size: 15px;
                padding: 10px;
            }
        }

        @media (max-width: 380px) {
            .login-container {
                padding: 15px;
            }

            .btn {
                font-size: 14px;
            }

            h2 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="Engo.png" alt="Logo" class="logo">
        </div>
        <h2>Employee Login</h2>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="id">Employee ID:</label>
            <input type="text" name="id" id="id" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>
