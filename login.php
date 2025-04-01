<?php
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "customersdb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the username and password from the POST request
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL to fetch the user by username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, check if password matches
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Correct login credentials
            $_SESSION['username'] = $username;
            header('Location: landingPage.php');
            exit();
        } else {
            // Password mismatch
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo "<script>
            window.onload = function() {
                const sound = new Audio('soundfx/errsound.mp3');
                sound.play();

                const backgroundElements = document.querySelectorAll(\"body > *:not(.swal2-container)\");
                backgroundElements.forEach(element => {
                    element.style.filter = \"blur(5px)\";
                });

                Swal.fire({
                    title: '<span class=\"custom-swal-title\">Login Failed<br>Incorrect password</span>',
                    html: '<span class=\"custom-swal-errorMessage\">Please try again</span>',
                    imageUrl: 'assets/error-icon.jpg',
                    imageWidth: 100,
                    imageHeight: 100,
                    confirmButtonText: 'Ok',
                    customClass: {
                        popup: 'custom-swal-popup',
                        confirmButton: 'custom-swal-button'
                    }
                }).then(() => {
                    backgroundElements.forEach(element => {
                        element.style.filter = \"none\";
                    });
                });
            };
            </script>";
        }
    } else {
        // Username not found
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo "<script>
        window.onload = function() {
            const sound = new Audio('soundfx/errsound.mp3');
            sound.play();

            const backgroundElements = document.querySelectorAll(\"body > *:not(.swal2-container)\");
            backgroundElements.forEach(element => {
                element.style.filter = \"blur(5px)\";
            });

            Swal.fire({
                title: '<span class=\"custom-swal-title\">Login Failed<br>No users found</span>',
                html: '<span class=\"custom-swal-errorMessage\">Please try again</span>',
                imageUrl: 'assets/error-icon2.webp',
                imageWidth: 100,
                imageHeight: 100,
                confirmButtonText: 'Ok',
                customClass: {
                    popup: 'custom-swal-popup',
                    confirmButton: 'custom-swal-button'
                }
            }).then(() => {
                backgroundElements.forEach(element => {
                    element.style.filter = \"none\";
                });
            });
        };
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/webp" href="assets/logo.webp">
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <!-- canvas tag declaration -->
    <!-- log in form -->
    <canvas id="particleCanvas"></canvas>
    <form action="index.php" method="post"></form>
    <div class="login-container">
        <h2 class="text">Login</h2>
        <img src="assets/logo.webp" alt="Company Logo" class="logo">
        <p class="tagline">Reserve Your Seat, Let Us Handle the Rest.</p>
        <form method="post" action="login.php">
            <input type="text" name="username" placeholder="Username" autocomplete="off" required>
            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Password" autocomplete="off" required>
                <span class="eye-icon" id="toggle-password" onclick="togglePassword()">
                    <i id="eye-icon" class="fas fa-eye"></i>
                </span>
            </div>
            <button type="submit">Login</button>
        </form>
        <a href="signup.php">Don't have an account? <b>Sign Up</b></a>
    </div>
    <script>
        // hide and show password icon && logic
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eye-icon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text"; // Show password
                eyeIcon.classList.remove("fa-eye"); // Remove closed eye icon
                eyeIcon.classList.add("fa-eye-slash"); // Add open eye icon
            } else {
                passwordInput.type = "password"; // Hide password
                eyeIcon.classList.remove("fa-eye-slash"); // Remove open eye icon
                eyeIcon.classList.add("fa-eye"); // Add closed eye icon
            }
        }
        // creation of animation in canvas for falling particles effects
        const canvas = document.getElementById("particleCanvas");
        const ctx = canvas.getContext("2d");

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const particles = [];

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 5 + 1;
                this.speedY = Math.random() * 2 + 0.2;
                this.color = `rgba(255, 255, 255, ${Math.random()})`;
            }
            update() {
                this.y += this.speedY;
                if (this.y > canvas.height) {
                    this.y = 0 - this.size;
                    this.x = Math.random() * canvas.width;
                }
            }
            draw() {
                ctx.fillStyle = this.color;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        function init() {
            for (let i = 0; i < 100; i++) {
                particles.push(new Particle());
            }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach((particle) => {
                particle.update();
                particle.draw();
            });
            requestAnimationFrame(animate);
        }
        // function call  for  the animation of particles random and continuosly 
        init();
        animate();
        // animation effects on a resize window
        window.addEventListener("resize", () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            particles.length = 0;
            init();
        });
    </script>
    <style>
        .custom-swal-errorMessage {
            color: white;
        }

        .custom-swal-title {
            font-size: 32px;
            color: white;
            letter-spacing: 2px;
        }

        .custom-swal-popup {
            border-radius: 12px;
            background: purple;
        }

        .custom-swal-button {
            border: none;
            background-color: rgb(70, 122, 235);
            border-radius: 8px;
            padding: 10px 50px; /* Adjusted length */
        }

        .custom-swal-button:before {
            border: none;
        }
    </style>
</body>

</html>