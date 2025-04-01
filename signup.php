<?php
  require_once 'db_connect.php'; // Include your database connection file

    // Ensure the 'is_verified' column exists in the 'users' table
    try {
        $conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS is_verified TINYINT(1) DEFAULT 0");
    } catch (PDOException $e) {
        die("Error updating database schema: " . $e->getMessage());
    }

    // Ensure the 'verification_code' column exists in the 'users' table
    try {
        $conn->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS verification_code VARCHAR(64) DEFAULT NULL");
    } catch (PDOException $e) {
        die("Error updating database schema: " . $e->getMessage());
    }

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $verification_code = bin2hex(random_bytes(32)); // Generate a random verification code

        // Check if email already exists
        $checkEmail = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($checkEmail);

        if (!$stmt) {
            die("Query preparation failed: " . $conn->errorInfo()[2]);
        }

        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (count($result) > 0) {
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
                    title: '<span class =\"custom-swal-title\">Login Failed<br><span class =\"username\">$username</span> is Already Exist</span>',
                    html: '<span class =\"custom-swal-errorMessage\">try again</span>',
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
  </script>
  <style>
  .username {
      color: white
      font-weight: bold;
      text-decoration: underline;
  }
  .custom-swal-errorMessage {
      color: white
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
      background-color: green;
  }
  .custom-swal-button:before {
      border: none;
  }
  </style>";
        } else {
            // Insert new user into database
            $query = "INSERT INTO users (username, email, password, is_verified, verification_code) 
                    VALUES (:username, :email, :password, 0, :verification_code)";

            $stmt = $conn->prepare($query);

            if (!$stmt) {
                header("Location: signup.php");
            }

            $stmt->bindValue(":username", $username, PDO::PARAM_STR);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->bindValue(":password", $password, PDO::PARAM_STR);
            $stmt->bindValue(":verification_code", $verification_code, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "Account created! Please verify your email.";
                $_SESSION['email'] = $email;
                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . $stmt->errorInfo()[2];
            }
        }

        $stmt = null;
    }

    // Properly close the database connection
    $conn = null;
    ?>
    <!-- html document for animation and display -->
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="theme-color" content="#3498db">
        <title>Sign Up Page</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link rel="icon" type="image/webp" href="assets/logo.webp">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="signup.css">
    </head>

    <body>
        <!-- canvas declaration -->
        <!-- sign up form -->
        <canvas id="particleCanvas"></canvas>
        <div class="signup-container">
            <h2>Create Account</h2>
            <img src="assets/logo.webp" alt="Company Logo" class="logo">
            <form method="post" action="signup.php">
                <input type="text" name="username" placeholder="Username" autocomplete="off" required>
                <input type="email" name="email" placeholder="Email" autocomplete="off" required>
                <div class="password-container">
                    <input type="password" name="password" id="password" placeholder="Password" autocomplete="off" required>
                    <span class="eye-icon" id="toggle-password" onclick="togglePassword()">
                        <i id="eye-icon" class="fas fa-eye"></i>
                    </span>
                </div>

                <button id="btnSubmit" type="submit">Sign Up</button>
            </form>
            <a href="login.php">Already have an account? <b>Log In<b></a>
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



            //checking button if active
            const signup = document.querySelector("#btnSubmit");
            signup.addEventListener('click', (event) => {
                // debugging display
                console.log("Button is Actived");
            });
            // canvas for background animation
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

            init();
            animate();
            window.addEventListener("resize", () => {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                particles.length = 0;
                init(); // Repopulate the particles
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