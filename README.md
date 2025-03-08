# ParcelTrack

## Overview
ParcelTrack System is an online courier management system that allows users to send parcels, track shipments, and calculate delivery costs effortlessly. The system provides a seamless experience for users to manage their shipments and for administrators to oversee parcel deliveries efficiently.

## Features
- **User Authentication:** Secure login and registration system for users.
- **Send Parcel:** Users can enter sender and receiver details, select delivery type, and calculate shipping costs before sending.
- **Parcel Tracking:** Allows users to track their parcels in real-time using a unique tracking number.
- **Cost Calculation:** Automatically determines delivery charges based on the distance between states.
- **User Dashboard:** Displays all sent and received parcels with their status.
- **Admin Panel:** Enables administrators to manage parcels, update delivery statuses, and monitor transactions.
- **Responsive UI:** Designed for a smooth user experience across different devices.

## Technologies Used
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL

## Installation

### Clone the Repository
```bash
git clone https://github.com/your-username/parcel-tracking-system.git
```

### Import Database
1. Open phpMyAdmin.
2. Create a new database (e.g., `courier_db`).
3. Import the provided `courier_db.sql` file.

### Configure Database Connection
Edit the `config.php` file with the following details:
```php
$servername = "localhost";
$username = "root";
$password = "";
$database = "courier_db";
```

### Run on Local Server
1. Place the project in `htdocs` (if using XAMPP).
2. Start **Apache** & **MySQL**.
3. Open `http://localhost/parcel-tracking-system/` in your browser.
