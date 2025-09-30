<?php

ob_start(); // buffer output so header() always works
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require __DIR__ . '/db.php';   // make sure this uses $mysqli with port 3307

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

// Collect form data
$full_name = trim($_POST['full_name'] ?? '');
$email     = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$phone     = trim($_POST['phone'] ?? '');
$pwd       = $_POST['password'] ?? '';
$pwd2      = $_POST['confirm_password'] ?? '';
$terms     = isset($_POST['terms']);

$errors = [];

// Validation
if (!$full_name) $errors[] = 'Full name is required.';
if (!$email) $errors[] = 'A valid email is required.';
if (strlen($pwd) < 6) $errors[] = 'Password must be at least 6 characters.';
if ($pwd !== $pwd2) $errors[] = 'Passwords do not match.';
if (!$terms) $errors[] = 'You must agree to the Terms.';

if ($errors) {
    $_SESSION['errors'] = $errors;
    header("Location: login2.php"); // change login2.html → login2.php if you add PHP error display
    exit;
}

// Check duplicate email
$stmt = $mysqli->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['errors'] = ['Email already registered. Please log in.'];
    header("Location: login2.php");
    exit;
}
$stmt->close();

// Insert user
$hash = password_hash($pwd, PASSWORD_DEFAULT);
$stmt = $mysqli->prepare("INSERT INTO users (full_name, email, phone, password_hash) VALUES('$full_name', '$email', '$phone', '$hash')");
if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}
$stmt->bind_param("ssss", $full_name, $email, $phone, $hash);

if ($stmt->execute()) {
    header("Location: interface3.html");
    echo "<script>window.location.href='interface3.html';</script>";
    exit;
}
ob_end_flush();
