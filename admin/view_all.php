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

// Handle delete requests
if (isset($_GET['delete'])) {
    $type = $_GET['type'];
    $id = $_GET['id'];
    
    switch($type) {
        case 'product':
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            break;
        case 'song':
            $stmt = $pdo->prepare("DELETE FROM songs WHERE id = ?");
            break;
        case 'quote':
            $stmt = $pdo->prepare("DELETE FROM quotes WHERE id = ?");
            break;
        case 'meme':
            $stmt = $pdo->prepare("DELETE FROM memes WHERE id = ?");
            break;
        case 'game':
            $stmt = $pdo->prepare("DELETE FROM games WHERE id = ?");
            break;
        default:
            header("Location: view_all.php?error=" . urlencode("Invalid content type."));
            exit();
    }
    $success = $error = '';
    if ($stmt->execute([(int)$id])) {
        $success = ucfirst($type) . " deleted successfully!";
    } else {
        $error = "Failed to delete " . $type . ". Please try again.";
    }
    
    // Refresh the page to show updated content
    header("Location: view_all.php?" . ($success ? "success=" . urlencode($success) : "error=" . urlencode($error)));
    exit();
}

// Fetch all content from database
$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$songs = $pdo->query("SELECT * FROM songs ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$quotes = $pdo->query("SELECT * FROM quotes ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$memes = $pdo->query("SELECT * FROM memes ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$games = $pdo->query("SELECT * FROM games ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
// games table uses name/url columns - map them to the keys this page displays
foreach ($games as &$g) {
    $g['title'] = $g['title'] ?? $g['name'] ?? 'Game';
    $g['link']  = $g['link'] ?? $g['url'] ?? '#';
}
unset($g);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>View All Content - MoodBuddy Admin</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/css/fontawesome.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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

    /* Floating background objects */
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

    /* Header styling */
    .admin-header {
      background: white;
      backdrop-filter: blur(20px);
      border-bottom: 2px solid rgba(255, 20, 147, 0.2);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      position: fixed;
      width: 100%;
      z-index: 1000;
      border-radius: 0 0 20px 20px;
      padding: 0 20px;
    }
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

    /* Sidebar styling */
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
      overflow-y: auto;
  scrollbar-width: thin; /* Firefox */
  scrollbar-color: var(--primary-pink) transparent;

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
      z-index: -1;
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

    /* Main content */
    .main-content {
      margin-left: 280px;
      padding: 110px 30px 30px;
      transition: all 0.3s ease;
      position: relative;
      z-index: 2;
    }

    /* Section headers */
    .section-header {
      background: linear-gradient(135deg, rgba(255, 20, 147, 0.1), rgba(0, 191, 255, 0.1));
      backdrop-filter: blur(20px);
      border: 2px solid rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      padding: 25px;
      margin-bottom: 30px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .section-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--primary-pink), var(--primary-sky), var(--metallic-gold));
    }

    .section-header h2 {
      background: linear-gradient(45deg, var(--primary-pink), var(--primary-sky));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 800;
      font-size: 2.5rem;
      margin: 0;
    }

    /* Content grid */
    .content-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 25px;
      margin-bottom: 50px;
    }

    /* Futuristic cards */
    .futuristic-card {
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(20px);
      border: 2px solid rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      padding: 25px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .futuristic-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--primary-pink), var(--primary-sky));
    }

    .futuristic-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 25px 50px rgba(255, 20, 147, 0.2);
      border-color: rgba(255, 20, 147, 0.4);
    }

    .card-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 15px;
      margin-bottom: 15px;
      border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .card-title {
      background: linear-gradient(45deg, var(--primary-pink), var(--primary-sky));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 700;
      font-size: 1.3rem;
      margin-bottom: 15px;
    }

    .card-price {
      background: linear-gradient(45deg, var(--metallic-gold), var(--primary-sky));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 800;
      font-size: 1.5rem;
      margin-bottom: 15px;
    }

    .card-description {
      color: #64748b;
      margin-bottom: 20px;
      line-height: 1.6;
    }

    .futuristic-btn {
      background: linear-gradient(135deg, var(--primary-pink), var(--primary-sky));
      border: none;
      padding: 12px 25px;
      border-radius: 12px;
      color: white;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      display: inline-block;
      position: relative;
      overflow: hidden;
    }

    .futuristic-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
      z-index: -1;
    }

    .futuristic-btn:hover::before {
      left: 100%;
    }

    .futuristic-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(255, 20, 147, 0.3);
      color: white;
      text-decoration: none;
    }

    /* Action buttons */
    .action-buttons {
      position: absolute;
      top: 15px;
      right: 15px;
      display: flex;
      gap: 10px;
    }

    .action-btn {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 14px;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .action-btn:hover {
      transform: scale(1.1);
    }

    .edit-btn {
      background: linear-gradient(135deg, var(--primary-sky), var(--neon-glow));
    }

    .delete-btn {
      background: linear-gradient(135deg, #ff4757, #ff6b81);
    }

    /* Confirmation modal */
    .confirmation-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(5px);
      z-index: 2000;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border: 2px solid rgba(255, 20, 147, 0.3);
      border-radius: 20px;
      padding: 30px;
      max-width: 500px;
      width: 90%;
      text-align: center;
    }

    .modal-title {
      background: linear-gradient(45deg, var(--primary-pink), var(--primary-sky));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 800;
      margin-bottom: 20px;
    }

    .modal-buttons {
      display: flex;
      gap: 15px;
      justify-content: center;
      margin-top: 25px;
    }

    .modal-btn {
      padding: 10px 25px;
      border-radius: 12px;
      border: none;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .modal-confirm {
      background: linear-gradient(135deg, #ff4757, #ff6b81);
      color: white;
    }

    .modal-cancel {
      background: linear-gradient(135deg, var(--primary-sky), var(--neon-glow));
      color: white;
    }

    .modal-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }


    /* SweetAlert Custom Styling */
.sweet-alert-popup {
  background: rgba(255, 255, 255, 0.95) !important;
  backdrop-filter: blur(20px) !important;
  border: 2px solid rgba(255, 20, 147, 0.3) !important;
  border-radius: 20px !important;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3) !important;
}

.sweet-alert-popup .swal2-title {
  background: linear-gradient(45deg, var(--primary-pink), var(--primary-sky)) !important;
  -webkit-background-clip: text !important;
  -webkit-text-fill-color: transparent !important;
  background-clip: text !important;
  font-weight: 800 !important;
  font-family: 'Orbitron', monospace !important;
}

.sweet-alert-popup .swal2-content {
  color: #1a202c !important;
  font-family: 'Orbitron', monospace !important;
}

.sweet-alert-popup .swal2-confirm {
  background: linear-gradient(135deg, var(--primary-pink), var(--primary-sky)) !important;
  border: none !important;
  border-radius: 12px !important;
  font-weight: 600 !important;
  font-family: 'Orbitron', monospace !important;
}

.sweet-alert-popup .swal2-confirm:hover {
  transform: translateY(-2px) !important;
  box-shadow: 0 10px 25px rgba(255, 20, 147, 0.3) !important;
}

    @keyframes slideIn {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }

    @keyframes fadeOut {
      from { opacity: 1; }
      to { opacity: 0; visibility: hidden; }
    }

    /* Page title */
    h1 {
      background: linear-gradient(45deg, var(--primary-pink), var(--primary-sky));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 800;
      font-size: 3rem;
      text-align: center;
      margin-bottom: 40px;
    }

    /* Responsive design */
    @media (max-width: 992px) {
      .sidebar {
        left: -280px;
      }
      .main-content {
        margin-left: 0;
      }
      .content-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
      }
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
        .custom-modal-width {
  max-width: 900px; /* jitni width chahiye */
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

  <!-- Alert messages -->
  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
      <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_GET['success']) ?>
    </div>
  <?php endif; ?>
  
  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">
      <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_GET['error']) ?>
    </div>
  <?php endif; ?>

  <!-- Confirmation Modal -->
  <div class="confirmation-modal" id="confirmationModal">
    <div class="modal-content">
      <h3 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion</h3>
      <p>Are you sure you want to delete this item? This action cannot be undone.</p>
      <div class="modal-buttons">
        <button class="modal-btn modal-confirm" id="confirmDelete">Yes, Delete</button>
        <button class="modal-btn modal-cancel" id="cancelDelete">Cancel</button>
      </div>
    </div>
  </div>

  <!-- Edit Product Modal -->
  <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered custom-modal-width">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editProductForm" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit-id">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Product Title</label>
                <input type="text" class="form-control" name="title" id="edit-title" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Price ($)</label>
                <input type="number" class="form-control" step="0.01" name="price" id="edit-price" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Mood Category</label>
                <select class="form-control" name="mood" id="edit-mood" required>
                  <option value="happy">Happy</option>
                  <option value="sad">Sad</option>
                  <option value="anxious">Anxious</option>
                  <option value="angry">Angry</option>
                  <option value="excited">Excited</option>
                  <option value="calm">Calm</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Product Image</label>
                <input type="file" class="form-control" name="image" id="edit-image">
                <img id="preview-image" src="" class="mt-2" width="100" style="display: none;">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea class="form-control" name="description" id="edit-description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary1">
              <i class="fas fa-save me-2"></i> Update Product
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Edit Song Modal -->
<div class="modal fade" id="editSongModal" tabindex="-1" aria-labelledby="editSongModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content futuristic-modal">
      <div class="modal-header">
        <h5 class="modal-title" id="editSongModalLabel">
          <i class="fas fa-music me-2"></i>Edit Song
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editSongForm">
          <input type="hidden" name="id" id="edit-song-id">
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Song Title *</label>
              <input type="text" class="form-control" name="title" id="edit-song-title" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Singer *</label>
              <input type="text" class="form-control" name="singer" id="edit-song-singer" required>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Mood Category *</label>
              <select class="form-control" name="mood" id="edit-song-mood" required>
                <option value="happy">Happy</option>
                <option value="sad">Sad</option>
                <option value="anxious">Anxious</option>
                <option value="angry">Angry</option>
                <option value="excited">Excited</option>
                <option value="calm">Calm</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">File Path *</label>
              <input type="text" class="form-control" name="file_path" id="edit-song-file-path" required>
              <small class="text-muted">e.g., songs/happy_song.mp3 or YouTube link</small>
            </div>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Description (Optional)</label>
            <textarea class="form-control" name="description" id="edit-song-description" rows="3"></textarea>
          </div>
          
          <div class="text-center">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-2"></i> Update Song
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

  <!-- Same header as dashboard -->
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

  <!-- Sidebar -->
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
        <a class="nav-link" href="dashboard.php">
          <i class="fas fa-tachometer-alt"></i>
          Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="view_all.php">
          <i class="fas fa-eye"></i>
          View All Content
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="add_products.php">
          <i class="fas fa-plus-circle"></i>
          Add Product
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="add_songs.php">
          <i class="fas fa-music"></i>
          Add Song
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="add_quote.php">
          <i class="fas fa-quote-right"></i>
          Add Quote
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="add_meme.php">
          <i class="fas fa-grin-squint-tears"></i>
          Add Meme
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="add_game.php">
          <i class="fas fa-gamepad"></i>
          Add Game
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-chart-bar"></i>
          Analytics
        </a>
      </li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid">
      <h1>All Content Overview</h1>

      <!-- Products Section -->
      <div class="section-header">
        <h2><i class="fas fa-shopping-bag me-3"></i>Products</h2>
      </div>
      <div class="content-grid">
        <?php if (empty($products)): ?>
          <div class="futuristic-card text-center">
            <h3 class="card-title">No Products Added Yet</h3>
            <p class="card-description">Start adding products to see them here.</p>
            <a href="add_product.php" class="futuristic-btn">
              <i class="fas fa-plus me-2"></i>Add First Product
            </a>
          </div>
        <?php else: ?>
          <?php foreach($products as $product): ?>
            <div class="futuristic-card">
              <div class="action-buttons">
                <!-- Edit Button -->
                <a href="#" class="action-btn edit-btn" title="Edit Product" 
                   onclick="openEditModal(<?= $product['id'] ?>)">
                  <i class="fas fa-edit"></i>
                </a>
                <a href="#" class="action-btn delete-btn" title="Delete Product" 
                   onclick="confirmDelete('product', <?= $product['id'] ?>, '<?= htmlspecialchars(addslashes($product['title'])) ?>')">
                  <i class="fas fa-trash"></i>
                </a>
              </div>
              <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['title']) ?>" class="card-image">
              <h3 class="card-title"><?= htmlspecialchars($product['title']) ?></h3>
              <?php if (isset($product['price'])): ?>
                <div class="card-price">$<?= htmlspecialchars($product['price']) ?></div>
              <?php endif; ?>
              <p class="card-description"><?= htmlspecialchars($product['description'] ?? 'No description available') ?></p>
              <a href="../single_product.php?id=<?= (int)$product['id'] ?>" target="_blank" class="futuristic-btn">
                <i class="fas fa-external-link-alt me-2"></i>View Product
              </a>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- Songs Section -->
      <div class="section-header">
        <h2><i class="fas fa-music me-3"></i>Songs</h2>
      </div>
      <div class="content-grid">
        <?php if (empty($songs)): ?>
          <div class="futuristic-card text-center">
            <h3 class="card-title">No Songs Added Yet</h3>
            <p class="card-description">Start adding songs to see them here.</p>
            <a href="add_song.php" class="futuristic-btn">
              <i class="fas fa-plus me-2"></i>Add First Song
            </a>
          </div>
        <?php else: ?>
          <?php foreach($songs as $song): ?>
  <div class="futuristic-card">
  <div class="action-buttons">
  <a href="#" class="action-btn edit-btn" title="Edit Song" 
     onclick="openEditSongModal(<?= $song['id'] ?>)">
    <i class="fas fa-edit"></i>
  </a>
  <a href="#" class="action-btn delete-btn" title="Delete Song" 
     onclick="confirmDelete('song', <?= $song['id'] ?>, '<?= htmlspecialchars(addslashes($song['title'])) ?>')">
    <i class="fas fa-trash"></i>
  </a>
