<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data from the initial form
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $contact_number = filter_var(trim($_POST['contact_number']), FILTER_SANITIZE_STRING);
    $topic = filter_var(trim($_POST['topic']), FILTER_SANITIZE_STRING);
    $description = filter_var(trim($_POST['description']), FILTER_SANITIZE_STRING);

    // Get the date and time from the modal form
    $date = filter_var(trim($_POST['date']), FILTER_SANITIZE_STRING);
    $time = filter_var(trim($_POST['time']), FILTER_SANITIZE_STRING);

    // Prepare the email message with inline styles
    $subject = "Meeting Scheduled: $topic";
    $message = "
    <html>
    <head>
        <title>Meeting Scheduled: $topic</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f9f9f9;
                color: #333;
                margin: 0;
                padding: 0;
            }
            .container {
                width: 100%;
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: #fff;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
            .header {
                text-align: center;
                background-color: #4CAF50;
                color: #fff;
                padding: 20px;
            }
            .header h2 {
                margin: 0;
            }
            .content {
                margin: 20px 0;
            }
            .content p {
                font-size: 16px;
                line-height: 1.5;
            }
            .content strong {
                font-weight: bold;
            }
            .button {
                display: inline-block;
                background-color: #4CAF50;
                color: #fff;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 20px;
            }
            .button:hover {
                background-color: #45a049;
            }
            .cancel-button {
                background-color: #f44336;
            }
            .footer {
                text-align: center;
                font-size: 14px;
                color: #777;
                margin-top: 30px;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Meeting Scheduled: $topic</h2>
            </div>
            <div class='content'>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Contact Number:</strong> $contact_number</p>
                <p><strong>Topic:</strong> $topic</p>
                <p><strong>Description:</strong> $description</p>
                <p><strong>Meeting Date:</strong> $date</p>
                <p><strong>Meeting Time:</strong> $time</p>
                <a href='mailto:$email?subject=Confirm Meeting: $topic' class='button'>Confirm Meeting</a>
                <a href='mailto:$email?subject=Cancel Meeting: $topic' class='button cancel-button'>Cancel Meeting</a>
            </div>
            <div class='footer'>
                <p>&copy; 2024 Your Company Name. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Set the recipient email address (replace with your actual email)
    $to = "u.godharwala@precedencekw.com"; 

    // Set the email headers for HTML content
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Send the email
    if (mail($to, $subject, $message, $headers)) {
        $confirmation_message = "Meeting has been scheduled successfully! A confirmation email has been sent.";
    } else {
        $confirmation_message = "Failed to schedule the meeting. Please try again.";
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
        }

        input, textarea, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border: none;
        }

        button:hover {
            background-color: #45a049;
        }

        .confirmation-message {
            color: #4CAF50;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            width: 50%;
            max-width: 600px;
            border-radius: 5px;
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 30px;
            font-weight: bold;
            color: #000;
            background: none;
            border: none;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #f44336;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Schedule a Meeting</h2>

    <?php
    // Show confirmation message if the form was successfully submitted
    if (isset($confirmation_message)) {
        echo "<div class='confirmation-message'>$confirmation_message</div>";
    }
    ?>

    <form id="meetingForm" method="POST">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required>

        <label for="contact_number">Contact Number</label>
        <input type="text" id="contact_number" name="contact_number" required>

        <label for="topic">Meeting Topic</label>
        <input type="text" id="topic" name="topic" required>

        <label for="description">Meeting Description</label>
        <textarea id="description" name="description" required></textarea>

        <button type="button" id="sendButton">Set Meeting Date and Time</button>
    </form>
</div>

<!-- Modal for Date and Time -->
<div class="modal" id="meetingModal">
    <div class="modal-content">
        <button class="close-btn" onclick="closeModal()">×</button>
        <h2>Set Meeting Date and Time</h2>
        <form method="POST" id="dateTimeForm">
            <input type="hidden" id="name_modal" name="name">
            <input type="hidden" id="email_modal" name="email">
            <input type="hidden" id="contact_number_modal" name="contact_number">
            <input type="hidden" id="topic_modal" name="topic">
            <input type="hidden" id="description_modal" name="description">

            <label for="date">Meeting Date</label>
            <input type="date" id="date" name="date" required>

            <label for="time">Meeting Time</label>
            <select id="time" name="time" required>
                <option value="09:00 AM">09:00 AM </option>
                <option value="10:00 AM">10:00 AM </option>
                <option value="11:00 AM">11:00 AM </option>
                <option value="12:00 PM">12:00 PM </option>
                <option value="01:00 PM">01:00 PM </option>
                <option value="02:00 PM">02:00 PM </option>
                <option value="03:00 PM">03:00 PM </option>
                <option value="04:00 PM">04:00 PM </option>
                <option value="05:00 PM">05:00 PM </option>
            </select>

            <button type="submit" id="submitMeeting">Schedule Meeting</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('sendButton').addEventListener('click', function () {
        var form = document.getElementById('meetingForm');
        var modal = document.getElementById('meetingModal');

        document.getElementById('name_modal').value = form.name.value;
        document.getElementById('email_modal').value = form.email.value;
        document.getElementById('contact_number_modal').value = form.contact_number.value;
        document.getElementById('topic_modal').value = form.topic.value;
        document.getElementById('description_modal').value = form.description.value;

        modal.style.display = 'flex';
    });

    function closeModal() {
        document.getElementById('meetingModal').style.display = 'none';
    }
</script>

</body>
</html>
