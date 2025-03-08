<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';
require_once 'header.php';

// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// Fetch parcels for the logged-in user
$stmt = $conn->prepare("SELECT tracking_number, sender_name, receiver_name, status FROM parcels WHERE user_id = ?");
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$result = $stmt->get_result();
$parcels = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<style>
.parcel-list {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

.parcel-item {
    padding: 15px;
    border-bottom: 1px solid #ddd;
    cursor: pointer;
}

.parcel-item:hover {
    background: #f1f1f1;
}

h2 {
    color: #333;
}

.parcel-info {
    display: flex;
    justify-content: space-between;
}
</style>

<div class="parcel-list">
    <h2>My Parcels</h2>
    <?php if (empty($parcels)): ?>
        <p>No parcels found.</p>
    <?php else: ?>
        <?php foreach ($parcels as $parcel): ?>
            <div class="parcel-item" onclick="window.location.href='parcel_details.php?tracking_number=<?php echo htmlspecialchars($parcel['tracking_number']); ?>'">
                <div class="parcel-info">
                    <strong><?php echo htmlspecialchars($parcel['tracking_number']); ?></strong>
                    <span>Status: <?php echo htmlspecialchars($parcel['status']); ?></span>
                </div>
                <div>Sender: <?php echo htmlspecialchars($parcel['sender_name']); ?></div>
                <div>Receiver: <?php echo htmlspecialchars($parcel['receiver_name']); ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>
