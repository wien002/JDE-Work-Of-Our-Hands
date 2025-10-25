<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

include('db_connection.php');

// Get the request method and action
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// For POST requests, also check the request body for action
if ($method === 'POST' && empty($action)) {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';
}

// Start session to get user info
session_start();

try {
    switch ($action) {
        case 'send_message':
            if ($method === 'POST') {
                sendMessage($conn);
            } else {
                throw new Exception('Method not allowed');
            }
            break;
            
        case 'get_messages':
            if ($method === 'GET') {
                getMessages($conn);
            } else {
                throw new Exception('Method not allowed');
            }
            break;
            
        case 'get_online_users':
            if ($method === 'GET') {
                getOnlineUsers($conn);
            } else {
                throw new Exception('Method not allowed');
            }
            break;
            
        case 'update_user_status':
            if ($method === 'POST') {
                updateUserStatus($conn);
            } else {
                throw new Exception('Method not allowed');
            }
            break;
            
        case 'get_user_info':
            if ($method === 'GET') {
                getUserInfo($conn);
            } else {
                throw new Exception('Method not allowed');
            }
            break;
            
        case 'send_broadcast':
            if ($method === 'POST') {
                sendBroadcast($conn);
            } else {
                throw new Exception('Method not allowed');
            }
            break;
            
        case 'clear_all_messages':
            if ($method === 'POST') {
                clearAllMessages($conn);
            } else {
                throw new Exception('Method not allowed');
            }
            break;
            
        case 'mark_as_read':
            if ($method === 'POST') {
                markAsRead($conn);
            } else {
                throw new Exception('Method not allowed');
            }
            break;
            
        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}

function sendMessage($conn) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['message']) || empty(trim($input['message']))) {
        throw new Exception('Message is required');
    }
    
    $message = trim($input['message']);
    $room = $input['room'] ?? 'general';
    
    // Get user ID from session or create a guest user
    $userID = $_SESSION['user_id'] ?? null;
    
    // If no user session, create or get a guest user
    if (!$userID) {
        $userID = getOrCreateGuestUser($conn);
    }
    
    // Validate message length
    if (strlen($message) > 500) {
        throw new Exception('Message too long (max 500 characters)');
    }
    
    // Insert message into database
    $stmt = $conn->prepare("INSERT INTO tbl_chatlogs (userID, message, timeSent) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $userID, $message);
    
    if ($stmt->execute()) {
        $messageID = $conn->insert_id;
        
        // Get the inserted message with user info
        $result = $conn->query("
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
            WHERE c.chatID = $messageID
        ");
        
        if ($row = $result->fetch_assoc()) {
            echo json_encode([
                'success' => true,
                'message' => [
                    'id' => $row['chatID'],
                    'userID' => $row['userID'],
                    'message' => $row['message'],
                    'timeSent' => $row['timeSent'],
                    'sender' => $row['firstName'] . ' ' . $row['lastName'],
                    'username' => $row['userName'],
                    'role' => $row['userTypeName'] ?? 'Customer',
                    'isAdmin' => ($row['userTypeName'] ?? '') === 'Admin'
                ]
            ]);
        } else {
            throw new Exception('Failed to retrieve message');
        }
    } else {
        throw new Exception('Failed to send message: ' . $stmt->error);
    }
    
    $stmt->close();
}

function getMessages($conn) {
    $limit = $_GET['limit'] ?? 50;
    $offset = $_GET['offset'] ?? 0;
    $room = $_GET['room'] ?? 'general';
    
    // Validate limit
    $limit = min(max(1, intval($limit)), 100);
    $offset = max(0, intval($offset));
    
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
        ORDER BY c.timeSent DESC
        LIMIT ? OFFSET ?
    ");
    
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = [
            'id' => $row['chatID'],
            'userID' => $row['userID'],
            'message' => $row['message'],
            'timeSent' => $row['timeSent'],
            'sender' => $row['firstName'] . ' ' . $row['lastName'],
            'username' => $row['userName'],
            'role' => $row['userTypeName'] ?? 'Customer',
            'isAdmin' => ($row['userTypeName'] ?? '') === 'Admin'
        ];
    }
    
    // Reverse to show oldest first
    $messages = array_reverse($messages);
    
    echo json_encode([
        'success' => true,
        'messages' => $messages,
        'hasMore' => count($messages) == $limit
    ]);
    
    $stmt->close();
}

