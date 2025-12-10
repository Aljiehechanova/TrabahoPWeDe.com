<?php
// This is the callback URL Veriff will call after verification ends

require_once '../config/db_connection.php'; // Optional: for DB logging

// 1. Get raw POST input
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

// 2. Log for debugging
file_put_contents('veriff_callback_log.txt', date('Y-m-d H:i:s') . "\n" . $rawInput . "\n\n", FILE_APPEND);

// 3. Basic structure check
if (!isset($data['verification']['id']) || !isset($data['verification']['status'])) {
    http_response_code(400);
    echo "Invalid payload.";
    exit;
}

$sessionId = $data['verification']['id'];
$status = $data['verification']['status']; // 'approved', 'declined', 'expired', etc.

// 4. Handle the status
if ($status === 'approved') {
    file_put_contents("verified_sessions.txt", date('Y-m-d H:i:s') . " | $sessionId\n", FILE_APPEND);
    http_response_code(200);
    echo "✅ Verification approved.";
} else {
    file_put_contents("declined_sessions.txt", date('Y-m-d H:i:s') . " | $sessionId => $status\n", FILE_APPEND);
    http_response_code(200);
    echo "⛔ Verification not approved: $status";
}
