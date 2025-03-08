<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';
require_once 'header.php';
require_once 'states.php';

// Function to generate a tracking number
function generateShortTrackingNumber($user_id) {
    $prefix = "PKL";
    $date_component = date('dmy');
    $random_letter = chr(rand(65, 90));
    $random_digits = mt_rand(100, 999);
    $user_component = "U" . str_pad($user_id, 3, '0', STR_PAD_LEFT);
    return "{$prefix}{$date_component}{$user_component}{$random_letter}{$random_digits}";
}

// Retrieve sender's details from database based on user ID
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT full_name, street, city, state, zip, phone FROM users WHERE id = ?");
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
if ($stmt->execute()) {
    $userData = $stmt->get_result()->fetch_assoc();
    if (!$userData) {
        die("No user data found for the provided user ID.");
    }
} else {
    die("Error executing query: " . $stmt->error);
}
$stmt->close();

// Extract user data with fallback values
$sender_name = $userData['full_name'] ?? '';
$sender_street = $userData['street'] ?? '';
$sender_city = $userData['city'] ?? '';
$sender_state = $userData['state'] ?? '';
$sender_zip = $userData['zip'] ?? '';
$sender_phone = $userData['phone'] ?? '';

// Initialize error variables and cost
$errors = [];
$calculated_cost = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_parcel'])) {
    // Input validation
    $sender_name = trim(filter_input(INPUT_POST, 'sender_name', FILTER_SANITIZE_STRING));
    $sender_street = trim(filter_input(INPUT_POST, 'sender_street', FILTER_SANITIZE_STRING));
    $sender_city = trim(filter_input(INPUT_POST, 'sender_city', FILTER_SANITIZE_STRING));
    $sender_state = trim(filter_input(INPUT_POST, 'sender_state', FILTER_SANITIZE_STRING));
    $sender_zip = trim(filter_input(INPUT_POST, 'sender_zip', FILTER_SANITIZE_NUMBER_INT));
    $sender_phone = trim(filter_input(INPUT_POST, 'sender_phone', FILTER_SANITIZE_STRING));

    $receiver_name = trim(filter_input(INPUT_POST, 'receiver_name', FILTER_SANITIZE_STRING));
    $receiver_street = trim(filter_input(INPUT_POST, 'receiver_street', FILTER_SANITIZE_STRING));
    $receiver_city = trim(filter_input(INPUT_POST, 'receiver_city', FILTER_SANITIZE_STRING));
    $receiver_state = trim(filter_input(INPUT_POST, 'receiver_state', FILTER_SANITIZE_STRING));
    $receiver_zip = trim(filter_input(INPUT_POST, 'receiver_zip', FILTER_SANITIZE_NUMBER_INT));
    $receiver_phone = trim(filter_input(INPUT_POST, 'receiver_phone', FILTER_SANITIZE_STRING));

    $parcel_weight = filter_input(INPUT_POST, 'parcel_weight', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $delivery_type = trim(filter_input(INPUT_POST, 'delivery_type', FILTER_SANITIZE_STRING));
    $parcel_status = "Pending";

    // Validate inputs with specific errors
    if (empty($sender_name)) {
        $errors['sender_name'] = "Sender name is required.";
    }
    if (empty($receiver_name)) {
        $errors['receiver_name'] = "Receiver name is required.";
    }
    if (!preg_match("/^\d{10}$/", $sender_phone)) {
        $errors['sender_phone'] = "Sender phone must be a 10-digit number.";
    }
    if (!preg_match("/^\d{6}$/", $sender_zip)) {
        $errors['sender_zip'] = "Sender ZIP code must be a 6-digit number.";
    }
    if (!preg_match("/^\d{10}$/", $receiver_phone)) {
        $errors['receiver_phone'] = "Receiver phone must be a 10-digit number.";
    }
    if (!preg_match("/^\d{6}$/", $receiver_zip)) {
        $errors['receiver_zip'] = "Receiver ZIP code must be a 6-digit number.";
    }
    if ($parcel_weight <= 0) {
        $errors['parcel_weight'] = "Parcel weight must be a positive number.";
    }

    // Calculate cost if no state-related errors
    if (empty($errors)) {
        if (isset($states[$sender_state][$receiver_state])) {
            $base_rate = $states[$sender_state][$receiver_state];
            $weight_cost = $parcel_weight * 10; // Example: ₹10 per kg
            $type_multiplier = ($delivery_type === 'Express') ? 1.5 : 1; // 1.5x for Express delivery

            $calculated_cost = ($base_rate + $weight_cost) * $type_multiplier;
            $calculated_cost = round($calculated_cost, 2); // Round to 2 decimal places
        } else {
            $errors['cost'] = "Unable to calculate cost for the selected origin and destination states.";
        }
    }

    // If there are no errors, proceed to insert data
    if (empty($errors)) {
        // Check for duplicate parcel submissions
        $tracking_number = generateShortTrackingNumber($user_id);

        $stmt = $conn->prepare("SELECT COUNT(*) FROM parcels WHERE user_id = ? AND tracking_number = ?");
        $stmt->bind_param("is", $user_id, $tracking_number);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $errors['general'] = "A parcel with this tracking number has already been submitted.";
        } else {
            // Insert parcel details
            $sql = "INSERT INTO parcels (user_id, tracking_number, sender_name, sender_street, sender_city, sender_state, sender_zip, sender_phone, receiver_name, receiver_street, receiver_city, receiver_state, receiver_zip, receiver_phone, parcel_weight, delivery_type, status, cost) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error preparing insert statement: " . $conn->error);
            }

            $stmt->bind_param(
                "issssssssssssssssd", $user_id, $tracking_number, $sender_name, $sender_street, $sender_city, $sender_state, $sender_zip, $sender_phone, $receiver_name, $receiver_street, $receiver_city, $receiver_state, $receiver_zip, $receiver_phone, $parcel_weight, $delivery_type, $parcel_status, $calculated_cost);

            if ($stmt->execute()) {
                $_SESSION['tracking_number'] = $tracking_number;
                header("Location: parcel_success.php");
                exit();
            } else {
                $errors['general'] = "Error sending parcel. Please try again.";
            }
            $stmt->close();
        }
    }
}
?>

