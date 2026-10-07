<?php include_once __DIR__ . '/includes/header.php'; ?>

<?php
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Access Denied',
            text: 'Please login first to access the shop!',
            confirmButtonText: 'Login Now'
        }).then(() => {
            window.location.href = 'admin/index.php';
        });
    </script>
    ";
    exit();
}


// Fetch categories and moods
$categories = $pdo->query("SELECT DISTINCT category FROM products")->fetchAll(PDO::FETCH_COLUMN);
$moods = $pdo->query("SELECT DISTINCT mood FROM products")->fetchAll(PDO::FETCH_COLUMN);

// Get filters
$selectedCategory = $_GET['category'] ?? '';
$selectedMood = $_GET['mood'] ?? '';
$searchTerm = $_GET['search'] ?? '';
$sortBy = $_GET['sort'] ?? 'title';

// Build query
$sql = "SELECT * FROM products WHERE 1";
$params = [];

if (!empty($searchTerm)) {
    $sql .= " AND title LIKE :search";
    $params[':search'] = "%$searchTerm%";
}
if (!empty($selectedCategory) && $selectedCategory !== 'all') {
    $sql .= " AND category = :category";
    $params[':category'] = $selectedCategory;
}
if (!empty($selectedMood) && $selectedMood !== 'all') {
    $sql .= " AND mood = :mood";
    $params[':mood'] = $selectedMood;
}

