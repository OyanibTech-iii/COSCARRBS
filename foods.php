<?php
require 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gourmet Food Menu</title>
    <link rel="icon" type="image/webp" href="assets/logo.webp">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

        body {
            background-color: rgba(148, 23, 211, 0.81);
            color: white;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }

        .foodContainer {
            display: flex;
            flex-wrap: nowrap;
            justify-content: center;
            gap: 15px;
            max-width: 100%;
            overflow: hidden;
            margin-bottom: 20px; /* Added gap between rows */
        }

        .food-item {
            flex: 0 0 auto;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            width: 250px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .food-item:hover {
            transform: scale(1.05);
        }

        .food-item img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        .food-item h2 {
            margin: 10px 0;
            color: white;
            font-size: 1.1rem;
        }

        .food-item p {
            color: #e0e0e0;
            font-size: 0.9rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            padding: 10px 15px;
            margin: 15px;
            box-shadow: 0px 4px 8px rgba(18, 0, 20, 0.575);
            background-color: rgba(138, 6, 161, 0.938);
            color: white;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 10;
        }

        .back-link i {
            margin-right: 8px;
            font-size: 18px;
        }

        .back-link:hover {
            background-color: rgba(138, 6, 161, 0.829);
            color: white;
            box-shadow: 0px 4px 14px rgb(18, 0, 20);
            transform: translateX(-3px);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            display: flex;
            flex-direction: row;
            align-items: center;
            background-color: white;
            color: black;
            border-radius: 10px;
            padding: 20px;
            width: 90%;
            max-width: 600px;
            text-align: left;
        }

        .modal-content img {
            max-width: 40%;
            height: auto;
            border-radius: 10px;
            margin-right: 20px;
        }

        .modal-content .modal-text {
            flex: 1;
        }

        .modal-content h2 {
            margin-bottom: 10px;
        }

        .modal-content p {
            margin-bottom: 20px;
        }

        .modal-content .actions {
            display: flex;
            justify-content: flex-end; /* Adjust alignment after removing the "+" button */
            gap: 10px;
        }

        .modal-content button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .modal-content .buy-btn {
            background-color: #8a06a1;
            color: white;
        }

        .modal-content .buy-btn i {
            margin-right: 5px;
        }

        .modal-content .close-btn {
            background-color: #ccc;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const foodItems = document.querySelectorAll('.food-item');
            const modal = document.getElementById('foodModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalDescription = document.getElementById('modalDescription');
            const closeModal = document.getElementById('closeModal');

            foodItems.forEach(item => {
                item.addEventListener('click', () => {
                    const imageSrc = item.querySelector('img').src;
                    const title = item.querySelector('h2').textContent;
                    const description = item.querySelector('p').textContent;

                    modalImage.src = imageSrc;
                    modalTitle.textContent = title;
                    modalDescription.textContent = description;

                    modal.style.display = 'flex';
                });
            });

            closeModal.addEventListener('click', () => {
                modal.style.display = 'none';
            });

            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>
</head>

<body>
    <header>
        <a href="landingPage.php" class="back-link">
            <i class="fas fa-chevron-left"></i> Go Back
        </a>
    </header>
    <h1>Gourmet Food Selection</h1>
    <div id="foodModal" class="modal">
        <div class="modal-content">
            <img id="modalImage" src="" alt="Food Image">
            <div class="modal-text">
                <h2 id="modalTitle"></h2>
                <p id="modalDescription"></p>
                <div class="actions">
                    <button class="buy-btn"><i class="fas fa-shopping-cart"></i> Buy</button>
                    <button id="closeModal" class="close-btn">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="foodContainer">
        <div class="food-item">
            <img src="assets/foods(1).png" alt="Elegant Plated Dish">
            <h2>Artistic Culinary Creation</h2>
            <p>A beautifully presented dish with vibrant colors and intricate plating</p>
        </div>
        <div class="food-item">
            <img src="assets/foods(3).png" alt="Chocolate Dessert">
            <h2>Decadent Chocolate Delight</h2>
            <p>Smooth chocolate dessert adorned with delicate white flowers and pearl decorations</p>
        </div>
        <div class="food-item">
            <img src="assets/foods(2).png" alt="Red Fruits">
            <h2>Fresh Red Berries</h2>
            <p>Bright red fruits with mint leaves, elegantly served on a unique plate</p>
        </div>
    </div>
    <div class="foodContainer">
        <div class="food-item">
            <img src="assets/food(4).png" alt="Ground beef tacos">
            <h2>Ground beef tacos</h2>
            <p></p>
        </div>
        <div class="food-item">
            <img src="assets/food(5).png" alt="Pork quesadilla">
            <h2>Pork quesadilla</h2>
            <p></p>
        </div>
    </div>
</body>

</html>