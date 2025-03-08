<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';
require_once 'header.php';

// Check if tracking number is provided
if (!isset($_GET['tracking_number'])) {
    die("Tracking number not specified.");
}

$tracking_number = $_GET['tracking_number'];

// Fetch parcel details based on the tracking number
$stmt = $conn->prepare("SELECT * FROM parcels WHERE tracking_number = ? AND user_id = ?");
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$user_id = $_SESSION['user_id'];
$stmt->bind_param("si", $tracking_number, $user_id);
if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$parcel = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$parcel) {
    die("No parcel found with the provided tracking number.");
}
?>

<style>
.parcel-detail {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
}

.back-button {
    display: inline-block;
    margin: 20px 0;
    padding: 10px 20px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    cursor: pointer;
}

.back-button:hover {
    background-color: #2980b9;
}
</style>

<div class="parcel-detail">
    <h2>Parcel Details</h2>
    <p><strong>Tracking Number:</strong> <?php echo htmlspecialchars($parcel['tracking_number']); ?></p>
    <p><strong>Sender Name:</strong> <?php echo htmlspecialchars($parcel['sender_name']); ?></p>
    <p><strong>Sender Address:</strong> <?php echo htmlspecialchars($parcel['sender_street'] . ', ' . $parcel['sender_city'] . ', ' . $parcel['sender_state'] . ' - ' . $parcel['sender_zip']); ?></p>
    <p><strong>Sender Phone:</strong> <?php echo htmlspecialchars($parcel['sender_phone']); ?></p>
    <p><strong>Receiver Name:</strong> <?php echo htmlspecialchars($parcel['receiver_name']); ?></p>
    <p><strong>Receiver Address:</strong> <?php echo htmlspecialchars($parcel['receiver_street'] . ', ' . $parcel['receiver_city'] . ', ' . $parcel['receiver_state'] . ' - ' . $parcel['receiver_zip']); ?></p>
    <p><strong>Receiver Phone:</strong> <?php echo htmlspecialchars($parcel['receiver_phone']); ?></p>
    <p><strong>Parcel Weight:</strong> <?php echo htmlspecialchars($parcel['parcel_weight']); ?> kg</p>
    <p><strong>Delivery Type:</strong> <?php echo htmlspecialchars($parcel['delivery_type']); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($parcel['status']); ?></p>
    <p><strong>Cost:</strong> ₹<?php echo htmlspecialchars($parcel['cost']); ?></p>

    <!-- Back button to return to My Parcels -->
    <a href="my_parcel.php" class="back-button">Back to My Parcels</a>
</div>

<?php require_once 'footer.php'; ?>
