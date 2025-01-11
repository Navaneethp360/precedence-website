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

// Create tables if not exist
try {
    // Create time_slots table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS time_slots (
            id INT AUTO_INCREMENT PRIMARY KEY,
            slot_time VARCHAR(50) NOT NULL,
            status ENUM('Available', 'Booked') DEFAULT 'Available',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Create meetings table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS meetings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL,
            company VARCHAR(100),
            topic VARCHAR(200),
            date DATE NOT NULL,
            slot_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (slot_id) REFERENCES time_slots(id) ON DELETE CASCADE
        )
    ");

    // Insert default time slots if table is empty
    $count = $pdo->query("SELECT COUNT(*) FROM time_slots")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("
            INSERT INTO time_slots (slot_time, status) VALUES
            ('09:00 AM - 10:00 AM', 'Available'),
            ('10:00 AM - 11:00 AM', 'Available'),
            ('11:00 AM - 12:00 PM', 'Available'),
            ('01:00 PM - 02:00 PM', 'Available'),
            ('02:00 PM - 03:00 PM', 'Available')
        ");
    }
} catch (PDOException $e) {
    die("Error creating tables: " . $e->getMessage());
}

// Fetch all time slots
$slotsStmt = $pdo->query("SELECT * FROM time_slots");
$timeSlots = $slotsStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle meeting booking
if (isset($_POST['book_meeting'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $company = $_POST['company'];
    $topic = $_POST['topic'];
    $date = $_POST['date'];
    $slotId = $_POST['slot_id'];

    // Check if slot is available
    $slotCheckStmt = $pdo->prepare("SELECT status FROM time_slots WHERE id = ?");
    $slotCheckStmt->execute([$slotId]);
    $slotStatus = $slotCheckStmt->fetch(PDO::FETCH_ASSOC)['status'];

    if ($slotStatus === 'Available') {
        // Book meeting
        $insertStmt = $pdo->prepare("
            INSERT INTO meetings (first_name, last_name, email, company, topic, date, slot_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $insertStmt->execute([$firstName, $lastName, $email, $company, $topic, $date, $slotId]);

        // Update slot status
        $updateSlotStmt = $pdo->prepare("UPDATE time_slots SET status = 'Booked' WHERE id = ?");
        $updateSlotStmt->execute([$slotId]);

        echo "<p style='color: green;'>Meeting booked successfully!</p>";
    } else {
        echo "<p style='color: red;'>Selected slot is no longer available.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Scheduler</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .slider-container { display: flex; overflow-x: scroll; padding: 10px; background-color: #f0f0f0; border-radius: 10px; }
        .time-slot { padding: 20px; margin-right: 10px; border: 2px solid #ddd; border-radius: 5px; text-align: center; cursor: pointer; }
        .time-slot.available { border-color: green; color: green; }
        .time-slot.booked { border-color: red; color: red; cursor: not-allowed; }
        form { margin-top: 20px; }
        input, select { padding: 8px; margin: 10px 0; width: 100%; }
        .btn { padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Book a Meeting</h1>

    <div class="slider-container">
        <?php foreach ($timeSlots as $slot): ?>
            <div class="time-slot <?= $slot['status'] === 'Available' ? 'available' : 'booked' ?>" 
                 data-id="<?= $slot['id'] ?>">
                <?= $slot['slot_time'] ?><br>
                <small><?= $slot['status'] ?></small>
            </div>
        <?php endforeach; ?>
    </div>

    <form method="POST">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="company">Company:</label>
        <input type="text" id="company" name="company">

        <label for="topic">Topic:</label>
        <input type="text" id="topic" name="topic">

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>

        <input type="hidden" id="slot_id" name="slot_id" required>

        <button type="submit" name="book_meeting" class="btn">Book Meeting</button>
    </form>

    <script>
        const slots = document.querySelectorAll('.time-slot');
        const slotInput = document.getElementById('slot_id');

        slots.forEach(slot => {
            slot.addEventListener('click', () => {
                if (slot.classList.contains('available')) {
                    slots.forEach(s => s.style.borderColor = '#ddd');
                    slot.style.borderColor = '#4CAF50';
                    slotInput.value = slot.getAttribute('data-id');
                }
            });
        });
    </script>
</body>
</html>
