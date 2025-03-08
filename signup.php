<?php
require_once 'config.php';
require_once 'header.php';
require_once 'states.php'; // Include the states file

// Initialize form variables
$full_name = $username = $email = $phone = $street = $city = $state = $zip = '';
$error = '';

if (isset($_POST['signup'])) {
    // Retrieve posted data
    $full_name = $conn->real_escape_string(trim($_POST['full_name']));
    $username = $conn->real_escape_string(trim($_POST['username']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $_POST['password']; // Keep the plain password for validation
    $phone = $conn->real_escape_string(trim($_POST['phone']));
    $street = $conn->real_escape_string(trim($_POST['street']));
    $city = $conn->real_escape_string(trim($_POST['city']));
    $state = $conn->real_escape_string(trim($_POST['state']));
    $zip = $conn->real_escape_string(trim($_POST['zip']));

    // Server-side validation
    $errors = []; // Use an array to store error messages
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "Phone number must be 10 digits and numeric.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    if (strlen($street) > 100) {
        $errors[] = "Street cannot exceed 100 characters.";
    }
    if (strlen($zip) != 6 || !preg_match("/^[0-9]+$/", $zip)) {
        $errors[] = "Zip Code must be 6 digits and numeric.";
    }

    // Validate password using a separate function
    $passwordErrors = validatePassword($password);
    if (!empty($passwordErrors)) {
        $errors = array_merge($errors, $passwordErrors);
    }

    // Check if there are no errors
    if (empty($errors)) {
        // Check if username or email already exists
        $sql = "SELECT id FROM users WHERE email = ? OR username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            // If all validations pass, hash the password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert new user
            $insert = "INSERT INTO users (full_name, username, email, password, phone, street, city, state, zip) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert);
            $stmt->bind_param("sssssssss", $full_name, $username, $email, $hashed_password, $phone, $street, $city, $state, $zip);
            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Error during registration. Please try again.";
            }
        } else {
            $error = "Username or Email already exists.";
        }
    } else {
        // Number the error messages
        $error = '';
        foreach ($errors as $index => $message) {
            $error .= ($index + 1) . ". " . $message . "<br>";
        }
    }
}

// Function to validate password
function validatePassword($password) {
    $errors = [];
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    if (!preg_match("/[A-Z]/", $password)) {
        $errors[] = "Password must include at least one uppercase letter.";
    }
    if (!preg_match("/[a-z]/", $password)) {
        $errors[] = "Password must include at least one lowercase letter.";
    }
    if (!preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must include at least one digit.";
    }
    if (!preg_match("/[\W_]/", $password)) {
        $errors[] = "Password must include at least one special character.";
    }
    return $errors;
}
?>

<style>
.signup-container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
.form-group input,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    box-sizing: border-box;
    font-size: 16px;
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

.error {
    color: #f00;
    background-color: #ffe6e6;
    border: 1px solid #f00;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}

.login-link {
    text-align: center;
    margin-top: 20px;
}
</style>

<div class="signup-container">
    <h2>Sign Up</h2>
    <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" id="password" required>
            <small>Password must be at least 8 characters long, include uppercase letters, lowercase letters, digits, and special characters.</small>
        </div>
        
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Street</label>
            <input type="text" name="street" value="<?php echo htmlspecialchars($street); ?>" maxlength="100" required>
        </div>
        
        <div class="form-group">
            <label>City</label>
            <input type="text" name="city" value="<?php echo htmlspecialchars($city); ?>" maxlength="50" required>
        </div>

        <div class="form-group">
            <label>State</label>
            <select name="state" required>
                <option value="" disabled>Select your state</option>
                <?php foreach ($states as $state_name => $cities): ?>
                    <option value="<?php echo $state_name; ?>" <?php echo ($state_name === $state) ? 'selected' : ''; ?>>
                        <?php echo $state_name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Zip Code</label>
            <input type="text" name="zip" value="<?php echo htmlspecialchars($zip); ?>" maxlength="6" required>
        </div>
        
        <button type="submit" name="signup" class="btn">Sign Up</button>
    </form>
    
    <div class="login-link">
        Already have an account? <a href="login.php">Login here</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
