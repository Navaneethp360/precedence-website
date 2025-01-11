<?php
$host = 'localhost'; 
$dbname = 'ashtiric_precedence_test'; 
$username = 'ashtiric_precedence'; 
$password = 'Precedence@2024';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Collect data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $company = $_POST['company'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];
    $topic = $_POST['topic'];
    $date = $_POST['date'];
    $time_slot = $_POST['time_slot'];

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO meetings (first_name, last_name, company, email, phone, message, topic, date, time_slot) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $company, $email, $phone, $message, $topic, $date, $time_slot]);

    // Send email
    $to = "u.godharwala@precedencekw.com, umargodharwala1996@gmail.com";
    $subject = "New Meeting Scheduled";
    $body = "Meeting Details:\n\n
             Name: $first_name $last_name\n
             Company: $company\n
             Email: $email\n
             Phone: $phone\n
             Topic: $topic\n
             Date: $date\n
             Time: $time_slot\n
             Message: $message";

    mail($to, $subject, $body);

    echo "Meeting scheduled successfully!";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
