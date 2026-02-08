<!-- TEST FILE: test_profile.html -->
<!-- Open this file in your browser to test what get_profile.php returns -->
<!DOCTYPE html>
<html>
<head>
    <title>Test Profile API</title>
</head>
<body>
    <h1>Profile API Test</h1>
    <button onclick="testProfile()">Test Get Profile</button>
    <pre id="result"></pre>

    <script>
        function testProfile() {
            const user = JSON.parse(localStorage.getItem('user'));
            
            if (!user) {
                document.getElementById('result').textContent = 'ERROR: No user in localStorage. Please login first.';
                return;
            }
            
            console.log('Testing with user_id:', user.id);
            
            fetch('api/get_profile.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: user.id })
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(text => {
                console.log('Raw response:', text);
                
                try {
                    const data = JSON.parse(text);
                    document.getElementById('result').textContent = JSON.stringify(data, null, 2);
                    
                    console.log('===== PARSED RESPONSE =====');
                    console.log('Success:', data.success);
                    console.log('Questionnaire Completed:', data.profile?.questionnaire_completed);
                    console.log('Education Level:', data.profile?.education_level);
                    console.log('Full profile:', data.profile);
                } catch (e) {
                    document.getElementById('result').textContent = 'ERROR PARSING JSON:\n' + text;
                    console.error('Parse error:', e);
                }
            })
            .catch(error => {
                document.getElementById('result').textContent = 'NETWORK ERROR:\n' + error;
                console.error('Fetch error:', error);
            });
        }
    </script>
</body>
</html>