<?php
include 'authentication.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COSCA Restaurant- location</title>
    <link rel="icon" type="image/webp" href="assets/logo.webp">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="location.css">
</head>

<body>
<header>
        <a href="landingPage.php" class="back-link">
            <i class="fas fa-chevron-left"></i> Go Back
        </a>
    </header>
    <!-- Search Bar -->
    <div class="search-container">
        <input type="text" id="locationInput" placeholder="Search location..." value="8844+GM7, Bishop Epifanio B, Dumaguete, Colegio de Santa Catalina de Alejandria">
        <button onclick="searchLocation()">
            <i class="fa-solid fa-search"></i>
        </button>
    </div>

    <!-- Tab Buttons -->
    <div class="tab-container">
        <button class="tab active-tab" onclick="showMap('normal')"><i class="fa-solid fa-map"></i> Map View</button>
        <button class="tab" onclick="showMap('satellite')"><i class="fa-solid fa-globe"></i> Satellite View</button>
    </div>

    <!-- Map Section -->
    <div class="map-container">
        <iframe id="mapFrame"
            src="https://www.google.com/maps?q=8844+GM7,Bishop+Epifanio+B,Bishop+Epifanio+Surban+St,Dumaguete,Negros+Oriental&output=embed"
            allowfullscreen
            loading="lazy">
        </iframe>
    </div>

    <script>
        function searchLocation() {
            let location = document.getElementById("locationInput").value.trim();
            if (location !== "") {
                let formattedLocation = encodeURIComponent(location);
                let mapFrame = document.getElementById("mapFrame");
                mapFrame.src = `https://www.google.com/maps?q=${formattedLocation}&output=embed`;
            }
        }

        function showMap(type) {
            let mapFrame = document.getElementById("mapFrame");
            let location = document.getElementById("locationInput").value.trim();
            let formattedLocation = encodeURIComponent(location);

            let normalURL = `https://www.google.com/maps?q=${formattedLocation}&output=embed`;
            let satelliteURL = normalURL + "&t=k";

            mapFrame.src = (type === "satellite") ? satelliteURL : normalURL;

            // Update button styles
            document.querySelectorAll('.tab').forEach(btn => btn.classList.remove('active-tab'));
            event.target.classList.add('active-tab');
        }
    </script>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>

</html>