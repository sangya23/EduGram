<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';

function sendOTPEmail($to, $otp, $userName = 'User') {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'edugram31@gmail.com'; // ⚠️ REPLACE WITH YOUR GMAIL
        $mail->Password = 'mrzrfswecpdbczfx';     // ⚠️ REPLACE WITH APP PASSWORD
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        $mail->setFrom('edugram31@gmail.com', 'Edugram Support');
        $mail->addAddress($to);
        
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset OTP - Edugram';
        $mail->Body = getOTPEmailTemplate($otp, $userName);
        
        $mail->send();
        return ['success' => true];
    } catch (Exception $e) {
        error_log("Email Error: {$mail->ErrorInfo}");
        return ['success' => false, 'error' => $mail->ErrorInfo];
    }
}

function getOTPEmailTemplate($otp, $userName) {
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f5f7fa; }
            .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 20px; text-align: center; }
            .header h1 { color: white; margin: 0; font-size: 32px; }
            .content { padding: 40px 30px; }
            .otp-box { background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf3 100%); border: 3px dashed #667eea; border-radius: 12px; padding: 30px; text-align: center; margin: 30px 0; }
            .otp-code { font-size: 42px; font-weight: bold; color: #667eea; letter-spacing: 8px; font-family: 'Courier New', monospace; }
            .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 6px; }
            .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #6c757d; font-size: 13px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🔐 Edugram</h1>
                <p style='color: white; margin: 10px 0 0 0;'>Password Reset Request</p>
            </div>
            <div class='content'>
                <h2 style='color: #333;'>Hello, $userName!</h2>
                <p style='color: #666; line-height: 1.6;'>You requested to reset your password. Use the code below:</p>
                <div class='otp-box'>
                    <p style='margin: 0 0 10px 0; color: #666;'>Your OTP Code</p>
                    <div class='otp-code'>$otp</div>
                    <p style='margin: 15px 0 0 0; color: #999; font-size: 13px;'>Expires in 15 minutes</p>
                </div>
                <div class='warning'>
                    <strong>⚠️ Security Notice:</strong> If you didn't request this, ignore this email.
                </div>
            </div>
            <div class='footer'>
                <p>© 2025 Edugram. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";
}
?>