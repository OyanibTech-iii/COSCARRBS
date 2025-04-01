<?php
session_start();
require 'db_connect.php';
include 'authentication.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Restaurant Reservation & Menu</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<style>
     .cart-icon{
    margin-top: 2px;
    color: rgb(249, 152, 249);
    font-size: 12px;
     }
  
</style>
<body>
<div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
        <div class="logo-container">
        <div class="logo-frame">
            <img src="assets/logo.webp" alt="Company Logo" class="logo">        
        </div> 
       <h4 class="company-name">
    <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>
    <br>username
</h4>
      
    </div>
    <h4 class="h4sp"><i class="fa-solid fa-list"></i>Menu Categories</h4>
            <ul class="ulsp">
                <li><i class="fa-solid fa-calendar-check"></i> Reservation</li>
                <li><i class="fa-solid fa-concierge-bell"></i> Orders</li>
                <li><i class="fa-solid fa-receipt"></i>Reciept</li>
                <li><i class="fa-solid fa-scroll"></i>Policy</li>
                <li>Pasta</li>
                <li>Burgers</li>
                <li><i class="fa-solid fa-gear"></i>Settings</li>
                <li><i class="fa-solid fa-comment-dots"></i> Feedback</li>
                <li><i class="fa-solid fa-right-from-bracket"></i> Log out</li>
            </ul>
        </aside>   

        <!-- Main Content -->
        <main class="main-content">
            <nav>
                <ul class="header">
                    <li>Reservation</li>
                    <li>Orders</li>
                    <li>Policy</li>
                    <li>Log out</li>
                </ul>
            </nav>
            <div class="text"> 
                <header class="headerText">
                <h1>Restaurant Reservation & Billing System</h1>
                <h3 class="message">Reserve yours now</h3>
                </header>           
            </div>
            <!-- food menu choices -->
            <div class="menu">
                <?php
                $stmt = $conn->prepare("SELECT * FROM products");
                $stmt->execute();
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    // code use to dispay products and labels
                foreach ($products as $product) {
                    echo "
                        <div class='product'>
                            <img src='{$product['image']}' alt='{$product['name']}'>
                            <h3>{$product['name']}</h3>
                            <p>{$product['description']}</p>
                            <p>Price: ₱ <b>{$product['price']}</b></p>
                            <form action='includes/order.php' method='post'>
                                <input type='hidden' name='product_id' value='{$product['id']}'>
                                <div class='quantity-control'>
                                    <button type='button' class='quantity-btn decrement'>-</button>
                                    <input type='text' name='quantity' value='1' readonly>
                                    <button type='button' class='quantity-btn increment'>+</button>
                                </div>
                                <button class='btn' type='submit'>
                                    <span class='material-icons cart-icon'>add_shopping_cart</span> Buy Now
                                </button>
                            </form>
                        </div>
                    ";
                }
                ?>
            </div>
        </main>
    </div>
    <script>
        // logic for the events on the button clicks that wil have an inceremental and decremental value.
    document.querySelectorAll('.quantity-control').forEach(function(control) {
        const input = control.querySelector('input[name="quantity"]');
        const increment = control.querySelector('.increment');
        const decrement = control.querySelector('.decrement');

        increment.addEventListener('click', function() {
            input.value = parseInt(input.value) + 1;
        });

        decrement.addEventListener('click', function() {
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        });
    });

    //side bar navigation logic
    // adding events to the sidebar list that if clicks will be navigate to the other php file.
    document.querySelectorAll('ul li').forEach(item => {
  item.addEventListener('click', () => {
    if (item.innerText === 'Reservation') {
      window.location.href = 'reservationUi.php';
    }
    if (item.innerText === 'Pasta') {
      window.location.href = 'login.php';
    }
    if (item.innerText === 'Burgers') {
      window.location.href = 'signup.php';
    }
    if (item.innerText === 'Log out') {
      window.location.href = 'logout.php';
    }
  });
});

</script>

</body>
</html>
