<?php
// Database setup script for JDE Works Chat System
include('db_connection.php');

echo "<h2>JDE Works Chat Database Setup</h2>";

try {
    // Check if tbl_chatlogs exists and has the correct structure
    $result = $conn->query("SHOW TABLES LIKE 'tbl_chatlogs'");
    
    if ($result->num_rows > 0) {
        echo "<p>✅ Table 'tbl_chatlogs' already exists.</p>";
        
        // Check table structure
        $structure = $conn->query("DESCRIBE tbl_chatlogs");
        echo "<h3>Current Table Structure:</h3>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        while ($row = $structure->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Check if we need to add any missing columns
        $columns = [];
        $structure = $conn->query("DESCRIBE tbl_chatlogs");
        while ($row = $structure->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
        
        $requiredColumns = ['chatID', 'userID', 'message', 'timeSent'];
        $missingColumns = array_diff($requiredColumns, $columns);
        
        if (empty($missingColumns)) {
            echo "<p>✅ All required columns are present.</p>";
        } else {
            echo "<p>⚠️ Missing columns: " . implode(', ', $missingColumns) . "</p>";
        }
        
    } else {
        echo "<p>❌ Table 'tbl_chatlogs' does not exist. Creating it now...</p>";
        
        // Create the table
        $createTable = "
        CREATE TABLE tbl_chatlogs (
            chatID INT(11) NOT NULL AUTO_INCREMENT,
            userID INT(11) NOT NULL,
            message TEXT NOT NULL,
            timeSent DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (chatID),
            INDEX userID (userID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
        
        if ($conn->query($createTable)) {
            echo "<p>✅ Table 'tbl_chatlogs' created successfully!</p>";
        } else {
            echo "<p>❌ Error creating table: " . $conn->error . "</p>";
        }
    }
    
    // Check if tbl_user table exists (for user information)
    $result = $conn->query("SHOW TABLES LIKE 'tbl_user'");
    if ($result->num_rows > 0) {
        echo "<p>✅ Table 'tbl_user' exists.</p>";
        
        // Check if lastLogin column exists
        $result = $conn->query("SHOW COLUMNS FROM tbl_user LIKE 'lastLogin'");
        if ($result->num_rows == 0) {
            echo "<p>⚠️ Adding 'lastLogin' column to tbl_user...</p>";
            $alterTable = "ALTER TABLE tbl_user ADD COLUMN lastLogin DATETIME DEFAULT CURRENT_TIMESTAMP";
            if ($conn->query($alterTable)) {
                echo "<p>✅ Column 'lastLogin' added successfully!</p>";
            } else {
                echo "<p>❌ Error adding column: " . $conn->error . "</p>";
            }
        } else {
            echo "<p>✅ Column 'lastLogin' already exists in tbl_user.</p>";
        }
    } else {
        echo "<p>⚠️ Table 'tbl_user' does not exist. Please ensure your user authentication system is set up.</p>";
    }
    
    // Check if tbl_usertype table exists
    $result = $conn->query("SHOW TABLES LIKE 'tbl_usertype'");
    if ($result->num_rows > 0) {
        echo "<p>✅ Table 'tbl_usertype' exists.</p>";
    } else {
        echo "<p>⚠️ Table 'tbl_usertype' does not exist. Creating basic user types...</p>";
        
        $createUserType = "
        CREATE TABLE tbl_usertype (
            userTypeID INT(11) NOT NULL AUTO_INCREMENT,
            userTypeName VARCHAR(50) NOT NULL,
            PRIMARY KEY (userTypeID)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
        
        if ($conn->query($createUserType)) {
            echo "<p>✅ Table 'tbl_usertype' created successfully!</p>";
            
            // Insert basic user types
            $insertTypes = "
            INSERT INTO tbl_usertype (userTypeName) VALUES 
            ('Admin'),
            ('Staff'),
            ('Customer')
            ";
            
            if ($conn->query($insertTypes)) {
                echo "<p>✅ Basic user types inserted successfully!</p>";
            } else {
                echo "<p>❌ Error inserting user types: " . $conn->error . "</p>";
            }
        } else {
            echo "<p>❌ Error creating tbl_usertype: " . $conn->error . "</p>";
        }
    }
    
    // Check if we have any users, if not create default admin
    $result = $conn->query("SELECT COUNT(*) as count FROM tbl_user");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        echo "<p>👤 No users found. Creating default admin user...</p>";
        
        // Create default admin user
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $conn->prepare("
            INSERT INTO tbl_user (userName, password, firstName, lastName, userTypeID, createdBy, updatedBy) 
            VALUES ('admin', ?, 'Admin', 'User', 1, 'system', 'system')
        ");
        $stmt->bind_param("s", $adminPassword);
        
        if ($stmt->execute()) {
            echo "<p>✅ Default admin user created successfully!</p>";
            echo "<p><strong>Username:</strong> admin</p>";
            echo "<p><strong>Password:</strong> admin123</p>";
        } else {
            echo "<p>❌ Error creating admin user: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p>✅ Found " . $row['count'] . " users in the database.</p>";
    }
    
    // Insert some sample messages for testing
    $result = $conn->query("SELECT COUNT(*) as count FROM tbl_chatlogs");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        echo "<p>📝 Inserting sample messages for testing...</p>";
        
        // Get the first user ID to use for sample messages
        $result = $conn->query("SELECT userID FROM tbl_user LIMIT 1");
        if ($row = $result->fetch_assoc()) {
            $sampleUserID = $row['userID'];
            
            $sampleMessages = [
                [$sampleUserID, "Welcome to JDE Works Chat! How can we help you today?"],
                [$sampleUserID, "Our expert tailors are here to assist you with all your uniform needs."],
                [$sampleUserID, "Feel free to ask about custom orders, measurements, or any other questions!"]
            ];
            
            $stmt = $conn->prepare("INSERT INTO tbl_chatlogs (userID, message) VALUES (?, ?)");
            foreach ($sampleMessages as $msg) {
                $stmt->bind_param("is", $msg[0], $msg[1]);
                $stmt->execute();
            }
            $stmt->close();
            
            echo "<p>✅ Sample messages inserted successfully!</p>";
        } else {
            echo "<p>⚠️ No users found to create sample messages.</p>";
        }
    } else {
        echo "<p>✅ Chat table already contains " . $row['count'] . " messages.</p>";
    }
    
    echo "<h3>🎉 Database Setup Complete!</h3>";
    echo "<p>Your chat system is now ready to use. You can:</p>";
    echo "<ul>";
    echo "<li>Access the chat at <a href='chat.php'>chat.php</a></li>";
    echo "<li>Test the API at <a href='chat_api.php?action=get_messages'>chat_api.php?action=get_messages</a></li>";
    echo "<li>View the database structure in phpMyAdmin</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?>
