<?php
require_once 'db_connect.php';
include 'authentication.php';
// Input validation and sanitization function
function sanitizeInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Centralized error handling function
function showError($message)
{
    echo "<script>
        alert('" . htmlspecialchars($message, ENT_QUOTES) . "');
        window.history.back();
        exit();
    </script>";
}

// Validate date function
function isValidDate($date)
{
    $currentDate = new DateTime();
    $inputDate = DateTime::createFromFormat('Y-m-d', $date);

    return $inputDate
        && $inputDate >= $currentDate
        && $inputDate->format('Y') <= 2030;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $username = sanitizeInput($_POST['username'] ?? '');
    $date = sanitizeInput($_POST['date'] ?? '');
    $table_type = sanitizeInput($_POST['tables'] ?? '');
    $chair_type = sanitizeInput($_POST['chairs'] ?? '');

    // Validate inputs
    if (empty($username) || empty($date) || empty($table_type) || empty($chair_type)) {
        showError('All fields are required.');
    }

    // Validate date
    if (!isValidDate($date)) {
        showError('Invalid date selection.');
    }

    // Validate table and chair types
    $validTableTypes = ['plastic', 'metal', 'wood', 'glass', 'none'];
    $validChairTypes = ['plastic', 'metal', 'wood', 'cushioned', 'none'];

    if (!in_array($table_type, $validTableTypes) || !in_array($chair_type, $validChairTypes)) {
        showError('Invalid table or chair type.');
    }

    try {
        // Disable error reporting and use exceptions for database errors
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if username exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            showError('Username does not exist. Please try again.');
        }

        // Check if the date is already reserved
        $stmt = $conn->prepare("SELECT * FROM reservations WHERE reservation_date = :date");
        $stmt->bindParam(':date', $date);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            showError('The selected date is already reserved. Please choose another date.');
        }

        // Insert the reservation
        $stmt = $conn->prepare("INSERT INTO reservations (username, reservation_date, table_type, chair_type) VALUES (:username, :date, :table_type, :chair_type)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':table_type', $table_type);
        $stmt->bindParam(':chair_type', $chair_type);

        if ($stmt->execute()) {
            // Reservation successful
            echo "<script>
                alert('Reservation successful! Your reservation details will be confirmed via email.');
                window.location.href = 'index.php';
            </script>";
            exit();
        } else {
            showError('Could not reserve the date. Please try again later.');
        }
    } catch (PDOException $e) {
        // Log the actual error for debugging
        error_log('Database error: ' . $e->getMessage());
        showError('A database error occurred. Please try again later.');
    }
}

// Close the database connection
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Calendar</title>
    <link rel="icon" type="image/webp" href="assets/logo.webp">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="reservation.css">
</head>

<body>
    <header>
        <a href="landingPage.php" class="back-link">
            <i class="fas fa-chevron-left"></i> Go Back
        </a>
    </header>

    <div class="calendar-container">
        <div class="calendar-header">
            <i id="prevMonth" class="fas fa-chevron-left" onclick="changeMonth(-1)"></i>
            <span id="calendarTitle"></span>
            <i id="nextMonth" class="fas fa-chevron-right" onclick="changeMonth(1)"></i>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Sun</th>
                    <th>Mon</th>
                    <th>Tue</th>
                    <th>Wed</th>
                    <th>Thu</th>
                    <th>Fri</th>
                    <th>Sat</th>
                </tr>
            </thead>
            <tbody id="calendarBody">
            </tbody>
        </table>
    </div>

    <div id="reservationForm" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeForm()">&times;</span>
            <h3>Reserve Date: <span id="selectedDate"></span></h3>
            <form action="reserve.php" method="post">
                <input type="hidden" name="date" id="dateInput">
                <input type="text" name="username" placeholder="Username" required>
                <div class="form-group">
                    <select name="tables" required>
                        <option value="" disabled selected>Select Table Type</option>
                        <option value="plastic">Plastic Table</option>
                        <option value="metal">Metal Table</option>
                        <option value="wood">Wooden Table</option>
                        <option value="glass">Glass Table</option>
                        <option value="none">No Table</option>
                    </select>
                </div>

                <div class="form-group">
                    <select name="chairs" required>
                        <option value="" disabled selected>Select Chair Type</option>
                        <option value="plastic">Plastic Chairs</option>
                        <option value="metal">Metal Chairs</option>
                        <option value="wood">Wooden Chairs</option>
                        <option value="cushioned">Cushioned Chairs</option>
                        <option value="none">No Chairs</option>
                    </select>
                </div>
                <button type="submit">Reserve Now</button>
            </form>
        </div>
    </div>

    <script>
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth() + 1;
        let currentYear = currentDate.getFullYear();
        const maxYear = 2030;

        function updateCalendar() {
            if (currentYear > maxYear) return;

            let monthName = new Date(currentYear, currentMonth - 1).toLocaleString('en-us', {
                month: 'long',
                year: 'numeric'
            });
            document.getElementById("calendarTitle").innerText = monthName;

            // Enable/disable navigation buttons
            document.getElementById("prevMonth").classList.toggle('disabled-nav',
                currentYear === currentDate.getFullYear() && currentMonth === currentDate.getMonth() + 1);

            let tableBody = "<tr>",
                day = 1;
            let firstDay = new Date(currentYear, currentMonth - 1, 1).getDay();
            let daysInMonth = new Date(currentYear, currentMonth, 0).getDate();

            for (let i = 0; i < 42; i++) {
                if (i < firstDay || day > daysInMonth) {
                    tableBody += "<td></td>";
                } else {
                    let dateStr = `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    let isPastDate = new Date(dateStr) < currentDate;
                    let cellClass = isPastDate ? 'disabled' : 'available';
                    let onClick = isPastDate ? '' : `onclick="openForm('${dateStr}')"`;
                    tableBody += `<td class='${cellClass}' ${onClick}>${day}</td>`;
                    day++;
                }
                if (i % 7 === 6) tableBody += "</tr><tr>";
            }
            document.getElementById("calendarBody").innerHTML = tableBody + "</tr>";
        }

        function changeMonth(step) {
            currentMonth += step;
            if (currentMonth < 1) {
                currentMonth = 12;
                currentYear--;
            } else if (currentMonth > 12) {
                currentMonth = 1;
                currentYear++;
            }
            updateCalendar();
        }

        function openForm(date) {
            document.getElementById("selectedDate").innerText = date;
            document.getElementById("dateInput").value = date;
            let modal = document.getElementById("reservationForm");
            modal.style.display = "flex";
            setTimeout(() => {
                document.querySelector(".modal-content").classList.add("show");
            }, 10);
        }

        function closeForm() {
            let modalContent = document.querySelector(".modal-content");
            modalContent.classList.remove("show");
            setTimeout(() => {
                document.getElementById("reservationForm").style.display = "none";
            }, 300);
        }

        updateCalendar();
    </script>
    <center>
        <footer>
            <?php include 'footer.php'; ?>
        </footer>
    </center>
</body>

</html>