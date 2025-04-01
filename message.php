<?php
include 'authentication.php';
require 'db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
    $message = trim($_POST['message']);
    if (!empty($message)) {
        // Insert message into database
        $stmt = $conn->prepare("INSERT INTO messages (username, message) VALUES (:username, :message)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':message', $message);
        
        if ($stmt->execute()) {
            // Send message via CallMeBot API
            $encoded_message = urlencode("From $username: $message");
            $url = "https://api.callmebot.com/whatsapp.php?phone=$admin_phone&text=$encoded_message&apikey=$callmebot_api_key";
            file_get_contents($url);
            
            echo "<script>alert('Message sent successfully!'); window.location.href='message.php';</script>";
        } else {
            echo "<script>alert('Failed to send message.'); window.location.href='message.php';</script>";
        }
    } else {
        echo "<script>alert('Message cannot be empty.'); window.location.href='message.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Send a Message to Admin</h2>
        <form action="message.php" method="POST">
            <label for="message">Your Message:</label>
            <textarea name="message" id="message" rows="5" required></textarea>
            <button type="submit">Send Message</button>
        </form>
        <a href="index.php">Back to Home</a>
    </div>
</body>
</html>
