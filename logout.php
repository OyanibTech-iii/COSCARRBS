<?php 
include 'authentication.php';
require 'db_connect.php';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$name = $username;
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/webp" href="assets/logo.webp">
    <title>Logging Out...</title>
    <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

        /* Smooth Background Gradient */
        body {
            background: linear-gradient(135deg, rgb(172, 26, 235), rgb(58, 2, 80));
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            transition: opacity 0.8s ease-in-out;
            padding: 20px;
        }

        /* Glassmorphism Effect */
        .logout-container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 320px;
            color: white;
            font-size: 18px;
        }

        /* Animated Loader */
        .loader {
            width: 25px;
            height: 25px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 5px solid white;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            body{
                background: linear-gradient(135deg, rgb(249, 180, 3), rgb(237, 113, 24));  
            }
            .logout-container {
                padding: 20px;
                font-size: 16px;
            }
            .loader {
                width: 15px;
                height: 15px;
                border-width: 4px;
            }
        }
    </style>
</head>
<body>

    <div class="logout-container">
        <p>Logging Out<p>
        <p><?php echo "Thank you for visiting us ".$name; ?></p>
        <div class="loader"></div>
        <p>You will be redirected shortly.</p>
    </div>

    <script>
        setTimeout(() => {
            document.body.style.opacity = "0"; // Fade out effect
            setTimeout(() => {
                window.location.href = 'signup.php';
            }, 800);
        }, 1000);
    </script>

</body>
</html>
