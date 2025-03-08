<?php
session_start();

if (!isset($_SESSION['tracking_number'])) {
    header("Location: send_parcel.php");
    exit();
}

$tracking_number = $_SESSION['tracking_number'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parcel Submitted</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .success-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .success-container h2 {
            color: #2ecc71;
        }
        .tracking-number {
            font-size: 24px;
            margin: 20px 0;
            color: #3498db;
            display: inline-block;
            cursor: pointer;
        }
        .copy-icon {
            color: #909692;
            cursor: pointer;
            margin-left: 10px;
        }
        .button {
            display: inline-block;
            margin-top: 20px;
            margin-left: 10px;
            padding: 10px 20px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }
        .button:hover {
            background-color: #34495e;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <h2>Parcel Submitted Successfully!</h2>
        <p>Your tracking number is:</p>
        <div class="tracking-number" id="trackingNumber">
            <?php echo htmlspecialchars($tracking_number); ?>
            <i class="fas fa-copy copy-icon" onclick="copyTrackingNumber()" title="Copy Tracking Number"></i>
        </div>
        <p>You can use this tracking number to track your parcel.</p>
        <a href="track.php" class="button">Track Parcel</a>
        <a href="index.php" class="button">Home</a>
    </div>

    <script>
        function copyTrackingNumber() {
            const trackingNumber = document.getElementById('trackingNumber').textContent;
            navigator.clipboard.writeText(trackingNumber).then(() => {
                alert('Tracking number copied to clipboard!');
            }).catch(err => {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
</body>
</html>

<?php
// Unset the tracking number session variable after use
unset($_SESSION['tracking_number']);
?>
