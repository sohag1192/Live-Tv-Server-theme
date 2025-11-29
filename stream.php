<?php
/**
 * Flussonic Secure Token Generator
 * --------------------------------
 * Generates secure HLS streaming tokens with optional CDNBye P2P acceleration.
 * Author: Md. Sohag Rana
 * Date: 2025-11-28
 *
 * Usage:
 *   token.php?stream=channel_name&type=live
 */

// ============================================================================
// CONFIGURATION SETTINGS
// ============================================================================
$flussonic = 'https://yourserver/';  // Flussonic server address
$key       = 'sohag';                            // Secret key from flussonic.conf
$lifetime  = 300 * 3;                            // Token validity: 3 hours (900 seconds)
$desync    = 300;                                // Time sync tolerance: 5 minutes
$ipaddr    = 'no_check_ip';                      // Disable IP binding for CDN/P2P

// ============================================================================
// FUNCTION: Generate Flussonic Token
// ============================================================================
function generateFlussonicToken($stream, $key, $flussonic, $lifetime, $desync, $ipaddr, $type = '') {
    if (empty($stream)) {
        return ['error' => 'Stream name is required'];
    }

    // Token time window
    $starttime = time() - $desync;
    $endtime   = $starttime + $lifetime;

    // Random salt for uniqueness
    $salt = bin2hex(openssl_random_pseudo_bytes(16));

    // Build hash string
    $hashsrt = $stream . $ipaddr . $starttime . $endtime . $key . $salt;
    $hash    = sha1($hashsrt);

    // Final token format
    $token = $hash . '-' . $salt . '-' . $endtime . '-' . $starttime;

    // Build streaming URL
    $url = $flussonic . '/' . $stream . '/index.m3u8?DVR&token=' . $token . '&autoplay=true';

    // Optional type handling
    if (!empty($type)) {
        $url .= '&type=' . urlencode($type);
    }

    return [
        'stream' => $stream,
        'token'  => $token,
        'url'    => $url,
        'start'  => $starttime,
        'end'    => $endtime,
        'salt'   => $salt
    ];
}

// ============================================================================
// MAIN EXECUTION
// ============================================================================
$stream = $_GET['stream'] ?? '';
$type   = $_GET['type'] ?? '';

$result = generateFlussonicToken($stream, $key, $flussonic, $lifetime, $desync, $ipaddr, $type);

// Output JSON response
header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);
?>