<?php
require_once __DIR__ . '/db.php';

requireLogin();
$user = getCurrentUser();

// If password already exists, redirect to login
if (!empty($user['password'])) {
    header("Location: index.html");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            // Hash password and update database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $conn = getDbConnection();
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashedPassword, $user['id']);
            
            if ($stmt->execute()) {
                $success = "Password set successfully! Redirecting to login...";
                echo "<script>setTimeout(function(){ window.location.href='index.html'; }, 2000);</script>";
            } else {
                $error = "Something went wrong. Please try again.";
            }
            
            $stmt->close();
            $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set Your Password | Edugram</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="set-password.css">
</head>
<body>

<div class="container">
    <div class="card">
        <h1 class="logo">Edugram</h1>
        <h2>Set Your Password</h2>
        <p class="subtitle">
            You're almost done. Create a password to secure your account.
        </p>

        <p class="debug">Signed in as: <strong><?php echo htmlspecialchars($user['email']); ?></strong></p>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="post" id="setPasswordForm">
            <div class="field">
                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Create password"
                    required
                    minlength="8"
                >
            </div>

            <div class="field">
                <input
                    type="password"
                    name="confirm_password"
                    id="confirm_password"
                    placeholder="Confirm password"
                    required
                    minlength="8"
                >
            </div>

            <button type="submit">Set Password</button>
        </form>

        <p class="footer-text">
            This password will be used for future logins.
        </p>
    </div>
</div>

<script>
document.getElementById('setPasswordForm').addEventListener('submit', function(e) {
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