<?php
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

session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Update slot status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $slotId = $_POST['slot_id'];
    $status = $_POST['status'];
    $csrfToken = $_POST['csrf_token'];

    if (!hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        die("CSRF token validation failed.");
    }

    if (in_array($status, ['Available', 'Booked']) && is_numeric($slotId)) {
        // Get slot time
        $slotStmt = $pdo->prepare("SELECT * FROM time_slots WHERE id = ?");
        $slotStmt->execute([$slotId]);
        $slot = $slotStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($slot) {
            $slotTime = date("g:i A", strtotime($slot['slot_time'])); // 12-hour format time
            $updateStmt = $pdo->prepare("UPDATE time_slots SET status = ? WHERE id = ?");
            $updateStmt->execute([$status, $slotId]);

            // Send email with meeting details
            // (Implement your email sending function here)

            echo "<script>alert('Slot status updated successfully!');</script>";
        }
    } else {
        echo "<script>alert('Invalid input.');</script>";
    }
}

// Fetch all time slots
$slotsStmt = $pdo->query("SELECT * FROM time_slots");
$timeSlots = $slotsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Time Slots</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #f4f4f4; }
        form { display: inline; }
        select { padding: 5px; }
        .btn { padding: 5px 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        .btn.update { background-color: #2196F3; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
    </style>
</head>
<body>
    <h1>Admin - Manage Time Slots</h1>

    <table>
        <thead>
            <tr>
                <th>Slot ID</th>
                <th>Time</th>
                <th>Status</th>
                <th>Update Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($timeSlots as $slot): ?>
                <?php $formattedTime = date("g:i A", strtotime($slot['slot_time'])); ?>
                <tr>
                    <td><?= $slot['id'] ?></td>
                    <td><?= $formattedTime ?></td>
                    <td><?= $slot['status'] ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="slot_id" value="<?= $slot['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <select name="status">
                                <option value="Available" <?= $slot['status'] === 'Available' ? 'selected' : '' ?>>Available</option>
                                <option value="Booked" <?= $slot['status'] === 'Booked' ? 'selected' : '' ?>>Booked</option>
                            </select>
                            <button type="submit" name="update_status" class="btn update">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
