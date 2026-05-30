<?php
/**
 * Auth E2E API test — run: php scripts/auth_e2e_test.php
 */
$base = 'http://127.0.0.1:8000/api/v1';
$suffix = random_int(100000, 999999);
$mobile = '79' . substr((string)$suffix, 0, 7);
$email = "e2e_{$suffix}@test.local";
$password = 'Test1234';
$countryCode = '962';
$residenceCountryId = 111;

echo "=== Auth E2E Test ===\n";
echo "Mobile: {$mobile} | Email: {$email}\n";

function api(string $method, string $path, ?array $json = null, ?string $token = null, ?array $multipart = null): array
{
    global $base;
    $url = $base . $path . '?lang=ar';
    $ch = curl_init($url);
    $headers = ['Accept: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($multipart !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $multipart);
    } elseif ($json !== null) {
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
    }

    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($body === false) {
        return ['ok' => false, 'code' => 0, 'body' => $err];
    }
    $decoded = json_decode($body, true);
    return ['ok' => $code >= 200 && $code < 300, 'code' => $code, 'body' => $body, 'json' => $decoded];
}

// Minimal PNG
$pngPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'profile_test.png';
file_put_contents($pngPath, base64_decode(
    'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='
));

$reg = api('POST', '/auth/register', null, null, [
    'name' => "E2E User {$suffix}",
    'email' => $email,
    'mobile' => $mobile,
    'country_code' => $countryCode,
    'residence_country_id' => $residenceCountryId,
    'dob' => '1995-06-15',
    'gender' => 'male',
    'type' => 'client',
    'password' => $password,
    'password_confirmation' => $password,
    'profile_image' => new CURLFile($pngPath, 'image/png', 'profile.png'),
]);

if (!$reg['ok'] || !($reg['json']['status'] ?? false)) {
    echo "REGISTER FAIL ({$reg['code']}): {$reg['body']}\n";
    exit(1);
}
$user = $reg['json']['data'];
$token = $user['access_token'] ?? '';
$userId = $user['id'];
echo "REGISTER OK: user_id={$userId}\n";

$sms = api('POST', '/auth/send-sms', null, $token);
if (!$sms['ok'] || !($sms['json']['status'] ?? false)) {
    echo "SEND-SMS FAIL ({$sms['code']}): {$sms['body']}\n";
} else {
    echo "SEND-SMS OK: sms_verify={$sms['json']['data']['sms_verify']}\n";
}

$mv = api('POST', '/auth/mobile-verified', ['user_id' => $userId], $token);
if (!$mv['ok'] || !($mv['json']['status'] ?? false)) {
    echo "MOBILE-VERIFIED FAIL ({$mv['code']}): {$mv['body']}\n";
} else {
    echo "MOBILE-VERIFIED OK\n";
}

$login = api('POST', '/auth/login', [
    'country_code' => $countryCode,
    'mobile' => $mobile,
    'password' => $password,
]);
if (!$login['ok'] || !($login['json']['status'] ?? false)) {
    echo "LOGIN FAIL ({$login['code']}): {$login['body']}\n";
} else {
    echo "LOGIN OK\n";
}

$fp = api('POST', '/auth/forget-password', [
    'country_code' => $countryCode,
    'mobile' => $mobile,
]);
if (!$fp['ok'] || !($fp['json']['status'] ?? false)) {
    echo "FORGET-PASSWORD FAIL ({$fp['code']}): {$fp['body']}\n";
    exit(1);
}
$resetToken = $fp['json']['data']['reset_password_token'];
$resetUserId = $fp['json']['data']['user_id'];
echo "FORGET-PASSWORD OK\n";

$newPassword = "NewPass{$suffix}";
$rp = api('POST', '/auth/reset-password', [
    'user_id' => $resetUserId,
    'reset_password_token' => $resetToken,
    'new_password' => $newPassword,
    'new_password_confirmation' => $newPassword,
]);
if (!$rp['ok'] || !($rp['json']['status'] ?? false)) {
    echo "RESET-PASSWORD FAIL ({$rp['code']}): {$rp['body']}\n";
} else {
    echo "RESET-PASSWORD OK\n";
}

$login2 = api('POST', '/auth/login', [
    'country_code' => $countryCode,
    'mobile' => $mobile,
    'password' => $newPassword,
]);
if (!$login2['ok'] || !($login2['json']['status'] ?? false)) {
    echo "LOGIN-NEW-PASS FAIL ({$login2['code']}): {$login2['body']}\n";
} else {
    echo "LOGIN-NEW-PASS OK\n";
    $token = $login2['json']['data']['access_token'];
}

// --- Profile tests ---
echo "\n=== Profile Tests ===\n";

$up = api('POST', '/auth/update-profile', [
    'name' => "E2E Updated {$suffix}",
    'email' => $email,
    'mobile' => $mobile,
    'country_code' => $countryCode,
    'residence_country_id' => $residenceCountryId,
    'dob' => '1990-03-20',
    'gender' => 'female',
    'def_lang' => 'en',
], $token);
if (!$up['ok'] || !($up['json']['status'] ?? false)) {
    echo "UPDATE-PROFILE FAIL ({$up['code']}): {$up['body']}\n";
} else {
    $updated = $up['json']['data'];
    echo "UPDATE-PROFILE OK: name={$updated['name']} gender={$updated['gender']} def_lang={$updated['def_lang']}\n";
}

$os = api('POST', '/auth/online-status', ['online_status' => 'unavailable'], $token);
if (!$os['ok'] || !($os['json']['status'] ?? false)) {
    echo "ONLINE-STATUS FAIL ({$os['code']}): {$os['body']}\n";
} else {
    echo "ONLINE-STATUS OK: {$os['json']['data']['online_status']}\n";
}

$terms = api('GET', '/auth/term-and-condition', null, $token);
if (!$terms['ok'] || !($terms['json']['status'] ?? false)) {
    echo "TERMS FAIL ({$terms['code']}): {$terms['body']}\n";
} else {
    echo "TERMS OK\n";
}

$langs = api('GET', '/languages');
if (!$langs['ok'] || !($langs['json']['status'] ?? false)) {
    echo "LANGUAGES FAIL ({$langs['code']}): {$langs['body']}\n";
} else {
    $codes = array_column($langs['json']['data'], 'code');
    echo "LANGUAGES OK: " . implode(',', $codes) . "\n";
}

$cpPass = "ProfilePass{$suffix}";
$cp = api('POST', '/auth/change-password', [
    'current_password' => $newPassword,
    'password' => $cpPass,
    'password_confirmation' => $cpPass,
], $token);
if (!$cp['ok'] || !($cp['json']['status'] ?? false)) {
    echo "CHANGE-PASSWORD FAIL ({$cp['code']}): {$cp['body']}\n";
} else {
    echo "CHANGE-PASSWORD OK\n";
    $newPassword = $cpPass;
}

echo "=== DONE ===\n";
echo "CREDENTIALS: country_code={$countryCode} mobile={$mobile} email={$email} password={$newPassword}\n";
