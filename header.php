<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parcel Tracking System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --text-light: #ecf0f1;
            --text-dark: #2c3e50;
            --background-light: #f8f9fa;
            --shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: var(--background-light);
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        .navbar {
            background: var(--primary-color);
            padding: 15px 0;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            max-width: 1200px;
        }

        .nav-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: var(--text-light);
            font-size: 24px;
            text-decoration: none;
            font-weight: bold;
        }

        .nav-items {
            display: flex;
            gap: 20px;
        }

        .nav-items a {
            color: var(--text-light);
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: 500;
        }

        .nav-items a:hover {
            background: var(--secondary-color);
            transition: background 0.3s;
        }

        .menu-toggle {
            display: none;
        }

        @media (max-width: 768px) {
            .nav-items {
                display: none;
            }

            .nav-items.active {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--primary-color);
                padding: 20px;
            }

            .menu-toggle {
                display: block;
                color: white;
                font-size: 24px;
                cursor: pointer;
            }
        }
    </style>

</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-links">
                <a href="index.php" class="logo">ParcelTrack</a>
                <div class="menu-toggle"><i class="fas fa-bars"></i></div>
                <div class="nav-items">
                    <a href="index.php"><i class="fas fa-home"></i> Home</a>
                    <a href="track.php"><i class="fas fa-search"></i> Track Parcel</a>
                    <a href="pricing.php"><i class="fas fa-tags"></i> Pricing</a>
                    <a href="about.php"><i class="fas fa-info-circle"></i> About Us</a>
                    <?php if(isset($_SESSION['user_id']) && !isset($_SESSION['is_admin'])): ?>
                        <a href="send_parcel.php"><i class="fas fa-shipping-fast"></i> Send Parcel</a>
                        <a href="my_parcel.php"><i class="fas fa-box"></i> My Parcels</a> <!-- Added link to My Parcels -->
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    <?php elseif(isset($_SESSION['is_admin'])): ?>
                        <a href="update_status.php"><i class="fas fa-edit"></i> Update Status</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    <?php else: ?>
                        <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <script>
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.nav-items').classList.toggle('active');
        });
    </script>
</body>
</html>
