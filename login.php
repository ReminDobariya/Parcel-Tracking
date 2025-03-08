<?php
session_start(); // Start the session to store user data

require_once 'config.php';
require_once 'header.php';

if (isset($_POST['login'])) {
    $loginInput = $conn->real_escape_string($_POST['loginInput']);
    $password = $_POST['password'];

    if ($loginInput === 'admin' && $password === 'admin') {
        $_SESSION['is_admin'] = true;  // Set the admin session
        header("Location: update_status.php"); // Redirect directly to update status
        exit();
    } else {
        // Regular user login flow
        $sql = "SELECT id, password FROM users WHERE email = ? OR username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $loginInput, $loginInput);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                unset($_SESSION['is_admin']); // Ensure admin session is cleared
                header("Location: index.php"); // Redirect after login success
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Invalid username or email.";
        }
        $stmt->close();
    }
}

?>

<style>
/* Styling for login page */
.login-container {
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

.error {
    color: red;
    margin-bottom: 10px;
}

.signup-link {
    text-align: center;
    margin-top: 20px;
}
</style>

<div class="login-container">
    <h2>Login</h2>
    <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Username or Email</label>
            <input type="text" name="loginInput" required>
        </div>
        
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        
        <button type="submit" name="login" class="btn">Login</button>
    </form>
    
    <div class="signup-link">
        Don't have an account? <a href="signup.php">Sign up here</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
