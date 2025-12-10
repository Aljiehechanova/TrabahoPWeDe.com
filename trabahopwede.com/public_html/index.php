<?php
$page = $_GET['page'] ?? 'home';

// Sanitize page name to prevent directory traversal
$page = basename($page);

// Full path to target PHP file in /views
$file = "views/$page.php";

// Check if the file exists, include it; otherwise show 404
if (file_exists($file)) {
    include $file;
} else {
    include "views/404.php"; // Create your own 404 page here
}