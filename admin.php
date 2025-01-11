<?php
session_start();

// Admin credentials (ensure security in production)
$adminUsername = 'admin';
$adminPassword = 'adminpassword';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Database connection
$host = 'localhost';
$dbname = 'ashtiric_precedence_test';
$username = 'ashtiric_precedence';
$password = 'Precedence@2024';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch meeting data
$stmt = $pdo->query("SELECT * FROM meetings");
$meetings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Send email after status update
function sendStatusUpdateEmail($meetingDetails, $newStatus) {
    $to = 'u.godharwala@precedencekw.com, umargodharwala1996@gmail.com';
    $subject = 'Meeting Status Update';
    $message = "
    <html>
    <head>
        <title>Meeting Status Update</title>
    </head>
    <body>
        <p>The status of a meeting has been updated:</p>
        <table>
            <tr><th>First Name</th><td>{$meetingDetails['first_name']}</td></tr>
            <tr><th>Last Name</th><td>{$meetingDetails['last_name']}</td></tr>
            <tr><th>Email</th><td>{$meetingDetails['email']}</td></tr>
            <tr><th>Company</th><td>{$meetingDetails['company']}</td></tr>
            <tr><th>Topic</th><td>{$meetingDetails['topic']}</td></tr>
            <tr><th>Date</th><td>{$meetingDetails['date']}</td></tr>
            <tr><th>Time Slot</th><td>{$meetingDetails['time_slot']}</td></tr>
            <tr><th>Status</th><td>{$newStatus}</td></tr>
        </table>
    </body>
    </html>
    ";

    // Headers for email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: admin@precedencekw.com" . "\r\n"; // Use a valid from email address

    mail($to, $subject, $message, $headers);
}

// Update meeting status
if (isset($_POST['update_status'])) {
    $meetingId = $_POST['meeting_id'];
    $status = $_POST['status'];

    if (!empty($status)) {
        $updateStmt = $pdo->prepare("UPDATE meetings SET status = ? WHERE id = ?");
        $updateStmt->execute([$status, $meetingId]);

        // Fetch updated meeting details
        $stmt = $pdo->prepare("SELECT * FROM meetings WHERE id = ?");
        $stmt->execute([$meetingId]);
        $updatedMeeting = $stmt->fetch(PDO::FETCH_ASSOC);

        // Send email after status update
        sendStatusUpdateEmail($updatedMeeting, $status);

        header('Location: admin.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Meeting Management</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #4CAF50; color: white; }
        .btn { padding: 5px 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        form { display: inline; }
    </style>
</head>
<body>
    <h1>Admin Dashboard - Meeting Management</h1>
    <table>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Company</th>
            <th>Topic</th>
            <th>Date</th>
            <th>Time Slot</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($meetings as $meeting): ?>
            <tr>
                <td><?= htmlspecialchars($meeting['first_name']) ?></td>
                <td><?= htmlspecialchars($meeting['last_name']) ?></td>
                <td><?= htmlspecialchars($meeting['email']) ?></td>
                <td><?= htmlspecialchars($meeting['company']) ?></td>
                <td><?= htmlspecialchars($meeting['topic']) ?></td>
                <td><?= htmlspecialchars($meeting['date']) ?></td>
                <td><?= htmlspecialchars($meeting['time_slot']) ?></td>
                <td><?= htmlspecialchars($meeting['status']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="meeting_id" value="<?= $meeting['id'] ?>">
                        
                        <select name="status">
                            <option value="Pending" <?= $meeting['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Approved" <?= $meeting['status'] === 'Approved' ? 'selected' : '' ?>>Approved</option>
                        
                            <option value="Rejected" <?= $meeting['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                        <button type="submit" name="update_status" class="btn">Update Status</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
