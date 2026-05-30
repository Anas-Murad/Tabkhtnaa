<?php
$base = 'http://127.0.0.1:8000/api/v1';
function post(string $path, array $data): void
{
    global $base;
    $ch = curl_init($base . $path . '?lang=ar');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Accept: application/json', 'Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($data),
    ]);
    $body = curl_exec($ch);
    echo $path . ' ' . json_encode($data) . PHP_EOL . $body . PHP_EOL . PHP_EOL;
}

post('/auth/login', ['country_code' => '+966', 'mobile' => '79600135', 'password' => 'NewPass600135']);
post('/auth/login', ['country_code' => '966', 'mobile' => '79600135', 'password' => 'NewPass600135']);
post('/auth/login', ['country_code' => '962', 'mobile' => '79600135', 'password' => 'NewPass600135']);
post('/auth/forget-password', ['country_code' => '00972', 'mobile' => '79600135']);
post('/auth/forget-password', ['country_code' => '970', 'mobile' => '79600135']);
post('/auth/forget-password', ['country_code' => '962', 'mobile' => '79600135']);