</div>

    <h3 class="card-title"><?= htmlspecialchars($song['title']) ?></h3>
    <p class="card-description"><strong>Singer:</strong> <?= htmlspecialchars($song['singer'] ?? 'Unknown') ?></p>
    <p class="card-description"><strong>Mood:</strong> <?= htmlspecialchars($song['mood'] ?? 'Any') ?></p>

    <!-- Listen Button -->
    <button class="futuristic-btn" onclick="playSong(this)" data-src="uploads/songs/<?= htmlspecialchars($song['file_path']) ?>">
      <i class="fas fa-play me-2"></i>Listen Now
    </button>

    <!-- Hidden Audio Player -->
    <div class="audio-player mt-3" style="display:none;">
      <audio controls style="width:100%;">
        <source src="uploads/songs/<?= htmlspecialchars($song['file_path']) ?>" type="audio/mpeg">
        Your browser does not support the audio element.
      </audio>
    </div>
  </div>
<?php endforeach; ?>

        <?php endif; ?>
      </div>


      <!-- Memes Section -->
      <div class="section-header">
        <h2><i class="fas fa-grin-squint-tears me-3"></i>Memes</h2>
      </div>
      <div class="content-grid">
        <?php if (empty($memes)): ?>
          <div class="futuristic-card text-center">
            <h3 class="card-title">No Memes Added Yet</h3>
            <p class="card-description">Start adding memes to see them here.</p>
            <a href="add_meme.php" class="futuristic-btn">
              <i class="fas fa-plus me-2"></i>Add First Meme
            </a>
          </div>
        <?php else: ?>
          <?php foreach($memes as $meme): ?>
            <div class="futuristic-card">
              <div class="action-buttons">
                <a href="edit_meme.php?id=<?= $meme['id'] ?>" class="action-btn edit-btn" title="Edit Meme">
                  <i class="fas fa-edit"></i>
                </a>
                <a href="#" class="action-btn delete-btn" title="Delete Meme" 
                   onclick="confirmDelete('meme', <?= $meme['id'] ?>, '<?= htmlspecialchars(addslashes($meme['title'])) ?>')">
                  <i class="fas fa-trash"></i>
                </a>
              </div>
              <img src="../<?= htmlspecialchars($meme['image']) ?>" alt="<?= htmlspecialchars($meme['title']) ?>" class="card-image">
              <h3 class="card-title"><?= htmlspecialchars($meme['title']) ?></h3>
              <p class="card-description"><strong>Mood:</strong> <?= htmlspecialchars($meme['mood'] ?? 'Any') ?></p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- Games Section -->
      <div class="section-header">
        <h2><i class="fas fa-gamepad me-3"></i>Games</h2>
      </div>
      <div class="content-grid">
        <?php if (empty($games)): ?>
          <div class="futuristic-card text-center">
            <h3 class="card-title">No Games Added Yet</h3>
            <p class="card-description">Start adding games to see them here.</p>
            <a href="add_game.php" class="futuristic-btn">
              <i class="fas fa-plus me-2"></i>Add First Game
            </a>
          </div>
        <?php else: ?>
          <?php foreach($games as $game): ?>
            <div class="futuristic-card">
              <div class="action-buttons">
                <a href="edit_game.php?id=<?= $game['id'] ?>" class="action-btn edit-btn" title="Edit Game">
                  <i class="fas fa-edit"></i>
                </a>
                <a href="#" class="action-btn delete-btn" title="Delete Game" 
                   onclick="confirmDelete('game', <?= $game['id'] ?>, '<?= htmlspecialchars(addslashes($game['title'])) ?>')">
                  <i class="fas fa-trash"></i>
                </a>
              </div>
              <img src="<?= htmlspecialchars($game['image']) ?>" alt="<?= htmlspecialchars($game['title']) ?>" class="card-image">
              <h3 class="card-title"><?= htmlspecialchars($game['title']) ?></h3>
              <p class="card-description"><?= htmlspecialchars($game['description'] ?? 'No description available') ?></p>
              <p class="card-description"><strong>Mood:</strong> <?= htmlspecialchars($game['mood'] ?? 'Any') ?></p>
              <a href="<?= htmlspecialchars($game['link']) ?>" target="_blank" class="futuristic-btn">
                <i class="fas fa-play me-2"></i>Play Game
              </a>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

    </div>
  </div>
  <!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // SweetAlert success message functions
