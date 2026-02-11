<?php
session_start();  
require_once 'api/db.php';
require_once 'api/google_config.php';

if (!isset($_GET['code'])) {
    header("Location: index.html");
    exit();
}

$code = $_GET['code'];


$tokenData = [
    'code' => $code,
    'client_id' => GOOGLE_CLIENT_ID,
    'client_secret' => GOOGLE_CLIENT_SECRET,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'grant_type' => 'authorization_code'
];

$ch = curl_init(GOOGLE_TOKEN_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenData));

$response = curl_exec($ch);
curl_close($ch);

$tokenInfo = json_decode($response, true);

if (!isset($tokenInfo['access_token'])) {
    $_SESSION['error'] = "Failed to authenticate with Google";
    header("Location: index.html");
    exit();
}


$ch = curl_init(GOOGLE_USER_INFO_URL . '?access_token=' . $tokenInfo['access_token']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$userInfo = json_decode($response, true);

if (!isset($userInfo['email'])) {
    $_SESSION['error'] = "Failed to get user information";
    header("Location: index.html");
    exit();
}

$email = $userInfo['email'];
$name = $userInfo['name'] ?? '';
$googleId = $userInfo['id'];

$conn = getDbConnection();

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();


$redirectTo = $_SESSION['redirect_after_login'] ?? 'index.html';
unset($_SESSION['redirect_after_login']);

if ($result->num_rows > 0) {
   
    $user = $result->fetch_assoc();
    
   
    $updateStmt = $conn->prepare("UPDATE users SET google_id = ? WHERE id = ?");
    $updateStmt->bind_param("si", $googleId, $user['id']);
    $updateStmt->execute();
    $updateStmt->close();
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $email;
    $_SESSION['user_name'] = $name;
    
   
    echo "<!DOCTYPE html>
    <html>
    <head><title>Redirecting...</title></head>
    <body>
    <script>
        localStorage.setItem('user', JSON.stringify({
            id: " . $user['id'] . ",
            name: " . json_encode($name) . ",
            email: " . json_encode($email) . "
        }));
        window.location.href = " . json_encode($redirectTo) . ";
    </script>
    </body>
    </html>";
    exit();
} else {
    
    $stmt = $conn->prepare("INSERT INTO users (email, name, google_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $name, $googleId);
    
    if ($stmt->execute()) {
        $userId = $conn->insert_id;
        
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $name;
        
      
        echo "<!DOCTYPE html>
        <html>
        <head><title>Redirecting...</title></head>
        <body>
        <script>
            localStorage.setItem('user', JSON.stringify({
                id: " . $userId . ",
                name: " . json_encode($name) . ",
                email: " . json_encode($email) . "
            }));
            localStorage.setItem('isNewUser', 'true');
            window.location.href = 'questionnaire.html';
        </script>
        </body>
        </html>";
        exit();
    } else {
        $_SESSION['error'] = "Failed to create account";
        header("Location: index.html");
        exit();
    }
}

$stmt->close();
$conn->close();
?>