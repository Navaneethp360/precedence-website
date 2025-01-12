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

// Fetch all time slots for the initial load (for current date or a default date)
function fetchSlots($date) {
    global $pdo;

    // Query available slots for the selected date
    $stmt = $pdo->prepare("
        SELECT ts.id, ts.slot_time, ts.status
        FROM time_slots ts
        LEFT JOIN meetings m ON ts.id = m.slot_id AND m.date = ?
        WHERE ts.status = 'Available' OR (ts.status = 'Booked' AND m.date = ?)
    ");
    $stmt->execute([$date, $date]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle the AJAX request for fetching slots
if (isset($_GET['fetch_slots'])) {
    $date = $_GET['date']; // Date selected from the calendar
    $slots = fetchSlots($date);

    echo json_encode(['success' => true, 'slots' => $slots]);
    exit;
}

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
        /* Add your styles here */
    </style>
</head>
<body>

    <div class="container">
        <h1>Book a Meeting</h1>

        <!-- Calendar -->
        <div class="calendar-container">
            <div id="calendarDays" class="calendar-days"></div>
        </div>

        <!-- Time Slot Slider -->
        <div class="slider-container" id="slotContainer">
            <!-- Available slots will be dynamically loaded here -->
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
                    
                    <label for="topic">Location:</label>
                    <div class="select-container">
                        <select id="topic" name="topic" required>
                            <option value="Precedence Office">Precedence Office</option>
                            <option value="Client Office">Client Office</option>
                        </select>
                    </div>
                    
                    <input type="hidden" id="date" name="date" required>
                    <input type="hidden" id="slot_id" name="slot_id" required>

                    <button type="submit" name="book_meeting">Book Meeting</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let selectedDate = null;

        // Function to select a date from calendar
        function selectDate(date) {
            selectedDate = date;
            document.getElementById('date').value = selectedDate;

            // Fetch available time slots for the selected date
            fetchTimeSlots(selectedDate);
        }

        // Function to fetch time slots for the selected date
        function fetchTimeSlots(date) {
            const slotContainer = document.getElementById('slotContainer');
            slotContainer.innerHTML = ''; // Clear the previous slots

            // Send AJAX request to fetch slots for the selected date
            fetch('?fetch_slots=1&date=' + date)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        data.slots.forEach(slot => {
                            const slotDiv = document.createElement('div');
                            slotDiv.classList.add('time-slot');
                            slotDiv.classList.add(slot.status === 'Available' ? 'available' : 'booked');
                            slotDiv.dataset.id = slot.id;
                            slotDiv.innerHTML = `<strong>${slot.slot_time}</strong><small>${slot.status}</small>`;
                            slotDiv.addEventListener('click', () => selectSlot(slot.id));
                            slotContainer.appendChild(slotDiv);
                        });
                    } else {
                        slotContainer.innerHTML = '<p>No available slots for this date.</p>';
                    }
                })
                .catch(error => console.error('Error fetching slots:', error));
        }

        // Function to select a time slot and show the booking form
        function selectSlot(slotId) {
            document.getElementById('slot_id').value = slotId;

            // Show the booking form popup
            document.getElementById('formPopup').classList.add('show');
        }

        // Close the booking form popup
        function closeForm() {
            document.getElementById('formPopup').classList.remove('show');
        }

        // Initialize the calendar and handle date selection
        function initCalendar() {
            const calendarDays = document.getElementById('calendarDays');
            const today = new Date();
            const currentMonth = today.getMonth();
            const currentYear = today.getFullYear();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            const firstDay = new Date(currentYear, currentMonth, 1).getDay();

            for (let i = 0; i < firstDay; i++) {
                const emptyCell = document.createElement('div');
                calendarDays.appendChild(emptyCell);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const dayCell = document.createElement('div');
                dayCell.classList.add('calendar-day');
                dayCell.textContent = day;
                const currentDate = new Date(currentYear, currentMonth, day);
                dayCell.dataset.date = `${currentYear}-${currentMonth + 1}-${day}`;

                dayCell.onclick = () => selectDate(dayCell.dataset.date);

                calendarDays.appendChild(dayCell);
            }
        }

        initCalendar();
    </script>

</body>
</html>
