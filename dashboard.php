<?php
include 'authentication.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="icon" type="image/webp" href="assets/logo.webp">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body>
    <aside class="sidebar">
        <ul class="ulsp">
            <li data-url="landingPage.php"><i class="fa fa-chevron-left"></i>Go back</li>
            <li data-url="reservations.php"><i class="fa-solid fa-calendar-check"></i> Reservations</li>
            <li data-url="orders.php"><i class="fa-solid fa-box"></i> My Orders</li>
            <li data-url="foods.php"><i class="fa-solid fa-utensils"></i> Foods</li>
            <li data-url="drinks.php"><i class="fa-solid fa-mug-hot"></i> Drinks</li>
            <li data-url="tables.php"><i class="fa-solid fa-chair"></i> Tables & Chairs</li>
        </ul>
    </aside>

    <div class="content">
        <div class="container mt-4">
            <h2 class="text-center mb-4">Dashboard</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <h5 class="text-center">Reservation Status</h5>
                        <div class="chart-container">
                            <canvas id="reservationStatusChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <h5 class="text-center">Monthly Bookings</h5>
                        <div class="chart-container">
                            <canvas id="monthlyBookingsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const reservationData = [0, 0, 0];
            const labels = ['Pending', 'Confirmed', 'Cancelled'];

            const ctx1 = document.getElementById('reservationStatusChart').getContext('2d');
            new Chart(ctx1, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: reservationData,
                        backgroundColor: ['#ffc107', '#28a745', '#dc3545']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom', // Position at the bottom
                            labels: {
                                color: 'white', // White text for legend
                                usePointStyle: true, // Use circle indicators
                                boxWidth: 10, // Adjust box size
                            }
                        }
                    },
                    layout: {
                        padding: {
                            bottom: 20 // Add padding for better spacing
                        }
                    }
                }
            });


            const ctx2 = document.getElementById('monthlyBookingsChart').getContext('2d');
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Monthly Bookings',
                        data: Array(12).fill(0),
                        backgroundColor: 'white'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: 'white' // White text for legend
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: 'white' // White text for X-axis labels
                            }
                        },
                        y: {
                            ticks: {
                                color: 'white' // White text for Y-axis labels
                            }
                        }
                    }
                }
            });
        });

        // Sidebar navigation
        document.querySelectorAll('.ulsp li').forEach(item => {
            item.addEventListener('click', () => {
                const url = item.dataset.url; // Get the URL from data-url attribute
                if (url) {
                    window.location.href = url;
                }
            });
        });
    </script>
</body>

</html>