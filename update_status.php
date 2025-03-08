<?php
session_start();
require_once 'config.php';
require_once 'header.php';

// Redirect if not admin
if (!isset($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit();
}

// Initialize messages
$success_message = '';
$error_message = '';

// Update status when form is submitted
if (isset($_POST['update_status'])) {
    $parcel_id = $_POST['parcel_id'];
    $new_status = $_POST['status'];

    // Fetch the current status of the parcel
    $sql = "SELECT status FROM parcels WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $parcel_id);
    $stmt->execute();
    $stmt->bind_result($current_status);
    $stmt->fetch();
    $stmt->close();

    // Check if the new status is different from the current status
    if ($current_status !== $new_status) {
        // Prepare and execute the update query
        $sql = "UPDATE parcels SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_status, $parcel_id);

        if ($stmt->execute()) {
            $success_message = "Status updated successfully.";
        } else {
            $error_message = "Failed to update status. Please try again.";
        }
        $stmt->close();
    } else {
        $error_message = "No changes were made to the status.";
    }
}

// Fetch undelivered parcels
$sql = "SELECT id, tracking_number, sender_name, receiver_name, status FROM parcels WHERE status != 'Delivered'";
$result = $conn->query($sql);
?>

<style>
.update-status-container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background: #f7f9fb;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

.update-status-container h2 {
    color: #2c3e50;
    text-align: center;
    margin-bottom: 30px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.table th, .table td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

.table th {
    background-color: #34495e;
    color: #ecf0f1;
}

.form-group select, .form-group button {
    padding: 8px;
    margin: 0;
}

.success, .error {
    text-align: center;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 6px;
}

.success {
    color: #27ae60;
    background-color: #eafaf1;
    border: 1px solid #27ae60;
}

.error {
    color: #e74c3c;
    background-color: #fbe6e6;
    border: 1px solid #e74c3c;
}
</style>

<div class="update-status-container">
    <h2>Manage Parcel Status</h2>

    <?php if ($success_message): ?>
        <div class="success"><?php echo $success_message; ?></div>
    <?php elseif ($error_message): ?>
        <div class="error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Tracking Number</th>
                    <th>Sender</th>
                    <th>Receiver</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($parcel = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($parcel['tracking_number']); ?></td>
                        <td><?php echo htmlspecialchars($parcel['sender_name']); ?></td>
                        <td><?php echo htmlspecialchars($parcel['receiver_name']); ?></td>
                        <td><?php echo htmlspecialchars($parcel['status']); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="parcel_id" value="<?php echo $parcel['id']; ?>">
                                <select name="status" required>
                                    <option value="Pending" <?php if ($parcel['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                    <option value="Sent" <?php if ($parcel['status'] == 'Sent') echo 'selected'; ?>>Sent</option>
                                    <option value="Delivered" <?php if ($parcel['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                                </select>
                                <button type="submit" name="update_status">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No undelivered parcels found.</p>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>
