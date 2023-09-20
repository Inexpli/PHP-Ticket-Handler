<?php
session_start();

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'\project\config.php');

if(!isset($_SESSION['username'])){
  header('Location: index.php');
  exit; 
}

if((isset($_SESSION['mod']) && $_SESSION['mod'] == True) || (isset($_SESSION['admin']) && $_SESSION['admin'] == True)) {
  header('Location: panel/dashboard.php');
  exit; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/main.css">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
      <div class="col-md-3 mb-2 mb-md-0">
      </div>

      <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <li><a class="nav-link px-2 link-secondary">Home</a></li>
        <li><a href="#" class="nav-link px-2 text-p">Features</a></li>
        <li><a href="#" class="nav-link px-2 text-p">Pricing</a></li>
        <li><a href="#" class="nav-link px-2 text-p">FAQs</a></li>
        <li><a href="reports.php" class="nav-link px-2 text-p">Reports</a></li>
        <li><a href="#" class="nav-link px-2 text-p">About</a></li>
      </ul>

      <div class="col-md-3 text-end">
        <button type="button" class="btn btn-primary" onclick="document.location.href='logout.php';">Logout</button>
      </div>
    </header>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



