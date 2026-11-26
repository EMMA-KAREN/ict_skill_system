<?php
// includes/auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function check_auth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /index.php');
        exit;
    }
}

function require_role($allowed_roles) {
    check_auth();
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        die("Access Denied: You do not have permission to view this module.");
    }
}
?>