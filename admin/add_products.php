<?php
session_start();
require_once __DIR__ . '/db.php';

// Protect page (admins only)
require_once __DIR__ . '/../includes/config.php';
if (empty($_SESSION['logged_in'])) {
    header("Location: index.php");
    exit();
}
if (!app_is_admin()) {
    header("Location: ../mood_result.php");
    exit();
}

// Handle form submission
if ($_POST) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $mood = $_POST['mood'];
    $price = $_POST['price'];
    $category = $_POST['category']; // new category field

    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDir = 'uploads/products/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . $_FILES['image']['name'];
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            $image = $fileName;
        }
    }

    // Insert into database including category
    $stmt = $pdo->prepare("INSERT INTO products (title, description, mood, category, image, price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $mood, $category, $image, $price]);

    $success = "Product added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - MoodBuddy Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/fontawesome.min.css">
    <style>
        :root {
      --primary-pink: #ff1493;
      --primary-sky: #00bfff;
      --metallic-silver: #c0c0c0;
      --metallic-gold: #ffd700;
      --neon-glow: #00ffff;
    }
    :root {
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

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Orbitron', monospace;
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      color: #1a202c;
      overflow-x: hidden;
      position: relative;
    }

    /* Added floating background objects like result page */
    .floating-objects {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 1;
    }

    .floating-object {
      position: absolute;
      opacity: 0.1;
      animation: float 20s infinite linear;
    }

    .floating-object:nth-child(1) {
      top: 10%;
      left: 10%;
      width: 80px;
      height: 80px;
      background: linear-gradient(45deg, var(--primary-pink), var(--primary-sky));
      border-radius: 50%;
      animation-delay: 0s;
    }

    .floating-object:nth-child(2) {
      top: 20%;
      right: 15%;
      width: 60px;
      height: 60px;
      background: linear-gradient(45deg, var(--primary-sky), var(--metallic-gold));
      transform: rotate(45deg);
      animation-delay: -5s;
    }

    .floating-object:nth-child(3) {
      bottom: 30%;
      left: 20%;
      width: 100px;
      height: 100px;
      background: linear-gradient(45deg, var(--metallic-silver), var(--primary-pink));
      border-radius: 30%;
      animation-delay: -10s;
    }

    .floating-object:nth-child(4) {
      bottom: 10%;
      right: 10%;
      width: 70px;
      height: 70px;
      background: linear-gradient(45deg, var(--neon-glow), var(--primary-sky));
      clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
      animation-delay: -15s;
    }

    @keyframes float {
      0% { transform: translateY(0px) rotate(0deg); }
      25% { transform: translateY(-20px) rotate(90deg); }
      50% { transform: translateY(0px) rotate(180deg); }
      75% { transform: translateY(-15px) rotate(270deg); }
      100% { transform: translateY(0px) rotate(360deg); }
    }

    /* Futuristic header with metallic gradient */
    .admin-header {
      background: White;
      backdrop-filter: blur(20px);
      border-bottom: 2px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      position: fixed;
      width: 100%;
      z-index: 1000;
      border-radius: 0 0 20px 20px;
      padding: 0 20px;
    }

    .admin-header .container-fluid {
      position: relative;
      z-index: 2;
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


    /* Futuristic sidebar with glass morphism */
    .sidebar {
  position: fixed;
  left: 0;
  top: 0;
  height: 100vh;
  width: 280px;
  background: linear-gradient(135deg, rgba(255, 20, 147, 0.1), rgba(0, 191, 255, 0.1));
  backdrop-filter: blur(20px);
  border-right: 2px solid rgba(255, 255, 255, 0.2);
  color: #1a202c;
  padding-top: 90px;
  transition: all 0.3s ease;
  z-index: 999;
  
  /* 👇 Add these */
  overflow-y: auto;
  scrollbar-width: thin; /* Firefox */
  scrollbar-color: var(--primary-pink) transparent;
}

.sidebar::-webkit-scrollbar {
  width: 6px;
}
.sidebar::-webkit-scrollbar-thumb {
  background: var(--primary-pink);
  border-radius: 10px;
}
.sidebar::-webkit-scrollbar-track {
  background: transparent;
}

    .profile-info {
      background: linear-gradient(135deg, rgba(255, 20, 147, 0.2), rgba(0, 191, 255, 0.2));
      border-bottom: 2px solid rgba(255, 255, 255, 0.3) !important;
      border-radius: 15px;
      margin: 0 15px 20px;
    }

    .profile-info img {
      border: 3px solid rgba(255, 255, 255, 0.5);
      box-shadow: 0 0 20px rgba(255, 20, 147, 0.3);
    }

    .nav-link {
      color: #1a202c;
      padding: 15px 25px;
      margin: 8px 15px;
      border-radius: 15px;
      transition: all 0.3s ease;
      font-weight: 500;
      position: relative;
      overflow: hidden;
    }

    .nav-link::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }

    .nav-link:hover::before {
      left: 100%;
    }

    .nav-link:hover,
    .nav-link.active {
      color: #1a202c;
      background: linear-gradient(135deg, rgba(255, 20, 147, 0.2), rgba(0, 191, 255, 0.2));
      border: 2px solid rgba(255, 255, 255, 0.3);
      box-shadow: 0 8px 25px rgba(255, 20, 147, 0.2);
      transform: translateX(5px);
    }

    .nav-link i {
      margin-right: 12px;
      font-size: 1.2rem;
      background: linear-gradient(45deg, var(--primary-pink), var(--primary-sky));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* Main content with enhanced spacing */
    .main-content {
      margin-left: 280px;
      padding: 110px 30px 30px;
      transition: all 0.3s ease;
      position: relative;
      z-index: 2;
    }

    /* Futuristic cards with glass morphism */
    .card {
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(20px);
      border: 2px solid rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, var(--primary-pink), var(--primary-sky), var(--metallic-gold));
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 25px 50px rgba(255, 20, 147, 0.15);
    }

    .card-header {
      background: linear-gradient(135deg, rgba(255, 20, 147, 0.1), rgba(0, 191, 255, 0.1));
      border-bottom: 2px solid rgba(255, 255, 255, 0.2);
      border-radius: 20px 20px 0 0 !important;
      padding: 20px 25px;
    }

    .card-title {
      background: linear-gradient(45deg, var(--primary-pink), var(--primary-sky));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 700;
      font-size: 1.3rem;
    }

    /* Enhanced stats cards with metallic icons */
    .card-body .bg-primary {
      background: linear-gradient(135deg, var(--primary-pink), var(--primary-sky)) !important;
    }

    .card-body .bg-success {
      background: linear-gradient(135deg, #10b981, #34d399) !important;
    }

    .card-body .bg-info {
      background: linear-gradient(135deg, var(--primary-sky), var(--neon-glow)) !important;
    }

    .card-body .bg-warning {
      background: linear-gradient(135deg, var(--metallic-gold), #fbbf24) !important;
    }

    .card-body h3 {
      background: linear-gradient(45deg, var(--primary-pink), var(--primary-sky));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 800;
    }

    /* Futuristic buttons */
    .btn-admin {
      background: linear-gradient(135deg, var(--primary-pink), var(--primary-sky));
      border: none;
      padding: 15px 30px;
      border-radius: 12px;
      color: white;
      font-weight: 600;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .btn-admin::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }

    .btn-admin:hover::before {
      left: 100%;
    }

    .btn-admin:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(255, 20, 147, 0.3);
      color: white;
    }

    .btn-outline-primary {
      border: 2px solid;
      border-image: linear-gradient(45deg, var(--primary-pink), var(--primary-sky)) 1;
      background: transparent;
      color: var(--primary-pink);
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
      background: linear-gradient(135deg, var(--primary-pink), var(--primary-sky));
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(255, 20, 147, 0.2);
    }

    /* Enhanced table styling */
    .table {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 15px;
      overflow: hidden;
    }

    .table th {
      background: linear-gradient(135deg, rgba(255, 20, 147, 0.1), rgba(0, 191, 255, 0.1));
      border: none;
      font-weight: 700;
      color: #1a202c;
      padding: 15px;
    }

    .table td {
      border-color: rgba(255, 255, 255, 0.2);
      padding: 15px;
      vertical-align: middle;
    }

    .table tbody tr:hover {
      background: rgba(255, 20, 147, 0.05);
    }

    /* Enhanced badges */
    .badge {
      padding: 8px 15px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 0.85rem;
    }

    .bg-primary {
      background: linear-gradient(135deg, var(--primary-pink), var(--primary-sky)) !important;
    }

    .bg-success {
      background: linear-gradient(135deg, #10b981, #34d399) !important;
    }

    .bg-info {
      background: linear-gradient(135deg, var(--primary-sky), var(--neon-glow)) !important;
    }

    .bg-warning {
      background: linear-gradient(135deg, var(--metallic-gold), #fbbf24) !important;
    }

    /* Responsive design */
    @media (max-width: 992px) {
      .sidebar {
        left: -280px;
      }
      .main-content {
        margin-left: 0;
      }
    }

    /* Enhanced form controls */
    .form-control {
      background: rgba(255, 255, 255, 0.1);
      border: 2px solid rgba(255, 255, 255, 0.2);
      border-radius: 12px;
      padding: 15px 20px;
      color: #1a202c;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      background: rgba(255, 255, 255, 0.2);
      border-color: var(--primary-pink);
      box-shadow: 0 0 20px rgba(255, 20, 147, 0.2);
    }

    /* Breadcrumb styling */
    .breadcrumb {
      background: transparent;
      padding: 0;
    }

    .breadcrumb-item {
      color: #64748b;
    }

    .breadcrumb-item.active {
      color: var(--primary-pink);
      font-weight: 600;
    }

    h4 {
      background: linear-gradient(45deg, var(--primary-pink), var(--primary-sky));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 800;
      font-size: 2rem;
    }
    /* Enhanced form control border visibility */
    .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 20, 147, 0.3);
            border-radius: 12px;
            padding: 15px 20px;
            color: #1a202c;
            transition: all 0.3s ease;
            font-family: 'Orbitron', monospace;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.3);
            border-color: var(--primary-pink);
            box-shadow: 0 0 20px rgba(255, 20, 147, 0.2);
            color: #1a202c;
        }

        .form-control::placeholder {
            color: rgba(26, 32, 44, 0.6);
        }

        .form-label {
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 8px;
        }

        .btn-primary1 {
            background: linear-gradient(135deg, var(--primary-pink), var(--primary-sky));
            border: none;
            padding: 15px 30px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary1::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary1:hover::before {
            left: 100%;
        }

        .btn-primary1:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 20, 147, 0.3);
        }

        .btn-secondary {
            border: 2px solid;
            border-image: linear-gradient(45deg, var(--primary-pink), var(--primary-sky)) 1;
            background: transparent;
            color: var(--primary-pink);
            font-weight: 600;
            padding: 15px 30px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, var(--primary-pink), var(--primary-sky));
            color: white;
            transform: translateY(-2px);
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(52, 211, 153, 0.1));
            border: 2px solid rgba(16, 185, 129, 0.3);
            border-radius: 12px;
            color: #065f46;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            gap: 1vw;
        }

        .breadcrumb-item {
            color: #64748b;
        }

        .breadcrumb-item.active {
            color: var(--primary-pink);
            font-weight: 600;
        }

        h4 {
            background: linear-gradient(45deg, var(--primary-pink), var(--primary-sky));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 2rem;
        }

        /* Responsive design */
        @media (max-width: 992px) {
            .sidebar {
                left: -280px;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Floating background objects -->
    <div class="floating-objects">
        <div class="floating-object"></div>
        <div class="floating-object"></div>
        <div class="floating-object"></div>
        <div class="floating-object"></div>
    </div>

    <!--  ADMIN HEADER -->
    <header class="admin-header py-3">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col-md-6">
          <div class="logo">MoodBuddy</div>
        </div>
        <div class="col-md-6 text-end d-flex justify-content-end align-items-center">
        <div class="me-3">
        <a href="../mood_result.php" class="btn btn-success btn-sm"><i class="fas fa-user-shield me-1"></i>Go to Website</a>
        </div>
          <div class="me-3 text-muted">
            <a href="profile.php" class="btn btn-primary btn-sm"><i class="fas fa-user-shield me-1"></i>My Profile</a>
          </div>
          <a href="logout.php" class="btn btn-sm btn-danger">
            <i class="fas fa-sign-out-alt me-1"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </header>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="profile-info text-center p-3 border-bottom">
            <img 
                src="uploads/<?php echo htmlspecialchars($_SESSION['profile_photo'] ?? 'default.jpg'); ?>" 
                class="rounded-circle mb-2" 
                width="80" 
                height="80" 
                alt="Profile"
            >
            <h6 class="mb-0"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></h6>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="view_all.php"><i class="fas fa-eye"></i> View All Content</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="add_products.php"><i class="fas fa-plus-circle"></i> Add Product</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="add_songs.php"><i class="fas fa-music"></i> Add Song</a></li>
            <li class="nav-item"><a class="nav-link" href="add_quote.php"><i class="fas fa-quote-right"></i> Add Quote</a></li>
            <li class="nav-item"><a class="nav-link" href="add_meme.php"><i class="fas fa-grin-squint-tears"></i> Add Meme</a></li>
            <li class="nav-item"><a class="nav-link" href="add_game.php"><i class="fas fa-gamepad"></i> Add Game</a></li>
            <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-chart-bar"></i> Analytics</a></li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="mb-0">Add New Product</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li>/</li>
                            <li class="breadcrumb-item active" aria-current="page">Add Product</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title"><i class="fas fa-plus-circle me-3"></i>Add New Product</h2>
                        </div>
                        <div class="card-body p-4">
                            <?php if (isset($success)): ?>
                                <div class="alert alert-success"><?= $success ?></div>
                            <?php endif; ?>

                            <form method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Product Title</label>
                                        <input type="text" class="form-control" name="title" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Price ($)</label>
                                        <input type="number" step="0.01" class="form-control" name="price" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mood Category</label>
                                        <select class="form-control" name="mood" required>
                                            <option value="">Select Mood</option>
                                            <option value="happy">Happy</option>
                                            <option value="sad">Sad</option>
                                            <option value="anxious">Anxious</option>
                                            <option value="angry">Angry</option>
                                            <option value="excited">Excited</option>
                                            <option value="calm">Calm</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Product Category</label>
                                        <select class="form-control" name="category" required>
                                            <option value="">Select Category</option>
                                            <option value="Relaxation">Relaxation</option>
                                            <option value="Self-Care">Self-Care</option>
                                            <option value="Energy">Energy</option>
                                            <option value="Mindfulness">Mindfulness</option>
                                            <option value="Decor">Decor</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Product Image</label>
                                        <input type="file" class="form-control" name="image" accept="image/*" required>
                                    </div>
                                    <div class="col-md-6 mb-3"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="4" required></textarea>
                                </div>

                                <div class="d-flex gap-3 mt-4">
                                    <button type="submit" class="btn btn-primary1">
                                        <i class="fas fa-save me-2"></i>Add Product
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