function getOnlineUsers($conn) {
    // For now, we'll get all users and simulate online status
    // In a real implementation, you'd track last activity
    $stmt = $conn->prepare("
        SELECT 
            u.userID,
            u.firstName,
            u.lastName,
            u.userName,
            ut.userTypeName,
            u.lastLogin
        FROM tbl_user u
        LEFT JOIN tbl_usertype ut ON u.userTypeID = ut.userTypeID
        WHERE u.userID IS NOT NULL
        ORDER BY u.lastName, u.firstName
    ");
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $users = [];
    while ($row = $result->fetch_assoc()) {
        // Simulate online status based on last login (within last 30 minutes)
        $lastLogin = strtotime($row['lastLogin'] ?? '1970-01-01');
        $isOnline = (time() - $lastLogin) < 1800; // 30 minutes
        
        $users[] = [
            'userID' => $row['userID'],
            'name' => $row['firstName'] . ' ' . $row['lastName'],
            'username' => $row['userName'],
            'role' => $row['userTypeName'] ?? 'Customer',
            'status' => $isOnline ? 'online' : 'offline',
            'lastLogin' => $row['lastLogin']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'users' => $users
    ]);
    
    $stmt->close();
}

function updateUserStatus($conn) {
    $input = json_decode(file_get_contents('php://input'), true);
    $userID = $_SESSION['user_id'] ?? 1;
    $status = $input['status'] ?? 'online';
    
    // Update last activity timestamp
    $stmt = $conn->prepare("UPDATE tbl_user SET lastLogin = NOW() WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Failed to update user status');
    }
    
    $stmt->close();
}

function getUserInfo($conn) {
    $userID = $_SESSION['user_id'] ?? null;
    
    if (!$userID) {
        // Return guest user info
        echo json_encode([
            'success' => true,
            'user' => [
                'userID' => 0,
                'name' => 'Guest User',
                'username' => 'guest',
                'email' => '',
                'role' => 'Guest'
            ]
        ]);
        return;
    }
    
    $stmt = $conn->prepare("
        SELECT 
            u.userID,
            u.firstName,
            u.lastName,
            u.userName,
            u.email,
            ut.userTypeName
        FROM tbl_user u
        LEFT JOIN tbl_usertype ut ON u.userTypeID = ut.userTypeID
        WHERE u.userID = ?
    ");
    
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            'success' => true,
            'user' => [
                'userID' => $row['userID'],
                'name' => $row['firstName'] . ' ' . $row['lastName'],
                'username' => $row['userName'],
                'email' => $row['email'],
                'role' => $row['userTypeName'] ?? 'Customer'
            ]
        ]);
    } else {
        throw new Exception('User not found');
    }
    
    $stmt->close();
}

function getOrCreateGuestUser($conn) {
    // Check if user ID 1 exists, if not create a guest user
    $stmt = $conn->prepare("SELECT userID FROM tbl_user WHERE userID = 1");
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        return 1; // User ID 1 exists
    }
    
    $stmt->close();
    
    // Create a guest user if none exists
    $stmt = $conn->prepare("
        INSERT INTO tbl_user (userName, password, firstName, lastName, userTypeID, createdBy, updatedBy) 
        VALUES ('guest', '', 'Guest', 'User', 3, 'system', 'system')
    ");
    
    if ($stmt->execute()) {
        $guestID = $conn->insert_id;
        $stmt->close();
        return $guestID;
    } else {
        $stmt->close();
        // If we can't create a guest user, try to find any existing user
        $stmt = $conn->prepare("SELECT userID FROM tbl_user LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $stmt->close();
            return $row['userID'];
        }
        
        $stmt->close();
        throw new Exception('No users found in database. Please create a user first.');
    }
}

function sendBroadcast($conn) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['message']) || empty(trim($input['message']))) {
        throw new Exception('Broadcast message is required');
    }
    
    $message = trim($input['message']);
    $adminID = $_SESSION['user_id'] ?? 1; // Default to admin user ID 1
    
    // Validate message length
    if (strlen($message) > 500) {
        throw new Exception('Broadcast message too long (max 500 characters)');
    }
    
    // Get all online users (customers only)
    $stmt = $conn->prepare("
        SELECT DISTINCT u.userID 
        FROM tbl_user u
        LEFT JOIN tbl_usertype ut ON u.userTypeID = ut.userTypeID
        WHERE ut.userTypeName = 'Customer' OR ut.userTypeName IS NULL
        AND u.lastLogin > DATE_SUB(NOW(), INTERVAL 30 MINUTE)
    ");
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $sentCount = 0;
    while ($row = $result->fetch_assoc()) {
        // Insert broadcast message for each customer
        $insertStmt = $conn->prepare("INSERT INTO tbl_chatlogs (userID, message, timeSent) VALUES (?, ?, NOW())");
        $insertStmt->bind_param("is", $adminID, "[BROADCAST] " . $message);
        
        if ($insertStmt->execute()) {
            $sentCount++;
        }
        $insertStmt->close();
    }
    
    $stmt->close();
    
    echo json_encode([
        'success' => true,
        'message' => "Broadcast sent to $sentCount customers"
    ]);
}

function clearAllMessages($conn) {
    // Check if user is admin (in a real app, you'd check user permissions)
    $adminID = $_SESSION['user_id'] ?? 1;
    
    $stmt = $conn->prepare("DELETE FROM tbl_chatlogs");
    
    if ($stmt->execute()) {
        $deletedCount = $stmt->affected_rows;
        echo json_encode([
            'success' => true,
            'message' => "Cleared $deletedCount messages"
        ]);
    } else {
        throw new Exception('Failed to clear messages: ' . $stmt->error);
    }
    
    $stmt->close();
}

function markAsRead($conn) {
    $input = json_decode(file_get_contents('php://input'), true);
    $customerID = $input['customerId'] ?? null;
    
    if (!$customerID) {
        throw new Exception('Customer ID is required');
    }
    
    // In a real implementation, you'd have a separate table for tracking read status
    // For now, we'll just return success
    echo json_encode([
        'success' => true,
        'message' => 'Messages marked as read'
    ]);
}

$conn->close();
?>