<style>
.parcel-container {
    max-width: 650px;
    margin: 50px auto;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

h2, h3 {
    color: #333;
    margin-top: 0;
}

.form-group {
    margin-bottom: 15px;
    width: 100%;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #333;
}

.form-group input, .form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

.address-group {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.btn {
    background: #2c3e50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
    margin-top: 10px;
}

.btn:hover {
    background: #34495e;
}

.error {
    color: #f00;
    background-color: #ffe6e6;
    border: 1px solid #f00;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}

.flex-container {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.flex-container .form-group {
    flex: 1 1 48%;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>

<div class="parcel-container">
    <h2>Send Parcel</h2>
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <!-- Sender Information -->
        <h3>Sender Information</h3>
        <div class="form-group">
            <label>Sender Name</label>
            <input type="text" name="sender_name" value="<?php echo htmlspecialchars($sender_name); ?>" required>
        </div>
        <div class="flex-container">
            <div class="form-group">
                <label>Street</label>
                <input type="text" name="sender_street" value="<?php echo htmlspecialchars($sender_street); ?>" required>
            </div>
            <div class="form-group">
                <label>City</label>
                <input type="text" name="sender_city" value="<?php echo htmlspecialchars($sender_city); ?>" required>
            </div>
        </div>
        <div class="flex-container">
            <div class="form-group">
                <label>State</label>
                <select name="sender_state" required>
                    <?php
                    foreach ($states as $state => $details) {
                        $selected = ($state === $sender_state) ? 'selected' : '';
                        echo "<option value=\"$state\" $selected>$state</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Zip Code</label>
                <input type="number" name="sender_zip" maxlength="6" value="<?php echo htmlspecialchars($sender_zip); ?>" required oninput="this.value = this.value.slice(0, 6)">
            </div>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="sender_phone" value="<?php echo htmlspecialchars($sender_phone); ?>" required>
        </div>

        <!-- Receiver Information -->
        <h3>Receiver Information</h3>
        <div class="form-group">
            <label>Receiver Name</label>
            <input type="text" name="receiver_name" required>
        </div>
        <div class="flex-container">
            <div class="form-group">
                <label>Street</label>
                <input type="text" name="receiver_street" required>
            </div>
            <div class="form-group">
                <label>City</label>
                <input type="text" name="receiver_city" required>
            </div>
        </div>
        <div class="flex-container">
            <div class="form-group">
                <label>State</label>
                <select name="receiver_state" required>
                    <?php
                    foreach ($states as $state => $details) {
                        echo "<option value=\"$state\">$state</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Zip Code</label>
                <input type="number" name="receiver_zip" maxlength="6" required oninput="this.value = this.value.slice(0, 6)">
            </div>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="receiver_phone" required>
        </div>

        <!-- Parcel Information -->
        <h3>Parcel Information</h3>
        <div class="form-group">
            <label>Parcel Weight (kg)</label>
            <input type="number" name="parcel_weight" step="0.01" min="0" required>
        </div>
        <div class="form-group">
            <label>Delivery Type</label>
            <select name="delivery_type" required>
                <option value="Standard" <?php if (isset($delivery_type) && $delivery_type === 'Standard') echo 'selected'; ?>>Standard</option>
                <option value="Express" <?php if (isset($delivery_type) && $delivery_type === 'Express') echo 'selected'; ?>>Express</option>
            </select>
        </div>

        <!-- Submit Button -->
        <button type="submit" name="send_parcel" class="btn">Send Parcel</button>
    </form>
</div>

<?php require_once 'footer.php'; ?>