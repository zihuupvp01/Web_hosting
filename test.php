<?php
// SECRET TOKEN LINK HIDDEN 🔒
$private_token = "https://vstech.serv00.net/chxhost/uploads/Chxff/token_ind.json";

if(isset($_GET['uid']) && isset($_GET['server_name'])) {

    $uid = htmlspecialchars($_GET['uid']);
    $region = htmlspecialchars($_GET['server_name']);

    $url = "https://like-api-of-chx.vercel.app/like?uid=$uid&server_name=$region&token=$private_token";

    // FAKE USER AGENT
    $userAgent = "Mozilla/5.0 (Linux; Android 14; FreeFire/1.101.1 Private System) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Mobile Safari/537.36";

    // FAKE IP Address Fixed
    $fakeIP = rand(103, 197) . "." . rand(20, 80) . "." . rand(1, 254) . "." . rand(1, 254);

    $headers = [
        "User-Agent: $userAgent",
        "X-Forwarded-For: $fakeIP",
        "Referer: https://official.garena.com",
        "X-System-Access: Garena Private Server",
        "X-Chx-Hack: Private Xray 🔥"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $res = json_decode($response, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            if (isset($res['likes_added']) && $res['likes_added'] > 0) {
                echo "<h1 style='color: green; font-family: Courier New; text-align: center;'>✅ SYSTEM ACCESS GRANTED</h1>";
                echo "<div style='text-align: center;'>
                        <p><b>Before Likes:</b> {$res['before_likes']} ❤️</p>
                        <p><b>After Likes:</b> {$res['after_likes']} ❤️</p>
                        <p><b>Likes Added:</b> {$res['likes_added']} ✅</p>
                        <p><b>Failed Requests:</b> {$res['failed_requests']} ❌</p>
                        <p><b>Successful Requests:</b> {$res['successful_requests']} 🔥</p>
                      </div>";

                // Progress Bar
                echo "<div style='width: 100%; background: #ccc; height: 30px; border-radius: 10px;'>
                        <div style='width: 100%; background: #0f0; height: 100%; border-radius: 10px; text-align: center; line-height: 30px; color: #fff;'>100% Completed</div>
                      </div>";
                
            } else {
                echo "<h1 style='color: red; font-family: Courier New;'>❌ SYSTEM ACCESS DENIED</h1>";
            }
        } else {
            echo "<h1 style='color: orange; font-family: Courier New;'>⚠️ Invalid JSON Response</h1>";
        }
    } else {
        echo "<h1 style='color: orange; font-family: Courier New;'>⚠️ API Server Not Responding</h1>";
    }
} else {
    echo "<h1>⚠️ 404 PRIVATE SYSTEM</h1>";
    echo "<p>Unauthorized Access 🔒</p>";
}
?>