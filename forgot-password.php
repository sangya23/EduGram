<?php
require_once 'api/db.php';
require_once 'api/email_config.php';

$error = '';
$success = '';
$step = 'email'; // email, verify, reset

// Check if OTP verification is being submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {
    $email = $_POST['email'] ?? '';
    $otp = $_POST['otp'] ?? '';
    
    if (empty($email) || empty($otp)) {
        $error = "Please enter the OTP code";
    } else {
        $conn = getDbConnection();
        
        // Verify OTP
        $stmt = $conn->prepare("SELECT id, expires_at, used FROM password_reset_tokens WHERE email = ? AND token = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->bind_param("ss", $email, $otp);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $error = "Invalid OTP code. Please check and try again.";
            $step = 'verify';
        } else {
            $tokenData = $result->fetch_assoc();
            
            if ($tokenData['used'] == 1) {
                $error = "This OTP has already been used.";
                $step = 'verify';
            } elseif (strtotime($tokenData['expires_at']) < time()) {
                $error = "This OTP has expired. Please request a new one.";
                $step = 'email';
            } else {
                // OTP is valid - show password reset form
                $step = 'reset';
                $_SESSION['reset_email'] = $email;
                $_SESSION['reset_token_id'] = $tokenData['id'];
            }
        }
        
        $stmt->close();
        $conn->close();
    }
}

// Check if password reset is being submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $email = $_SESSION['reset_email'] ?? '';
    $tokenId = $_SESSION['reset_token_id'] ?? 0;
    
    if (empty($password) || empty($confirmPassword)) {
        $error = "Please fill in all fields";
        $step = 'reset';
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match";
        $step = 'reset';
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long";
        $step = 'reset';
    } else {
        $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        if (!preg_match($passwordRegex, $password)) {
            $error = "Password must contain uppercase, lowercase, number and special character";
            $step = 'reset';
        } else {
            // Update password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $conn = getDbConnection();
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashedPassword, $email);
            
            if ($stmt->execute()) {
                // Mark token as used
                $stmt = $conn->prepare("UPDATE password_reset_tokens SET used = 1 WHERE id = ?");
                $stmt->bind_param("i", $tokenId);
                $stmt->execute();
                
                // Clear session
                unset($_SESSION['reset_email']);
                unset($_SESSION['reset_token_id']);
                
                $success = "Password reset successfully! Redirecting to login...";
                echo "<script>setTimeout(function(){ window.location.href='index.html'; }, 2000);</script>";
            } else {
                $error = "Something went wrong. Please try again.";
                $step = 'reset';
            }
            
            $stmt->close();
            $conn->close();
        }
    }
}

// Handle email submission and OTP generation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_otp'])) {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        $error = "Please enter your email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else {
        $conn = getDbConnection();
        
        // Check if user exists
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Check if user has a password
            if (empty($user['password'])) {
                $error = "This account was created with Google. Please use 'Continue with Google' to sign in.";
            } else {
                // Generate 6-digit OTP
                $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                
                // Store OTP in database
                $stmt = $conn->prepare("INSERT INTO password_reset_tokens (email, token, expires_at) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $email, $otp, $expiresAt);
                $stmt->execute();
                
                // Send OTP via email
                $emailResult = sendOTPEmail($email, $otp, $user['name']);
                
                if ($emailResult['success']) {
                    $success = "A 6-digit OTP has been sent to your email. Please check your inbox.";
                    $step = 'verify';
                    $_SESSION['reset_email_temp'] = $email;
                } else {
                    $error = "Failed to send OTP. Please try again later.";
                }
            }
        } else {
            // Don't reveal if email doesn't exist for security
            $success = "If an account exists with this email, an OTP has been sent.";
            $step = 'verify';
            $_SESSION['reset_email_temp'] = $email;
        }
        
        $stmt->close();
        $conn->close();
    }
}

$resetEmail = $_SESSION['reset_email_temp'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password | Edugram</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="forgot-password.css">
</head>
<body>

<div class="container">
    <div class="card">
        <h1 class="logo">Edugram</h1>
        
        <?php if ($step === 'email'): ?>
            <h2>Forgot Password?</h2>
            <p class="subtitle">Enter your email address and we'll send you an OTP to reset your password.</p>
            
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="field">
                    <input type="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                <button type="submit" name="send_otp">Send OTP</button>
            </form>
            
        <?php elseif ($step === 'verify'): ?>
            <h2>Verify OTP</h2>
            <p class="subtitle">Enter the 6-digit code sent to <strong><?php echo htmlspecialchars($resetEmail); ?></strong></p>
            
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="post">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($resetEmail); ?>">
                <div class="field">
                    <input type="text" name="otp" placeholder="Enter 6-digit OTP" maxlength="6" pattern="[0-9]{6}" required autofocus style="text-align: center; font-size: 24px; letter-spacing: 5px; font-weight: bold;">
                </div>
                <button type="submit" name="verify_otp">Verify OTP</button>
            </form>
            
            <form method="post" style="margin-top: 15px;">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($resetEmail); ?>">
                <button type="submit" name="send_otp" style="background: linear-gradient(135deg, #64748b, #475569);">Resend OTP</button>
            </form>
            
        <?php elseif ($step === 'reset'): ?>
            <h2>Reset Password</h2>
            <p class="subtitle">Enter your new password</p>
            
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="field">
                    <input type="password" name="password" placeholder="New Password (min. 8 characters)" required minlength="8">
                </div>
                <div class="field">
                    <input type="password" name="confirm_password" placeholder="Confirm New Password" required minlength="8">
                </div>
                <button type="submit" name="reset_password">Reset Password</button>
            </form>
        <?php endif; ?>

        <p class="footer-text">
            Remember your password? <a href="index.html">Sign in</a>
        </p>
    </div>
</div>

</body>
</html>