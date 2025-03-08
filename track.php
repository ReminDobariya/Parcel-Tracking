<?php
session_start();
require_once 'config.php';
require_once 'header.php';

$trackingResult = null;
$error = null;

if (isset($_POST['track'])) {
    // Sanitize tracking number input
    $tracking_number = trim($_POST['tracking_number']);

    if (empty($tracking_number)) {
        $error = "Tracking number is required.";
    } else {
        // Prepare and execute the query to fetch parcel data by tracking number
        $sql = "SELECT * FROM parcels WHERE tracking_number = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            $error = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param("s", $tracking_number);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $trackingResult = $result->fetch_assoc();
            } else {
                $error = "No parcel found with this tracking number.";
            }
            $stmt->close();
        }
    }
}

// Format date helper function
function formatDate($dateString) {
    if (!$dateString) return 'Not available';
    $date = new DateTime($dateString);
    return $date->format('F j, Y, g:i A'); // Format: Month Day, Year, Time AM/PM
}
?>

<style>
.tracking-container {
    max-width: 500px;
    margin: 50px auto;
    padding: 20px;
    background: #f7f9fb;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}
.tracking-container h2 {
    color: #2c3e50;
    text-align: center;
    margin-bottom: 30px;
}
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    font-weight: 600;
    color: #34495e;
    margin-bottom: 10px;
}
.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    box-sizing: border-box;
}
.btn {
    width: 100%;
    padding: 12px;
    background: #2c3e50;
    color: white;
    font-size: 16px;
    font-weight: 600;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
.btn:hover {
    background: #34495e;
}
.tracking-result {
    margin-top: 30px;
    padding: 20px;
    background: #ecf0f1;
    border-radius: 6px;
    text-align: left;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
}
.tracking-result h3 {
    color: #2c3e50;
    margin-bottom: 20px;
}
.tracking-result p {
    font-size: 16px;
    margin-bottom: 12px;
}
.not-found, .error {
    text-align: center;
    padding: 20px;
    color: #e74c3c;
    background-color: #fbe6e6;
    border: 1px solid #e74c3c;
    border-radius: 6px;
    margin-top: 30px;
}
</style>

<div class="tracking-container">
    <h2>Track Your Parcel</h2>
    
    <form method="POST" action="" class="tracking-form">
        <div class="form-group">
            <label>Enter Tracking Number</label>
            <input type="text" name="tracking_number" placeholder="Tracking Number" required>
        </div>
        <button type="submit" name="track" class="btn">Track Parcel</button>
    </form>

    <?php if ($trackingResult): ?>
        <div class="tracking-result">
            <h3>Tracking Information</h3>
            <p><strong>Tracking Number:</strong> <?php echo htmlspecialchars($trackingResult['tracking_number']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($trackingResult['status']); ?></p>
            <p><strong>Sender Name:</strong> <?php echo htmlspecialchars($trackingResult['sender_name']); ?></p>
            <p><strong>Receiver Name:</strong> <?php echo htmlspecialchars($trackingResult['receiver_name']); ?></p>
            <p><strong>Origin Address:</strong> <?php echo htmlspecialchars($trackingResult['sender_street'] . ', ' . $trackingResult['sender_city'] . ', ' . $trackingResult['sender_state'] . ', ' . $trackingResult['sender_zip']); ?></p>
            <p><strong>Destination Address:</strong> <?php echo htmlspecialchars($trackingResult['receiver_street'] . ', ' . $trackingResult['receiver_city'] . ', ' . $trackingResult['receiver_state'] . ', ' . $trackingResult['receiver_zip']); ?></p>
            <p><strong>Parcel Weight:</strong> <?php echo htmlspecialchars($trackingResult['parcel_weight']); ?> kg</p>
            <p><strong>Delivery Type:</strong> <?php echo htmlspecialchars($trackingResult['delivery_type']); ?></p>
            <p><strong>Cost:</strong> ₹<?php echo htmlspecialchars($trackingResult['cost']); ?></p> <!-- Displaying the cost -->
            <p><strong>Last Updated:</strong> <?php echo formatDate($trackingResult['created_at']); ?></p>
        </div>
    <?php elseif ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>
