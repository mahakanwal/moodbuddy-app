<?php
require_once __DIR__ . '/db.php';

$message = '';
$type = '';

if (isset($_GET['verify_token'])) {
    $token = $_GET['verify_token'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE verify_token = ? AND is_verified = 0 LIMIT 1");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $update = $pdo->prepare("UPDATE users SET is_verified = 1, verify_token = NULL WHERE id = ?");
        $update->execute([$user['id']]);

        $message = "Email verified successfully! You can now login.";
        $type = "success";
    } else {
        $message = "Invalid or expired token!";
        $type = "error";
    }
} else {
    $message = "No token provided!";
    $type = "warning";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Email Verification</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
    Swal.fire({
        icon: '<?php echo $type; ?>',
        title: '<?php echo $message; ?>',
        confirmButtonText: 'OK'
    }).then(() => {
        // Redirect automatically after closing alert
        window.location.href = "index.php";
    });
</script>
</body>
</html>
