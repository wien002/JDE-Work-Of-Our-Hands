<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - JDE Works of Our Hands</title>
    <meta name="description" content="Browse and order custom uniforms and pre-made garments from JDE Works of Our Hands. Quality tailoring services in Caloocan City.">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/product.css">
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
                    <li class="nav-item"><a class="nav-link active" href="products.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#About">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#Contact">Contact us</a></li>
                    <li class="nav-item"><a class="nav-link" href="chat.php">Live Chat</a></li>
                </ul>
            </div>

            <div class="navbar-right button-container">
                <a href="signin.html" class="signup">Sign Up</a>
                <a href="login.html" class="login">Login</a>
            </div>
        </div>
    </nav>

    <!-- Back Button -->
    <div class="back-button-container">
        <button class="back-button" onclick="window.history.back()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m15 18-6-6 6-6"/>
            </svg>
        </button>
    </div>

    <!-- Products Page -->
    <main class="products-page">
        <div class="container">
            <!-- Page Header -->
            <div class="products-header">
                <h1 class="products-title">Products</h1>
            </div>

            <!-- Pre-made Section -->
            <section class="products-section">
                <h2 class="section-label">Pre-made Uniforms</h2>
                <div class="products-grid">
                    <div class="product-item">
                        <div class="product-image">
                            <svg class="product-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                                <line x1="7" y1="7" x2="7.01" y2="7"/>
                            </svg>
                        </div>
                        <h3 class="product-name">Men's Polo Uniform</h3>
                        <button class="order-button" onclick="orderProduct('mens-polo-uniform')">Order</button>
                    </div>
                    
                    <div class="product-item">
                        <div class="product-image">
                            <svg class="product-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                                <line x1="7" y1="7" x2="7.01" y2="7"/>
                            </svg>
                        </div>
                        <h3 class="product-name">Men's Trouser</h3>
                        <button class="order-button" onclick="orderProduct('mens-trouser')">Order</button>
                    </div>
                    
                    <div class="product-item">
                        <div class="product-image">
                            <svg class="product-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                                <line x1="7" y1="7" x2="7.01" y2="7"/>
                            </svg>
                        </div>
                        <h3 class="product-name">Women's Blouse Uniform</h3>
                        <button class="order-button" onclick="orderProduct('womens-blouse-uniform')">Order</button>
                    </div>
                    
                    <div class="product-item">
                        <div class="product-image">
                            <svg class="product-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                                <line x1="7" y1="7" x2="7.01" y2="7"/>
                            </svg>
                        </div>
                        <h3 class="product-name">Women's Skirt</h3>
                        <button class="order-button" onclick="orderProduct('womens-skirt')">Order</button>
                    </div>
                    
                    <div class="product-item">
                        <div class="product-image">
                            <svg class="product-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                                <line x1="7" y1="7" x2="7.01" y2="7"/>
                            </svg>
                        </div>
                        <h3 class="product-name">Women's Pants</h3>
                        <button class="order-button" onclick="orderProduct('womens-pants')">Order</button>
                    </div>
                    
                    <div class="product-item">
                        <div class="product-image">
                            <svg class="product-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                                <line x1="7" y1="7" x2="7.01" y2="7"/>
                            </svg>
                        </div>
                        <h3 class="product-name">Men's Uniform Set</h3>
                        <button class="order-button" onclick="orderProduct('mens-uniform')">Order</button>
                    </div>
                </div>
            </section>

            <!-- Custom-made Section -->
            <section class="products-section">
                <h2 class="section-label">Custom-made Uniforms</h2>
                <div class="products-grid">
                    <div class="product-item">
                        <div class="product-image">
                            <svg class="product-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                                <line x1="7" y1="7" x2="7.01" y2="7"/>
                            </svg>
                        </div>
                        <h3 class="product-name">Custom Men's Polo</h3>
                        <button class="order-button" onclick="orderProduct('custom-mens-polo')">Order</button>
                    </div>
                    
                    <div class="product-item">
                        <div class="product-image">
                            <svg class="product-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                                <line x1="7" y1="7" x2="7.01" y2="7"/>
                            </svg>
                        </div>
                        <h3 class="product-name">Custom Men's Trouser</h3>
                        <button class="order-button" onclick="orderProduct('custom-mens-trouser')">Order</button>
                    </div>
                    
                    <div class="product-item">
                        <div class="product-image">
                            <svg class="product-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                                <line x1="7" y1="7" x2="7.01" y2="7"/>
                            </svg>
                        </div>
                        <h3 class="product-name">Custom Women's Blouse</h3>
                        <button class="order-button" onclick="orderProduct('custom-womens-blouse')">Order</button>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>JDE Works Of Our Hands</p>
    </footer>

    <!-- Order Modal -->
    <div class="modal-overlay" id="orderModal">
        <div class="modal-content">
            <h3>Order Confirmation</h3>
            <p id="modalProductName">You're about to order: <strong>Product Name</strong></p>
            <div class="modal-buttons">
                <button class="btn-primary" onclick="proceedToOrder()">Proceed</button>
                <button class="btn-secondary" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/product.js"></script>
</body>
</html>