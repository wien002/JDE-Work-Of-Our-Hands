<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Cache-Control');
header('X-Accel-Buffering: no');

include('db_connection.php');

// Start session and then unlock it to avoid blocking other requests
session_start();
$userID = $_SESSION['user_id'] ?? 1;
session_write_close();

// Disable output buffering for immediate SSE delivery
@ini_set('output_buffering', 'off');
@ini_set('zlib.output_compression', '0');
while (ob_get_level() > 0) { ob_end_flush(); }
ob_implicit_flush(1);
ignore_user_abort(true);

// Get parameters
$lastMessageId = isset($_GET['lastMessageId']) ? (int)$_GET['lastMessageId'] : 0;
$room = $_GET['room'] ?? 'general';

// Helper: send SSE event with optional id
function sendEvent($event, $data, $id = null) {
    if ($id !== null) {
        echo "id: {$id}\n";
    }
    echo "event: {$event}\n";
    echo "data: " . json_encode($data) . "\n\n";
    @ob_flush();
    @flush();
}

// Helper: comment keep-alive (prevents proxies from buffering)
function sendKeepAliveComment() {
    echo ": keep-alive\n\n";
    @ob_flush();
    @flush();
}

// Helper: fetch online users (last activity within 60s)
function fetchOnlineUsers($conn) {
    $users = [];
    $sql = "SELECT u.userID, u.firstName, u.lastName, u.userName, ut.userTypeName
            FROM tbl_user u
            LEFT JOIN tbl_usertype ut ON u.userTypeID = ut.userTypeID
            WHERE u.lastLogin >= (NOW() - INTERVAL 60 SECOND)
            ORDER BY u.firstName, u.lastName";
    if ($result = $conn->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $users[] = [
                'userID' => (int)$row['userID'],
                'name' => trim(($row['firstName'] ?? '') . ' ' . ($row['lastName'] ?? '')),
                'username' => $row['userName'] ?? '',
                'role' => $row['userTypeName'] ?? 'Customer'
            ];
        }
        $result->close();
    }
    return $users;
}

// Send initial connection message and online users list
sendEvent('connected', [
    'message' => 'Connected to JDE Works Chat',
    'timestamp' => date('Y-m-d H:i:s')
]);
sendEvent('online_users', [
    'users' => fetchOnlineUsers($conn),
    'timestamp' => date('Y-m-d H:i:s')
]);

// Keep connection alive and check for new messages
$lastPresenceUpdateAt = 0;
$lastKeepAliveAt = 0;
while (true) {
    // Check for new messages
    $stmt = $conn->prepare("
        SELECT 
            c.chatID,
            c.userID,
            c.message,
            c.timeSent,
            u.firstName,
            u.lastName,
            u.userName,
            ut.userTypeName
        FROM tbl_chatlogs c
        LEFT JOIN tbl_user u ON c.userID = u.userID
        LEFT JOIN tbl_usertype ut ON u.userTypeID = ut.userTypeID
        WHERE c.chatID > ?
        ORDER BY c.timeSent ASC
    ");
    
    $stmt->bind_param("i", $lastMessageId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $lastMessageId = (int)$row['chatID'];
        
        sendEvent('new_message', [
            'id' => $row['chatID'],
            'userID' => $row['userID'],
            'message' => $row['message'],
            'timeSent' => $row['timeSent'],
            'sender' => trim(($row['firstName'] ?? '') . ' ' . ($row['lastName'] ?? '')),
            'username' => $row['userName'],
            'role' => $row['userTypeName'] ?? 'Customer',
            'isAdmin' => ($row['userTypeName'] ?? '') === 'Admin'
        ], $lastMessageId);
    }
    
    $stmt->close();

    $now = time();

    // Update presence and broadcast online users every 10s
    if ($now - $lastPresenceUpdateAt >= 10) {
        $lastPresenceUpdateAt = $now;
        if ($userID) {
            if ($updateStmt = $conn->prepare("UPDATE tbl_user SET lastLogin = NOW() WHERE userID = ?")) {
                $updateStmt->bind_param("i", $userID);
                $updateStmt->execute();
                $updateStmt->close();
            }
        }
        sendEvent('online_users', [
            'users' => fetchOnlineUsers($conn),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    // Send lightweight keep-alive comment every 15s to defeat buffering
    if ($now - $lastKeepAliveAt >= 15) {
        $lastKeepAliveAt = $now;
        sendKeepAliveComment();
    }
    
    // Sleep for 1 second before next check
    sleep(1);
    
    // Stop if client disconnected
    if (connection_aborted()) {
        break;
    }
}

$conn->close();
?>
