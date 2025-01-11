<?php
// Database connection
$host = 'localhost';
$dbname = 'ashtiric_precedence';
$username = 'ashtiric_pre_user';
$password = 'Precedence@2025';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch all time slots
$slotsStmt = $pdo->query("SELECT * FROM time_slots");
$timeSlots = $slotsStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle meeting booking
if (isset($_POST['book_meeting'])) {
    $firstName = htmlspecialchars($_POST['first_name']);
    $lastName = htmlspecialchars($_POST['last_name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $company = htmlspecialchars($_POST['company']);
    $topic = htmlspecialchars($_POST['topic']);
    $date = $_POST['date'];
    $slotId = intval($_POST['slot_id']);

    // Validate date (ensure it is in the future)
    $currentDate = date('Y-m-d');
    if ($date < $currentDate) {
        die("<p class='error-message'>Please select a future date.</p>");
    }

    // Check if slot is available
    $slotCheckStmt = $pdo->prepare("SELECT status FROM time_slots WHERE id = ?");
    $slotCheckStmt->execute([$slotId]);
    $slotStatus = $slotCheckStmt->fetch(PDO::FETCH_ASSOC)['status'];

    if ($slotStatus === 'Available') {
        try {
            // Start a transaction
            $pdo->beginTransaction();

            // Book meeting
            $insertStmt = $pdo->prepare("
                INSERT INTO meetings (first_name, last_name, email, company, topic, date, slot_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $insertStmt->execute([$firstName, $lastName, $email, $company, $topic, $date, $slotId]);

            // Update slot status
            $updateSlotStmt = $pdo->prepare("UPDATE time_slots SET status = 'Booked' WHERE id = ?");
            $updateSlotStmt->execute([$slotId]);

            // Commit the transaction
            $pdo->commit();

            echo "<p class='success-message'>Meeting booked successfully!</p>";
        } catch (Exception $e) {
            // Rollback the transaction in case of error
            $pdo->rollBack();
            echo "<p class='error-message'>Error booking meeting: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='error-message'>Selected slot is no longer available.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Scheduler</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        /* Time Slot Slider */
        .slider-container {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding: 20px 0;
            justify-content: center;
        }

        .time-slot {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 12px;
            border: 2px solid #ddd;
            width: 200px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .time-slot.available {
            border-color: #27ae60;
            color: #27ae60;
        }

        .time-slot.booked {
            border-color: #e74c3c;
            color: #e74c3c;
            cursor: not-allowed;
        }

        .time-slot:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .time-slot.booked:hover {
            transform: none;
            box-shadow: none;
        }

        .time-slot strong {
            font-size: 1.3rem;
            font-weight: 500;
        }

        .time-slot small {
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        /* Popup Form */
        .form-popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
            z-index: 1000;
            transition: opacity 0.3s ease;
        }

        .form-popup.show {
            display: flex;
            opacity: 1;
        }

        .form-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            position: relative;
        }

        .form-container h2 {
            color: #2c3e50;
            font-size: 2rem;
            font-weight: 500;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-container label {
            font-weight: 500;
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            color: #2c3e50;
        }

        .form-container input {
            width: 100%;
            padding: 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 2px solid #ddd;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .form-container input:focus {
            border-color: #27ae60;
            outline: none;
        }

        .form-container button {
            width: 100%;
            padding: 16px;
            background-color: #27ae60;
            color: white;
            font-size: 16px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #2ecc71;
        }

        .close-btn {
    background-color: transparent;
    border: none;
    color: #e74c3c;
    font-size: 20px; /* Reduced size */
    font-weight: bold;
    cursor: pointer;
    position: absolute;
    top: 15px; /* Positioned slightly down from the top */
    right: 15px; /* Positioned slightly in from the right edge */
    padding: 5px 10px;
    border-radius: 50%;
    transition: background-color 0.3s ease;
}

.close-btn:hover {
    background-color: rgba(231, 76, 60, 0.1);
}


        /* Success & Error Messages */
        .error-message, .success-message {
            text-align: center;
            padding: 15px;
            font-size: 16px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error-message {
            color: #e74c3c;
            background-color: #f8d7da;
        }

        .success-message {
            color: #27ae60;
            background-color: #d4edda;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .time-slot {
                min-width: 180px;
            }

            .form-container {
                padding: 25px;
                max-width: 90%;
            }

            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Book a Meeting</h1>

        <!-- Time Slot Slider -->
        <div class="slider-container">
            <?php foreach ($timeSlots as $slot): ?>
                <div class="time-slot <?= $slot['status'] === 'Available' ? 'available' : 'booked' ?>" 
                     data-id="<?= $slot['id'] ?>" onclick="selectSlot(<?= $slot['id'] ?>)">
                    <strong><?= $slot['slot_time'] ?></strong>
                    <small><?= $slot['status'] ?></small>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Popup Form -->
        <div id="formPopup" class="form-popup">
            <div class="form-container">
                <button class="close-btn" onclick="closeForm()">×</button>
                <h2>Book a Meeting</h2>
                <form method="POST" action="">
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

                    <button type="submit" name="book_meeting">Book Meeting</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let selectedSlot = null;

        // Show the form popup
        function selectSlot(slotId) {
            document.getElementById('slot_id').value = slotId;

            const selected = document.querySelector(`.time-slot[data-id='${slotId}']`);
            if (selected.classList.contains('booked')) {
                alert("This slot is already booked.");
                return;
            }

            document.getElementById('formPopup').classList.add('show');
        }

        // Close the form popup
        function closeForm() {
            document.getElementById('formPopup').classList.remove('show');
        }
    </script>

</body>
</html>
