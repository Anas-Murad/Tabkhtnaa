# Auth E2E API test script
$ErrorActionPreference = "Stop"
$base = "http://127.0.0.1:8000/api/v1"
$suffix = Get-Random -Minimum 100000 -Maximum 999999
$mobile = "79$suffix"
$email = "e2e_$suffix@test.local"
$password = "Test1234"
$countryCode = "962"
$residenceCountryId = 111  # Jordan

Write-Host "=== Auth E2E Test ===" 
Write-Host "Mobile: $mobile | Email: $email"

# Create minimal PNG for profile_image
$pngBytes = [byte[]](0x89,0x50,0x4E,0x47,0x0D,0x0A,0x1A,0x0A,0x00,0x00,0x00,0x0D,0x49,0x48,0x44,0x52,0x00,0x00,0x00,0x01,0x00,0x00,0x00,0x01,0x08,0x06,0x00,0x00,0x00,0x1F,0x15,0xC4,0x89,0x00,0x00,0x00,0x0A,0x49,0x44,0x41,0x54,0x78,0x9C,0x63,0x00,0x01,0x00,0x00,0x05,0x00,0x01,0x0D,0x0A,0x2D,0xB4,0x00,0x00,0x00,0x00,0x49,0x45,0x4E,0x44,0xAE,0x42,0x60,0x82)
$pngPath = [System.IO.Path]::GetTempFileName() + ".png"
[System.IO.File]::WriteAllBytes($pngPath, $pngBytes)

function Invoke-Api {
    param($Method, $Path, $Body = $null, $Token = $null, $Form = $null)
    $headers = @{ Accept = "application/json"; lang = "ar" }
    if ($Token) { $headers.Authorization = "Bearer $Token" }
    $uri = "$base$Path"
    try {
        if ($Form) {
            $r = Invoke-RestMethod -Uri $uri -Method $Method -Headers $headers -Form $Form
        } elseif ($Body) {
            $r = Invoke-RestMethod -Uri $uri -Method $Method -Headers $headers -Body ($Body | ConvertTo-Json) -ContentType "application/json"
        } else {
            $r = Invoke-RestMethod -Uri $uri -Method $Method -Headers $headers
        }
        return @{ ok = $true; data = $r }
    } catch {
        $text = $_.Exception.Message
        $resp = $_.Exception.Response
        if ($resp) {
            $reader = New-Object System.IO.StreamReader($resp.GetResponseStream())
            $text = $reader.ReadToEnd()
            return @{ ok = $false; status = [int]$resp.StatusCode; body = $text }
        }
        return @{ ok = $false; status = 0; body = $text }
    }
}

# 1. Register
$form = @{
    name = "E2E User $suffix"
    email = $email
    mobile = $mobile
    country_code = $countryCode
    residence_country_id = $residenceCountryId
    dob = "1995-06-15"
    gender = "male"
    type = "client"
    password = $password
    password_confirmation = $password
    profile_image = Get-Item $pngPath
}
$reg = Invoke-Api -Method POST -Path "/auth/register" -Form $form
if (-not $reg.ok) {
    Write-Host "REGISTER FAIL: $($reg.body)"
    exit 1
}
$user = $reg.data.data
$token = $user.access_token
$userId = $user.id
Write-Host "REGISTER OK: user_id=$userId token_len=$($token.Length)"

# 2. Send SMS
$sms = Invoke-Api -Method POST -Path "/auth/send-sms" -Token $token
if (-not $sms.ok) {
    Write-Host "SEND-SMS FAIL: $($sms.body)"
} else {
    $smsCode = $sms.data.data.sms_verify
    Write-Host "SEND-SMS OK: sms_verify=$smsCode"
}

# 3. Mobile verified
$mv = Invoke-Api -Method POST -Path "/auth/mobile-verified" -Token $token -Body @{ user_id = $userId }
if (-not $mv.ok) {
    Write-Host "MOBILE-VERIFIED FAIL: $($mv.body)"
} else {
    Write-Host "MOBILE-VERIFIED OK: mobile_verified=$($mv.data.data.mobile_verified)"
}

# 4. Login
$login = Invoke-Api -Method POST -Path "/auth/login" -Body @{
    country_code = $countryCode
    mobile = $mobile
    password = $password
}
if (-not $login.ok) {
    Write-Host "LOGIN FAIL: $($login.body)"
} else {
    Write-Host "LOGIN OK: token_len=$($login.data.data.access_token.Length)"
}

# 5. Forget password
$fp = Invoke-Api -Method POST -Path "/auth/forget-password" -Body @{
    country_code = $countryCode
    mobile = $mobile
}
if (-not $fp.ok) {
    Write-Host "FORGET-PASSWORD FAIL: $($fp.body)"
    exit 1
}
$resetToken = $fp.data.data.reset_password_token
$resetUserId = $fp.data.data.user_id
Write-Host "FORGET-PASSWORD OK: reset_token_len=$($resetToken.Length)"

# 6. Reset password
$newPassword = "NewPass$suffix"
$rp = Invoke-Api -Method POST -Path "/auth/reset-password" -Body @{
    user_id = $resetUserId
    reset_password_token = $resetToken
    new_password = $newPassword
    new_password_confirmation = $newPassword
}
if (-not $rp.ok) {
    Write-Host "RESET-PASSWORD FAIL: $($rp.body)"
} else {
    Write-Host "RESET-PASSWORD OK"
}

# 7. Login with new password
$login2 = Invoke-Api -Method POST -Path "/auth/login" -Body @{
    country_code = $countryCode
    mobile = $mobile
    password = $newPassword
}
if (-not $login2.ok) {
    Write-Host "LOGIN-NEW-PASS FAIL: $($login2.body)"
} else {
    Write-Host "LOGIN-NEW-PASS OK"
}

Write-Host "=== DONE ==="
Write-Host "CREDENTIALS: country_code=$countryCode mobile=$mobile email=$email password=$newPassword"
