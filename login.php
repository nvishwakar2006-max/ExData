<?php
// login.php
session_start();
require __DIR__ . '/db.php';

// Security best practices
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$pwd   = $_POST['password'] ?? '';

if (!$email || !$pwd) {
    header('Location: sign_in_page.html?error=missing');
    exit;
}

$stmt = $mysqli->prepare('SELECT id, full_name, email, password_hash FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($pwd, $user['password_hash'])) {
    header('Location: sign_in_page.html?error=invalid');
    exit;
}

// Login successful
$_SESSION['user_id']   = $user['id'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['email']     = $user['email'];

// Redirect to dashboard (use .php if you need session values)
header('Location: interface3.html');
exit;
