<?php
// Enable error reporting for debugging (disable this in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details (Replace these with your actual details)
$host = 'localhost';
$username = 'ashtiric_precedence'; // Your database username
$password = 'Precedence@2024'; // Your database password
$dbname = 'ashtiric_precedence_test'; // Your database name

// Establish database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle meeting status update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_status'])) {
        $meeting_id = $_POST['meeting_id'];
        $new_status = $_POST['status'];

        // Sanitize the status input (check if it's a valid status)
        $valid_statuses = ['Pending', 'Approved', 'Rejected'];
        if (in_array($new_status, $valid_statuses)) {
            // Update the status of the meeting
            $update_sql = "UPDATE meetings SET status = ? WHERE id = ?";
            if ($stmt = $conn->prepare($update_sql)) {
                $stmt->bind_param("si", $new_status, $meeting_id);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "Error updating status: " . $conn->error;
            }
        } else {
            echo "Invalid status value.";
        }
        header("Location: admin_panel.php"); // Redirect to refresh the page after updating status
        exit;
    } elseif (isset($_POST['delete_meeting'])) {
        $meeting_id = $_POST['meeting_id'];

        // Delete the meeting from the database
        $delete_sql = "DELETE FROM meetings WHERE id = ?";
        if ($stmt = $conn->prepare($delete_sql)) {
            $stmt->bind_param("i", $meeting_id);
            $stmt->execute();
            $stmt->close();
        }
        header("Location: admin_panel.php"); // Redirect to refresh the page after deletion
        exit;
    }
}

// Fetch meetings data from the database
$sql = "SELECT * FROM meetings";
$result = $conn->query($sql);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Meeting Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .status-select {
            width: 150px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            font-size: 14px;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #45a049;
        }
        .delete-button {
            background-color: #f44336;
            color: white;
            font-size: 14px;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-button:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin Panel - Meeting Management</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Time Slot</th>
                    <th>Topic</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['contact_number']; ?></td>
                        <td><?php echo $row['time_slot']; ?></td>
                        <td><?php echo $row['topic']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="meeting_id" value="<?php echo $row['id']; ?>">
                                <select name="status" class="status-select">
                                    <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Approved" <?php echo $row['status'] == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="Rejected" <?php echo $row['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                                <button type="submit" name="update_status" class="button">Update Status</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this meeting?');">
                                <input type="hidden" name="meeting_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_meeting" class="delete-button">Delete</button>
                                
                                <!--sma code here-->
                                
                                for(i=0;i>5;i)=:0
                                catch
                                {
                                break:0;
                                break:1;
                                break:2;to Be Contuned there code
                                
                                brek:n,
                                
                                break: date
                                
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No meetings scheduled yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
