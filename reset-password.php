<?php
require_once __DIR__ . '/db.php';

$token = $_GET['token'] ?? '';
$error = '';
$success = '';
$validToken = false;

if (empty($token)) {
    header("Location: index.html");
    exit();
}

$conn = getDbConnection();

// Verify token
$stmt = $conn->prepare("SELECT email, expires_at, used FROM password_reset_tokens WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $error = "Invalid or expired reset link";
} else {
    $tokenData = $result->fetch_assoc();
    
    if ($tokenData['used'] == 1) {
        $error = "This reset link has already been used";
    } elseif (strtotime($tokenData['expires_at']) < time()) {
        $error = "This reset link has expired";
    } else {
        $validToken = true;
        $email = $tokenData['email'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($password) || empty($confirmPassword)) {
        $error = "Please fill in all fields";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long";
    } else {
        $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        if (!preg_match($passwordRegex, $password)) {
            $error = "Password must contain uppercase, lowercase, number and special character";
        } else {
            // Update password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashedPassword, $email);
            
            if ($stmt->execute()) {
                // Mark token as used
                $stmt = $conn->prepare("UPDATE password_reset_tokens SET used = 1 WHERE token = ?");
                $stmt->bind_param("s", $token);
                $stmt->execute();
                
                $success = "Password reset successfully! Redirecting to login...";
                echo "<script>setTimeout(function(){ window.location.href='index.html'; }, 2000);</script>";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | Edugram</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="reset-password.css">
</head>
<body>

<div class="container">
    <div class="card">
        <h1 class="logo">Edugram</h1>
        <h2>Reset Password</h2>
        <p class="subtitle">Enter your new password below.</p>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php if (!$validToken): ?>
                <p class="footer-text">
                    <a href="forgot-password.php">Request a new reset link</a>
                </p>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if ($validToken && !$success): ?>
        <form method="post" id="resetForm">
            <div class="field">
                <input type="password" name="password" id="password" placeholder="New Password (min. 8 characters)" required minlength="8">
            </div>

            <div class="field">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm New Password" required minlength="8">
            </div>

            <button type="submit">Reset Password</button>
        </form>
        <?php endif; ?>

        <p class="footer-text">
            Remember your password? <a href="index.html">Sign in</a>
        </p>
    </div>
</div>

<script>
document.getElementById('resetForm')?.addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
    }
});
</script>

</body>
</html>