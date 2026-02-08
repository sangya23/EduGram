<?php
require_once 'api/email_config.php';

echo "<h2>Testing Email Configuration...</h2>";

$test_email = 'pragyee.nightingale@gmail.com'; // ← PUT YOUR ACTUAL EMAIL HERE
$test_otp = '123456';

echo "<p>Attempting to send email to: $test_email</p>";

$result = sendOTPEmail($test_email, $test_otp, 'Test User');

if ($result['success']) {
    echo "<p style='color: green;'>✓ Email sent successfully!</p>";
    echo "<p>Check your inbox (and spam folder) for OTP: $test_otp</p>";
} else {
    echo "<p style='color: red;'>✗ Email failed to send!</p>";
    echo "<p>Error: " . ($result['error'] ?? 'Unknown error') . "</p>";
    
    echo "<h3>Debug Info:</h3>";
    echo "<p>Check your Gmail App Password in api/email_config.php</p>";
    echo "<p>Make sure 2-Step Verification is enabled</p>";
}
?>