function showSuccess(message) {
  Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: message,
    timer: 2000,
    timerProgressBar: true,
    showConfirmButton: false,
    background: 'rgba(255, 255, 255, 0.95)',
    backdrop: 'rgba(0, 0, 0, 0.5)',
    customClass: {
      popup: 'sweet-alert-popup'
    }
  });
}

function showError(message) {
  Swal.fire({
    icon: 'error',
    title: 'Error!',
    text: message,
    timer: 3000,
    timerProgressBar: true,
    background: 'rgba(255, 255, 255, 0.95)',
    backdrop: 'rgba(0, 0, 0, 0.5)',
    customClass: {
      popup: 'sweet-alert-popup'
    }
  });
}
    // Delete confirmation function
    let deleteUrl = '';
    
    function confirmDelete(type, id, title) {
      const modal = document.getElementById('confirmationModal');
      const message = modal.querySelector('p');
      message.innerHTML = `Are you sure you want to delete <strong>"${title}"</strong>? This action cannot be undone.`;
      
      deleteUrl = `view_all.php?delete=1&type=${type}&id=${id}`;
      modal.style.display = 'flex';
    }
    
    // Set up modal buttons
    document.getElementById('confirmDelete').addEventListener('click', function() {
      window.location.href = deleteUrl;
    });
    
    document.getElementById('cancelDelete').addEventListener('click', function() {
      document.getElementById('confirmationModal').style.display = 'none';
    });
    
    // Close modal when clicking outside
    document.getElementById('confirmationModal').addEventListener('click', function(e) {
      if (e.target === this) {
        this.style.display = 'none';
      }
    });

    // Function to open edit modal
    function openEditModal(productId) {
      // Fetch product data via AJAX
      fetch('get_product.php?id=' + productId)
        .then(response => response.json())
        .then(data => {
          // Populate the form fields
          document.getElementById('edit-id').value = data.id;
          document.getElementById('edit-title').value = data.title;
          document.getElementById('edit-price').value = data.price;
          document.getElementById('edit-mood').value = data.mood;
          document.getElementById('edit-description').value = data.description;
          
          // Set image preview
          const previewImg = document.getElementById('preview-image');
          previewImg.src = 'uploads/products/' + data.image;
          previewImg.style.display = 'block';
          
          // Show the modal using Bootstrap's modal method
          const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
          modal.show();
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error loading product data');
        });
    }

    // Product form submission
