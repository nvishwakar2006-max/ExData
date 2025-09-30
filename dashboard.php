<?php
// dashboard.php
session_start();
if (empty($_SESSION['user_id'])) {
  header('Location: sign_in_page.html');
  exit;
}
?>
<!doctype html>
<html>
  <head><meta charset="utf-8"><title>Dashboard</title></head>
  <body style="font-family: Segoe UI, sans-serif;">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?> 👋</h2>
    <p>You’re logged in as <?php echo htmlspecialchars($_SESSION['email']); ?>.</p>
    <p><a href="logout.php">Log out</a></p>
  </body>
</html>
