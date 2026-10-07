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

// Delete user before any output
if (isset($_GET['delete_user']) && $_SESSION['role'] === 'admin') {
    $id = intval($_GET['delete_user']);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MoodBuddy Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/css/fontawesome.min.css" />
  <link rel="stylesheet" href="style.css">
  <!-- Updated to Orbitron font for futuristic 2027 theme -->
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
</head>

<body>
  <!-- Added floating background objects -->
  <div class="floating-objects">
    <div class="floating-object"></div>
    <div class="floating-object"></div>
    <div class="floating-object"></div>
    <div class="floating-object"></div>
  </div>

  <!-- Admin Header Section -->
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
        <a class="nav-link active" href="dashboard.php">
          <i class="fas fa-tachometer-alt"></i>
          Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="view_all.php">
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
      <div class="row mb-4">
        <div class="col-12">
          <h4 class="mb-0">Admin Dashboard</h4>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </nav>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="row">
        <div class="col-md-6 col-lg-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-muted mb-2">Total Users</h6>
                  <h3 class="mb-0">1,248</h3>
                </div>
                <div class="bg-primary bg-opacity-10 p-3 rounded">
                  <i class="fas fa-users text-primary"></i>
                </div>
              </div>
              <p class="mb-0 mt-2 text-muted"><small>+24 this week</small></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-muted mb-2">Content Items</h6>
                  <h3 class="mb-0">487</h3>
                </div>
                <div class="bg-success bg-opacity-10 p-3 rounded">
                  <i class="fas fa-file-alt text-success"></i>
                </div>
              </div>
              <p class="mb-0 mt-2 text-muted"><small>+15 this week</small></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-muted mb-2">Mood Entries</h6>
                  <h3 class="mb-0">3,842</h3>
                </div>
                <div class="bg-info bg-opacity-10 p-3 rounded">
                  <i class="fas fa-smile text-info"></i>
                </div>
              </div>
              <p class="mb-0 mt-2 text-muted"><small>+128 today</small></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-muted mb-2">Avg. Mood</h6>
                  <h3 class="mb-0">7.2</h3>
                </div>
                <div class="bg-warning bg-opacity-10 p-3 rounded">
                  <i class="fas fa-chart-line text-warning"></i>
                </div>
              </div>
              <p class="mb-0 mt-2 text-muted"><small>+0.5 from last week</small></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Content and Activity -->
      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0">Recent Content Added</h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Type</th>
                      <th>Title</th>
                      <th>Mood</th>
                      <th>Date</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>MB-124</td>
                      <td><span class="badge bg-primary">Song</span></td>
                      <td>Relaxing Piano Music</td>
                      <td>Anxious</td>
                      <td>10 min ago</td>
                      <td>
                        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                      </td>
                    </tr>
                    <tr>
                      <td>MB-123</td>
                      <td><span class="badge bg-success">Meme</span></td>
                      <td>Funny Cat Meme</td>
                      <td>Happy</td>
                      <td>25 min ago</td>
                      <td>
                        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                      </td>
                    </tr>
                    <tr>
                      <td>MB-122</td>
                      <td><span class="badge bg-info">Quote</span></td>
                      <td>Inspirational Quote #42</td>
                      <td>Sad</td>
                      <td>1 hour ago</td>
                      <td>
                        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                      </td>
                    </tr>
                    <tr>
                      <td>MB-121</td>
                      <td><span class="badge bg-warning">Game</span></td>
                      <td>Mindfulness Puzzle</td>
                      <td>Anxious</td>
                      <td>2 hours ago</td>
                      <td>
                        <a href="edit_user.php?id=MB-121" class="btn btn-sm btn-primary">
                          <i class="fas fa-edit"></i>
                        </a>
                        <a href="dashboard.php?delete_user=MB-121" 
                           onclick="return confirm('Are you sure?')" 
                           class="btn btn-sm btn-danger">
                          <i class="fas fa-trash"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
          <!-- User Management Section -->
          <?php if ($_SESSION['role'] === 'admin'): ?>
  <div class="card mt-4" id="user-management-section">
    <div class="card-header">
      <h5 class="card-title mb-0">User Management</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Profile</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Username</th>
              <th>Email</th>
              <th>Role</th>
              <th>Join Date</th>
              <th colspan="2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            require_once __DIR__ . '/db.php'; // DB connection

            $stmt = $pdo->prepare("SELECT id, first_name, last_name, username, email, role, profile_photo, created_at FROM users");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($users as $user):
            ?>
              <tr>
                <td><?= $user['id'] ?></td>
                <td>
                  <img src="uploads/<?= htmlspecialchars($user['profile_photo'] ?? 'default.jpg') ?>" 
                       width="40" height="40" class="rounded-circle" alt="Profile">
                </td>
                <td><?= htmlspecialchars($user['first_name'] ?? '') ?></td>
                <td><?= htmlspecialchars($user['last_name'] ?? '') ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><span class="badge bg-info"><?= htmlspecialchars($user['role']) ?></span></td>
                <td><?= htmlspecialchars($user['created_at']) ?></td>
                <td>
                  <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i>
                  </a>
                </td>
                <td>
                  <a href="dashboard.php?delete_user=<?= $user['id'] ?>" 
                     onclick="return confirm('Are you sure?')" 
                     class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php endif; ?>
            </div>
        
        <div class="col-lg-4">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
              <a href="add_product.php" class="btn btn-admin w-100 mb-3">
                <i class="fas fa-plus-circle me-2"></i> Add New Product
              </a>
              <a href="add_song.php" class="btn btn-outline-primary w-100 mb-3">
                <i class="fas fa-music me-2"></i> Add New Song
              </a>
              <a href="add_quote.php" class="btn btn-outline-primary w-100 mb-3">
                <i class="fas fa-quote-right me-2"></i> Add New Quote
              </a>
              <a href="add_meme.php" class="btn btn-outline-primary w-100 mb-3">
                <i class="fas fa-grin-squint-tears me-2"></i> Add New Meme
              </a>
              <a href="add_game.php" class="btn btn-outline-primary w-100">
                <i class="fas fa-gamepad me-2"></i> Add New Game
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="../assets/js/vendor/jquery-3.6.0.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
