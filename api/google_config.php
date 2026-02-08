<?php
define('GOOGLE_CLIENT_ID', '1090943248108-chlg9pcpifafaeg2q1ipdd9hb93kjleq.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', 'http://localhost/edugram/google-callback.php');
define('GOOGLE_AUTH_URL', 'https://accounts.google.com/o/oauth2/v2/auth');
define('GOOGLE_TOKEN_URL', 'https://oauth2.googleapis.com/token');
define('GOOGLE_USER_INFO_URL', 'https://www.googleapis.com/oauth2/v2/userinfo');

function getGoogleAuthUrl() {
    $params = [
        'client_id' => GOOGLE_CLIENT_ID,
        'redirect_uri' => GOOGLE_REDIRECT_URI,
        'response_type' => 'code',
        'scope' => 'email profile',
        'access_type' => 'online',
        'prompt' => 'select_account'
    ];
    return GOOGLE_AUTH_URL . '?' . http_build_query($params);
}
?>