document.addEventListener('DOMContentLoaded', function() {
  const productForm = document.getElementById('editProductForm');
  if (productForm) {
    productForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      
      fetch('update_product.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        // Hide the modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editProductModal'));
        modal.hide();
        
        // Show SweetAlert success message
        showSuccess('Product updated successfully!');
        
        // Refresh the page after a short delay
        setTimeout(() => {
          location.reload();
        }, 2000);
      })
      .catch(error => {
        console.error('Error updating product:', error);
        showError('Error updating product: ' + error.message);
      });
    });
  }
});

    // Songs Play button
    function playSong(button) {
  let card = button.closest('.futuristic-card');
  let player = card.querySelector('.audio-player');

  // Pehle sab cards ke player hide kar dete hain
  document.querySelectorAll('.audio-player').forEach(p => {
    if (p !== player) {
      p.style.display = 'none';
      p.querySelector('audio').pause();
      p.querySelector('audio').currentTime = 0;
    }
  });

  // Clicked card ka player show karo
  if (player.style.display === "none") {
    player.style.display = "block";
    player.querySelector('audio').play();
  } else {
    player.querySelector('audio').pause();
    player.style.display = "none";
  }
}
// Function to open song edit modal
function openEditSongModal(songId) {
  console.log("Opening edit modal for song ID:", songId);
  
  // Fetch song data via AJAX
  fetch('get_song.php?id=' + songId)
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      console.log("Song data received:", data);
      
      // Check if we got an error
      if (data.error) {
        alert('Error: ' + data.error);
        return;
      }
      
      // Populate the form fields with correct database field names
      document.getElementById('edit-song-id').value = data.id;
      document.getElementById('edit-song-title').value = data.title || '';
      document.getElementById('edit-song-singer').value = data.singer || ''; // Changed from artist to singer
      document.getElementById('edit-song-mood').value = data.mood || 'happy';
      document.getElementById('edit-song-file-path').value = data.file_path || ''; // Changed from link to file_path
      document.getElementById('edit-song-description').value = data.description || '';
      
      // Show the modal using Bootstrap's modal method
      const modalElement = document.getElementById('editSongModal');
      const modal = new bootstrap.Modal(modalElement);
      modal.show();
    })
    .catch(error => {
      console.error('Error fetching song data:', error);
      alert('Error loading song data: ' + error.message);
    });
}

// Song form submission
document.addEventListener('DOMContentLoaded', function() {
  const songForm = document.getElementById('editSongForm');
  if (songForm) {
    songForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      
      fetch('update_song.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        // Hide the modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editSongModal'));
        modal.hide();
        
        // Show SweetAlert success message
        showSuccess('Song updated successfully!');
        
        // Refresh the page after a short delay
        setTimeout(() => {
          location.reload();
        }, 2000);
      })
      .catch(error => {
        console.error('Error updating song:', error);
        showError('Error updating song: ' + error.message);
      });
    });
  }
});
  </script>
</body>
</html>