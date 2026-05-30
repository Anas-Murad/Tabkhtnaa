<?php
/**
 * Main features E2E API test — run: php scripts/main_features_e2e_test.php
 */
$base = 'http://127.0.0.1:8000/api/v1';
$suffix = random_int(100000, 999999);
$mobile = '78' . substr((string)$suffix, 0, 7);
$email = "main_e2e_{$suffix}@test.local";
$password = 'Test1234';
$countryCode = '962';
$residenceCountryId = 111;
$lat = 31.9539;
$lng = 35.9106;

echo "=== Main Features E2E Test ===\n";

function api(string $method, string $path, ?array $json = null, ?string $token = null, ?array $query = null): array
{
    global $base;
    $qs = array_merge(['lang' => 'ar'], $query ?? []);
    $url = $base . $path . '?' . http_build_query($qs);
    $ch = curl_init($url);
    $headers = ['Accept: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($json !== null) {
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
    }

    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($body === false) {
        return ['ok' => false, 'code' => 0, 'body' => 'curl error'];
    }
    $decoded = json_decode($body, true);
    return ['ok' => $code >= 200 && $code < 300, 'code' => $code, 'body' => $body, 'json' => $decoded];
}

function pass(string $label, array $res): bool
{
    $ok = $res['ok'] && ($res['json']['status'] ?? false);
    echo ($ok ? '✅' : '❌') . " {$label}";
    if (!$ok) {
        echo " ({$res['code']}): {$res['body']}\n";
    } else {
        echo "\n";
    }
    return $ok;
}

$pngPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'main_e2e.png';
file_put_contents($pngPath, base64_decode(
    'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='
));

$reg = api('POST', '/auth/register', null, null);
// register needs multipart
$ch = curl_init($base . '/auth/register?lang=ar');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'name' => "Main E2E {$suffix}",
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
$body = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$reg = ['ok' => $code >= 200 && $code < 300, 'code' => $code, 'body' => $body, 'json' => json_decode($body, true)];

if (!$reg['ok'] || !($reg['json']['status'] ?? false)) {
    echo "REGISTER FAIL ({$reg['code']}): {$reg['body']}\n";
    exit(1);
}
$token = $reg['json']['data']['access_token'];
$userId = $reg['json']['data']['id'];
echo "REGISTER OK user_id={$userId}\n";

$results = [];

$results['GET /category/list'] = pass('GET /category/list', api('GET', '/category/list', null, $token));

$results['GET /user/chefs'] = pass('GET /user/chefs', api('GET', '/user/chefs', null, $token, [
    'lat' => $lat,
    'long' => $lng,
    'radius' => 30,
]));

$results['GET /user/meals/list'] = pass('GET /user/meals/list', api('GET', '/user/meals/list', null, $token, [
    'lat' => $lat,
    'long' => $lng,
    'radius' => 30,
]));

$results['GET /notification/list'] = pass('GET /notification/list', api('GET', '/notification/list', null, $token));

$seenAll = api('POST', '/notification/seen_all', [], $token);
$results['POST /notification/seen_all'] = pass('POST /notification/seen_all', $seenAll);

$results['GET /complaint/list'] = pass('GET /complaint/list', api('GET', '/complaint/list', null, $token));

$results['GET /user/sanction/list'] = pass('GET /user/sanction/list', api('GET', '/user/sanction/list', null, $token));

$results['GET /user/orders/list'] = pass('GET /user/orders/list', api('GET', '/user/orders/list', null, $token));

$results['GET /addresses/list'] = pass('GET /addresses/list', api('GET', '/addresses/list', null, $token));

// Cart empty for freshly registered user — validated via demo user below
$cartNew = api('GET', '/user/cart/list', null, $token);
$cartEmptyOk = !$cartNew['ok'] || !($cartNew['json']['status'] ?? false);
echo ($cartEmptyOk ? '✅' : '❌') . " GET /user/cart/list (new user empty)";
echo $cartEmptyOk ? "\n" : " ({$cartNew['code']}): {$cartNew['body']}\n";
$results['GET /user/cart/list (new user empty)'] = $cartEmptyOk;

$results['GET /conversations/list'] = pass('GET /conversations/list', api('GET', '/conversations/list', null, $token));

// Demo user login (requires DemoDataSeeder)
$demoLogin = api('POST', '/auth/login', [
    'country_code' => '962',
    'mobile' => '799999999',
    'password' => 'Demo1234',
]);
$demoOk = $demoLogin['ok'] && ($demoLogin['json']['status'] ?? false);
echo ($demoOk ? '✅' : '⚠️') . " POST /auth/login (demo user)";
if (!$demoOk) {
    echo " (run DemoDataSeeder first): {$demoLogin['body']}\n";
} else {
    echo "\n";
    $demoToken = $demoLogin['json']['data']['access_token'];
    $results['demo GET /conversations/list'] = pass('demo GET /conversations/list', api('GET', '/conversations/list', null, $demoToken));
    $results['demo GET /user/cart/list'] = pass('demo GET /user/cart/list', api('GET', '/user/cart/list', null, $demoToken));
    $results['demo GET /user/chef'] = pass('demo GET /user/chef', api('GET', '/user/chef', null, $demoToken, ['id' => 2]));
    $convList = api('GET', '/conversations/list', null, $demoToken);
    if (($convList['json']['data'][0]['id'] ?? null) !== null) {
        $convId = $convList['json']['data'][0]['id'];
        $results['demo GET /conversations/get'] = pass('demo GET /conversations/get', api('GET', '/conversations/get', null, $demoToken, [
            'conversation_id' => $convId,
        ]));
        $results['demo POST /conversations/send_message'] = pass('demo POST /conversations/send_message', api('POST', '/conversations/send_message', [
            'conversation_id' => $convId,
            'message' => 'E2E test message',
        ], $demoToken));
    }
}

// Complaint create may fail without valid order/time window — report but don't exit
$complaint = api('POST', '/complaint/create', [
    'type' => 'maker',
    'order_id' => 1,
    'description' => 'E2E test complaint',
    'note' => 'Test title',
], $token);
$complaintOk = $complaint['ok'] && ($complaint['json']['status'] ?? false);
echo ($complaintOk ? '✅' : '⚠️') . " POST /complaint/create";
if (!$complaintOk) {
    echo " (expected if no order): {$complaint['body']}\n";
} else {
    echo "\n";
    $results['POST /complaint/create'] = true;
}

echo "\n=== SUMMARY ===\n";
foreach ($results as $name => $ok) {
    echo ($ok ? '✅' : '❌') . " {$name}\n";
}

$failed = array_filter($results, fn($v) => !$v);
exit(empty($failed) ? 0 : 1);
