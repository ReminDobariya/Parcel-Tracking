<?php 
session_start(); 
require_once 'config.php'; 
require_once 'header.php'; 
require_once 'states.php';
?>

<style>
/* Styling */
.pricing-container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    font-size: 28px;
    margin-bottom: 15px;
    color: #2c3e50;
    text-align: center;
}

p.description {
    text-align: center;
    font-size: 16px;
    margin-bottom: 30px;
    color: #666;
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

.form-group input, .form-group select {
    width: 100%;
    max-width: 100%;
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

.result-box {
    margin-top: 20px;
    padding: 15px;
    background-color: #f1f1f1;
    border-radius: 5px;
    font-size: 16px;
    color: #333;
    border: 1px solid #ddd;
}

.result-box p {
    margin: 0 0 10px;
}

.small-note {
    display: inline-block;
    font-size: 12px;
    color: #666;
    margin-top: 5px;
}

.call-to-action {
    margin-top: 30px;
    text-align: center;
}

.call-to-action a {
    font-size: 16px;
    color: #007bff;
    text-decoration: none;
}

.call-to-action a:hover {
    text-decoration: underline;
}
</style>

<div class="pricing-container">
    <h2>Check Courier Charges</h2>
    <p class="description">Enter parcel weight, choose origin and destination states, and delivery type to calculate the cost for your parcel delivery.</p>

    <form method="POST" action="">
        <div class="form-group">
            <label for="origin_state">Origin State</label>
            <select name="origin_state" id="origin_state" required>
                <option value="Gujarat">Gujarat</option>
                <option value="Maharashtra">Maharashtra</option>
                <option value="Karnataka">Karnataka</option>
                <option value="Uttar Pradesh">Uttar Pradesh</option>
                <option value="West Bengal">West Bengal</option>
                <option value="Rajasthan">Rajasthan</option>
                <option value="Telangana">Telangana</option>
                <option value="Delhi">Delhi</option>
                <option value="Haryana">Haryana</option>
                <option value="Tamil Nadu">Tamil Nadu</option>
            </select>
        </div>

        <div class="form-group">
            <label for="destination_state">Destination State</label>
            <select name="destination_state" id="destination_state" required>
                <option value="Gujarat">Gujarat</option>
                <option value="Maharashtra">Maharashtra</option>
                <option value="Karnataka">Karnataka</option>
                <option value="Uttar Pradesh">Uttar Pradesh</option>
                <option value="West Bengal">West Bengal</option>
                <option value="Rajasthan">Rajasthan</option>
                <option value="Telangana">Telangana</option>
                <option value="Delhi">Delhi</option>
                <option value="Haryana">Haryana</option>
                <option value="Tamil Nadu">Tamil Nadu</option>
            </select>
        </div>

        <div class="form-group">
            <label for="parcel_weight">Parcel Weight (in kg)</label>
            <input type="number" name="parcel_weight" id="parcel_weight" step="0.10" min="0" required>
        </div>

        <div class="form-group">
            <label for="delivery_type">Delivery Type</label>
            <select name="delivery_type" id="delivery_type" required>
                <option value="Standard">Standard</option>
                <option value="Express">Express</option>
            </select>
        </div>

        <button type="submit" name="calculate_price" class="btn">Calculate Price</button>
    </form>

    <?php 
    if (isset($_POST['calculate_price'])): 
        $parcel_weight = floatval($_POST['parcel_weight']);
        $delivery_type = $_POST['delivery_type'];
        $origin_state = $_POST['origin_state'];
        $destination_state = $_POST['destination_state'];

        // Check if the states are valid and fetch the base price
        if (isset($states[$origin_state][$destination_state])) {
            $base_price = $states[$origin_state][$destination_state];
            $total_price = $base_price + ($parcel_weight * 10); // 10 per kg additional charge

            $total_price_with_tax = $total_price * 1.18; // Including 18% tax
            $formatted_price = number_format($total_price_with_tax, 2);
        } else {
            $formatted_price = "Price not available for the selected states.";
        }
    ?>

    <div class="result-box">
        <p><strong>Origin State:</strong> <?php echo htmlspecialchars($origin_state); ?></p>
        <p><strong>Destination State:</strong> <?php echo htmlspecialchars($destination_state); ?></p>
        <p><strong>Parcel Weight:</strong> <?php echo htmlspecialchars($parcel_weight); ?> kg</p>
        <p><strong>Delivery Type:</strong> <?php echo htmlspecialchars($delivery_type); ?></p>
        <p><strong>Total Price:</strong> ₹<?php echo htmlspecialchars($formatted_price); ?>
            <span class="small-note"> (Includes all taxes and charges)</span>
        </p>    
    </div>

    <div class="call-to-action">
        <p>Ready to book your parcel? <a href="send_parcel.php">Send Parcel Now</a></p>
    </div>

    <?php endif; ?>
</div>
<?php require_once 'footer.php'; ?>
