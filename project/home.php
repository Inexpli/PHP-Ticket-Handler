<?php
  session_start();

  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'\project\config.php');

  if(!isset($_SESSION['username'])){
    header('Location: index.php');
  }
  
  if($_SESSION['staff'] == True) {
    header('Location: dashboard.php');
  }
?>

<style>
.nav-link {
  color: #4723D9 !important;
}

.btn {
  --bs-btn-color: #fff !important;
  --bs-btn-bg: #4723D9 !important;
  --bs-btn-border-color: #4723D9 !important; 
  --bs-btn-hover-color: #fff !important;
  --bs-btn-hover-bg: #32189e !important;
  --bs-btn-hover-border-color: #32189e !important;
  --bs-btn-focus-shadow-rgb: 49,132,253 !important;
  --bs-btn-active-color: #fff !important;
  --bs-btn-active-bg: #32189e !important;
  --bs-btn-active-border-color: #32189e !important;
  --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125) !important;
  --bs-btn-disabled-color: #fff !important;
  --bs-btn-disabled-bg: #4723D9 !important;
  --bs-btn-disabled-border-color: #4723D9 !important;
}

</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/script.js"></script>
    <title>Main page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" rel="stylesheet">
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



