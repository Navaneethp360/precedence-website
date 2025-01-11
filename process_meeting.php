<?php
$host = 'localhost'; 
$dbname = 'ashtiric_precedence_test'; 
$username = 'ashtiric_precedence'; 
$password = 'Precedence@2024';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form fields are set
    if (isset($_POST['first_name'], $_POST['last_name'], $_POST['company'], $_POST['email'], $_POST['phone'], $_POST['message'], $_POST['location'], $_POST['date'], $_POST['time_slot'])) {
        // Collect and sanitize data
        $first_name = htmlspecialchars(trim($_POST['first_name']));
        $last_name = htmlspecialchars(trim($_POST['last_name']));
        $company = htmlspecialchars(trim($_POST['company']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $phone = htmlspecialchars(trim($_POST['phone']));
        $message = htmlspecialchars(trim($_POST['message']));
        $location = htmlspecialchars(trim($_POST['location']));  // Changed topic to location
        $date = htmlspecialchars(trim($_POST['date']));
        $time_slot = htmlspecialchars(trim($_POST['time_slot']));

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format.";
            exit;
        }

        // Insert data into database
        $stmt = $pdo->prepare("INSERT INTO meetings (first_name, last_name, company, email, phone, message, location, date, time_slot) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$first_name, $last_name, $company, $email, $phone, $message, $location, $date, $time_slot]);

        // Send email notification to admins
        $to = "u.godharwala@precedencekw.com, umargodharwala1996@gmail.com";
        $subject = "New Meeting Scheduled";
        $body = "Meeting Details:\n\n" .
                "Name: $first_name $last_name\n" .
                "Company: $company\n" .
                "Email: $email\n" .
                "Phone: $phone\n" .
                "Location: $location\n" .  // Changed topic to location
                "Date: $date\n" .
                "Time: $time_slot\n" .
                "Message: $message";

        // Set email headers
        $headers = 'From: no-reply@precedencekw.com' . "\r\n" .
                   'Reply-To: no-reply@precedencekw.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        // Send email
        if (mail($to, $subject, $body, $headers)) {
            echo "Meeting scheduled successfully!";
        } else {
            echo "Failed to send email notification.";
        }
    } else {
        echo "All form fields are required.";
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
