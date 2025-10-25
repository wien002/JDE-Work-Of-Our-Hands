<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JDE Works Chat - Connect with Our Team</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/chat.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-md bg-white sticky-top shadow-sm">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-brand">
                <a href="index.php"><img src="assets/img/logojd.png" alt="JDE Works of Our Hands Logo" class="logo" loading="lazy"></a>
            </div>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="index.php#Services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="product.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#About">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#Contact">Contact us</a></li>
                    <li class="nav-item"><a class="nav-link active" href="chat.php">Live Chat</a></li>
                </ul>
            </div>

            <div class="navbar-right button-container">
                <div class="user-info">
                    <span class="user-name" id="currentUser">Guest User</span>
                    <div class="online-indicator" id="userStatus"></div>
                </div>
                <a href="login.php" class="login">Login</a>
            </div>
        </div>
    </nav>

    <!-- Chat Container -->
    <div class="chat-container">
        <div class="chat-sidebar">
            <div class="chat-header">
                <h3><i class="fas fa-comments"></i> JDE Works Chat</h3>
                <p>Connect with our tailoring experts</p>
            </div>
            
            <div class="online-users">
                <h4><i class="fas fa-users"></i> Online Now</h4>
                <div class="user-list" id="userList">
                    <!-- Users will be populated here -->
                </div>
            </div>

            <div class="chat-rooms">
                <h4><i class="fas fa-door-open"></i> Chat Rooms</h4>
                <div class="room-list">
                    <div class="room-item active" data-room="general">
                        <i class="fas fa-home"></i>
                        <span>General Support</span>
                        <span class="room-count">0</span>
                    </div>
                    <div class="room-item" data-room="orders">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Order Inquiries</span>
                        <span class="room-count">0</span>
                    </div>
                    <div class="room-item" data-room="custom">
                        <i class="fas fa-cut"></i>
                        <span>Custom Orders</span>
                        <span class="room-count">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="chat-main">
            <div class="chat-messages-header">
                <div class="room-info">
                    <h4 id="currentRoom">General Support</h4>
                    <span class="room-description">Get help with general inquiries</span>
                </div>
                <div class="chat-actions">
                    <button class="btn-action" id="clearChat" title="Clear Chat">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button class="btn-action" id="toggleNotifications" title="Toggle Notifications">
                        <i class="fas fa-bell"></i>
                    </button>
                </div>
            </div>

            <div class="chat-messages" id="chatMessages">
                <div class="welcome-message">
                    <div class="welcome-content">
                        <i class="fas fa-sewing-machine"></i>
                        <h3>Welcome to JDE Works Chat!</h3>
                        <p>Our expert tailors are here to help you with:</p>
                        <ul>
                            <li>Custom uniform orders</li>
                            <li>Size measurements and fittings</li>
                            <li>Order status updates</li>
                            <li>General inquiries</li>
                        </ul>
                        <p>Start typing below to begin your conversation!</p>
                    </div>
                </div>
            </div>

            <div class="typing-indicator" id="typingIndicator">
                <span class="typing-text"></span>
            </div>

            <div class="chat-input-container">
                <div class="input-group">
                    <button class="btn-attachment" id="attachFile" title="Attach File">
                        <i class="fas fa-paperclip"></i>
                    </button>
                    <input type="text" id="messageInput" placeholder="Type your message here..." maxlength="500">
                    <button class="btn-send" id="sendMessage">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                <div class="input-footer">
                    <span class="char-count">0/500</span>
                    <span class="input-hint">Press Enter to send, Shift+Enter for new line</span>
                </div>
            </div>
        </div>
    </div>

    <!-- File Upload Modal -->
    <div class="modal fade" id="fileUploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attach File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="file" id="fileInput" accept="image/*,.pdf,.doc,.docx" multiple>
                    <div class="file-preview" id="filePreview"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="uploadFile">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/chat.js"></script>
</body>
</html>
