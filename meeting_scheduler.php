<html lang="en"><head>
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
        input, textarea, button {
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
        .confirmation {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .error {
            background-color: #f44336;
            color: white;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body cz-shortcut-listen="true">

<div class="container">
    <h2>Schedule a Meeting</h2>

            <div class="confirmation">Your meeting has been scheduled successfully!</div>
    
    <form method="POST">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" required="" fdprocessedid="4nl0fj">

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required="" fdprocessedid="5ez8c">

        <label for="contact_number">Contact Number</label>
        <input type="text" id="contact_number" name="contact_number" required="" fdprocessedid="6x7lgh">

        <label for="date">Meeting Date</label>
        <input type="date" id="date" name="date" required="">

        <label for="time">Meeting Time</label>
        <input type="time" id="time" name="time" required="">

        <label for="topic">Meeting Topic</label>
        <input type="text" id="topic" name="topic" required="" fdprocessedid="6dsgx8">

        <label for="description">Meeting Description</label>
        <textarea id="description" name="description" required=""></textarea>

        <button type="submit" class="button" fdprocessedid="g5hn8">Submit</button>
    </form>
</div>



<span id="PING_IFRAME_FORM_DETECTION" style="display: none;"></span></body></html>