// Sorting
switch ($sortBy) {
    case 'price':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price-desc':
        $sql .= " ORDER BY price DESC";
        break;
    case 'name':
    default:
        $sql .= " ORDER BY title ASC";
        break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get cart and wishlist counts
$cartCount = 0;
$wishlistCount = 0;
if (isset($_SESSION['cart'])) {
    $cartCount = count($_SESSION['cart']);
}
if (isset($_SESSION['wishlist'])) {
    $wishlistCount = count($_SESSION['wishlist']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - MoodBuddy</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* Pink and sky blue theme with metallic shining effects */
            --primary-color: #06b6d4;
            --secondary-color: #ec4899;
            --primary-gradient: linear-gradient(135deg, #06b6d4 0%, #ec4899 100%);
            --metallic-shine: linear-gradient(45deg, #06b6d4, #0891b2, #ec4899, #db2777);
            --light-bg: #ffffff;
            --card-bg: rgba(255, 255, 255, 0.95);
            --text-primary: #1f2937;
            --text-secondary: #4b5563;
            --border-light: rgba(6, 182, 212, 0.2);
            --glow-pink: rgba(236, 72, 153, 0.4);
            --glow-blue: rgba(6, 182, 212, 0.4);
        }

        body {
            font-family: 'Exo 2', sans-serif;
            background: var(--light-bg);
            color: var(--text-primary);
            overflow-x: hidden;
            position: relative;
            padding: 0;
        }

        /* Enhanced header with metallic glass effect */
        .futuristic-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 2px solid transparent;
            border-image: var(--primary-gradient) 1;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(6, 182, 212, 0.1);
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0.5rem;
            height: 80px;
        }

        /* Enhanced logo with metallic shining effect */
        .logo {
            font-family: 'Orbitron', monospace;
            font-size: 1.8rem;
            font-weight: 900;
            background: var(--metallic-shine);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transition: all 0.3s ease;
            cursor: pointer;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .logo:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 0 15px var(--glow-blue));
        }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        /* Enhanced section titles with metallic underline */
        .section-title {
            font-family: 'Orbitron', monospace;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 3rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--metallic-shine);
            background-size: 300% 300%;
            border-radius: 2px;
            box-shadow: 0 0 15px var(--glow-blue);
            animation: shimmer 2s ease infinite;
        }

       

        /* Filters */
        .filters-container {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
            box-shadow: 0 8px 30px rgba(6, 182, 212, 0.1);
            position: relative;
        }

        .filters-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 20px;
            padding: 2px;
            background: var(--primary-gradient);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
            z-index: -1;
        }

        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .filter-group select, .filter-group input {
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-light);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--text-primary);
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .filter-group select:focus, .filter-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 15px var(--glow-blue);
            background: white;
        }

        .apply-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
            align-self: flex-start;
        }

        .apply-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px var(--glow-pink);
            filter: brightness(1.1);
        }

        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        /* Enhanced cards with metallic borders and glow effects */
        .product-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(6, 182, 212, 0.1);
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 20px;
            padding: 2px;
            background: var(--primary-gradient);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
            z-index: -1;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px var(--glow-pink);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        .product-title {
            font-family: 'Orbitron', monospace;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .product-category, .product-mood {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .product-price {
            font-family: 'Orbitron', monospace;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin: 1rem 0;
            text-shadow: 0 0 10px var(--glow-pink);
        }

        .product-actions {
            display: flex;
            gap: 0.75rem;
        }

        /* Enhanced buttons with metallic gradient and glow */
        .add-cart {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
            flex: 1;
            justify-content: center;
        }

        .add-wishlist {
            background: transparent;
            color: var(--secondary-color);
            border: 2px solid var(--secondary-color);
            padding: 0.75rem;
            border-radius: 50%;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
            width: 50px;
            height: 50px;
        }

        .add-cart::before, .add-wishlist::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }

        .add-cart:hover, .add-wishlist:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px var(--glow-blue);
        }

        .add-cart:hover::before, .add-wishlist:hover::before {
            left: 100%;
        }

        .add-wishlist:hover {
            box-shadow: 0 10px 25px var(--glow-pink);
        }

        .no-products {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem;
            background: var(--card-bg);
            border-radius: 20px;
            font-size: 1.2rem;
            color: var(--text-secondary);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }
            
            .filters {
                grid-template-columns: 1fr;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
            }
            
            .header-container {
                padding: 1rem;
            }
        }

        /* Scroll animations */
        .fade-in {
            opacity: 1;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body>

    <!-- Main Content -->
    <main class="main-content">
        <h2 class="section-title">Our Products</h2>
        
        <!-- Cart and Wishlist Buttons -->
        <div class="action-buttons">
            <a href="cart.php" class="action-btn cart-btn">
                <i class="fas fa-shopping-cart"></i>
                View Cart
                <span class="badge"><?= $cartCount ?></span>
            </a>
            <a href="wishlist.php" class="action-btn wishlist-btn">
                <i class="fas fa-heart"></i>
                Wishlist
                <span class="badge"><?= $wishlistCount ?></span>
            </a>
        </div>

        <!-- Filters -->
        <div class="filters-container fade-in">
            <form method="GET">
                <div class="filters">
                    <div class="filter-group">
                        <label for="search">Search Products</label>
                        <input type="text" id="search" name="search" placeholder="Search..." value="<?= htmlspecialchars($searchTerm) ?>">
                    </div>
                    
                    <div class="filter-group">
                        <label for="category">Category</label>
                        <select id="category" name="category">
                            <option value="all">All Categories</option>
                            <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat ?>" <?= $selectedCategory === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="mood">Mood</label>
                        <select id="mood" name="mood">
                            <option value="all">All Moods</option>
                            <?php foreach($moods as $m): ?>
                            <option value="<?= $m ?>" <?= $selectedMood === $m ? 'selected' : '' ?>><?= $m ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="sort">Sort By</label>
                        <select id="sort" name="sort">
                            <option value="name" <?= $sortBy==='name'?'selected':'' ?>>Name A-Z</option>
                            <option value="price" <?= $sortBy==='price'?'selected':'' ?>>Price Low-High</option>
                            <option value="price-desc" <?= $sortBy==='price-desc'?'selected':'' ?>>Price High-Low</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="apply-btn">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="products-grid">
            <?php if($products): ?>
                <?php foreach($products as $p): ?>
                    <div class="product-card fade-in">
                        <img src="admin/uploads/products/<?= $p['image'] ?>" alt="<?= $p['title'] ?>" class="product-image">
                        <h3 class="product-title"><?= $p['title'] ?></h3>
                        <div class="product-category"><?= $p['category'] ?></div>
                        <div class="product-mood"><?= $p['mood'] ?> Mood</div>
                        <div class="product-price">$<?= number_format($p['price'],2) ?></div>
                        
                        <div class="product-actions">
                            <button class="add-cart" onclick="addToCart(<?= $p['id'] ?>)">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                            <button class="add-wishlist" onclick="addToWishlist(<?= $p['id'] ?>)">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-products">
                    <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; color: var(--secondary-color);"></i>
                    <h3>No products found</h3>
                    <p>Try adjusting your filters or search term</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <?php include_once __DIR__ . '/includes/footer.php'; ?>

    <script>
        // Add to Cart function
        function addToCart(id){
            fetch('actions/add_to_cart.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'product_id=' + id
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    // Update cart count
                    const cartBadge = document.querySelector('.cart-btn .badge');
                    cartBadge.textContent = parseInt(cartBadge.textContent) + 1;
                    
                    Swal.fire({
                        title: 'Added to Cart!',
                        text: 'Product has been added to your cart',
                        icon: 'success',
                        confirmButtonColor: '#06b6d4'
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to add product to cart',
                        icon: 'error',
                        confirmButtonColor: '#06b6d4'
                    });
                }
            });
        }

        // Add to Wishlist function
        function addToWishlist(id){
            fetch('add_to_wishlist.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'product_id=' + id
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    // Update wishlist count
                    const wishlistBadge = document.querySelector('.wishlist-btn .badge');
                    wishlistBadge.textContent = parseInt(wishlistBadge.textContent) + 1;
                    
                    Swal.fire({
                        title: 'Added to Wishlist!',
                        text: 'Product has been added to your wishlist',
                        icon: 'success',
                        confirmButtonColor: '#ec4899'
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to add product to wishlist',
                        icon: 'error',
                        confirmButtonColor: '#ec4899'
                    });
                }
            });
        }

        // Scroll Animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>
