<?php
require 'db_connect.php';
include 'authentication.php';
$product = 'product';
$quantity = 'quantity';
$prices = 'prices';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order/Header File</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/webp" href="assets/logo.webp">
    <link rel="stylesheet" href="orders.css">
    <style>
        nav {
            margin-top: 90px; /* Added top margin */
        }
    </style>
</head>

<body>
    <header>
        <a href="landingPage.php" class="back-link">
            <i class="fas fa-chevron-left"></i> Go Back
        </a>
    </header>
    <nav>
        <ul class="ulsp">
            <li data-url="foods.php">Menu</li>
            <li class="paymentTrigger">Payments</li>
        </ul>
    </nav>
    <div class="content">
        <div class="forTb">
            <table>
                <tr>
                    <th>Items</th>
                    <th>Cost</th>
                    <th>Quantity</th>
                </tr>
                <tr>
                    <td>Dress</td>
                    <td>$5.2</td>
                    <td>2</td>
                </tr>
            </table>
        </div>
        <br>

        <div class="forTb">
            <table>
                <tr>
                    <th>Discount Type</th>
                    <th>Discount Percentage</th>
                </tr>
                <tr>
                    <td>Student Discount</td>
                    <td>10%</td>
                </tr>
                <tr>
                    <td>PWD Discount</td>
                    <td>20%</td>
                </tr>
                <tr>
                    <td>Senior Citizen Discount</td>
                    <td>20%</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="paymentModal" id="paymentModal">
        <h2>Select Payment Method</h2>
        <div class="payment-option">
            <button class="paypal">
                <i class="fa-brands fa-paypal"></i>Pay with PayPal</button>
            <button class="gcash">Pay with GCash</button>
            <button class="cod">Pay with Cash</button>
        </div>
        <button class="close-btn" id="closeModal">Close</button>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("paymentModal");
            const trigger = document.querySelector(".paymentTrigger"); // Payments Button
            const closeModal = document.getElementById("closeModal");
            const gcashButton = document.querySelector(".gcash"); // GCash Button
            const paypalButton = document.querySelector(".paypal"); // PayPal Button

            if (trigger) {
                trigger.addEventListener("click", () => {
                    modal.style.display = "block";
                });
            }

            closeModal.addEventListener("click", () => {
                modal.style.display = "none";
            });

            // Redirect to GCash when clicking the GCash button
            gcashButton.addEventListener("click", () => {
                window.location.href = "https://www.gcash.com/signin"; // Redirect to GCash
            });

            // Redirect to PayPal when clicking the PayPal button
            paypalButton.addEventListener("click", () => {
                window.location.href = "https://www.paypal.com/signin"; // Redirect to PayPal
            });
        });
        document.querySelectorAll('.ulsp li').forEach(item => {
            item.addEventListener('click', () => {
                const url = item.dataset.url; // Get the URL from data-url attribute
                if (url) {
                    window.location.href = url;
                }
            });
        });
    </script>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>

</body>

</html>