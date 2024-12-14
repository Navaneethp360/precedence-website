<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection details (Replace these with your actual details)
$host = 'localhost';     // Your database host (e.g., 'localhost' or an IP address)
$username = 'ashtiric_precedence'; // Your database username
$password = 'Precedence@2024'; // Your database password
$dbname = 'ashtiric_precedence_test';   // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px 40px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        .status {
            font-size: 18px;
            font-weight: bold;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        .status.success {
            color: #fff;
            background-color: #4CAF50; /* Green for success */
        }
        .status.error {
            color: #fff;
            background-color: #f44336; /* Red for error */
        }
        footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Database Connection Test</h1>

        <div class="status">
            <?php
            // Check the database connection and display the result
            if ($conn->connect_error) {
                echo "<p class='error'>Connection failed: " . $conn->connect_error . "</p>";
            } else {
                echo "<p class='success'>Connected successfully to the database!</p>";
            }

            // Close the connection
            $conn->close();
            ?>
        </div>

        <footer>
            <p>&copy; 2024 Database Connection Test</p>
        </footer>
    </div>

</body>
</html>
