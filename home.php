<?php
  session_start();
  // Importing config
  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'\project\config.php');
  // Checking if the user is logged in, otherwise he will be redirected to the login page
  if(!isset($_SESSION['username'])){
    header('Location: index.php');
    exit; 
  }
  // If user has moderator or admin rights, he is redirected to the dashboard
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
    <div class="col-md-0 col-lg-3 mb-2 mb-md-0">
    </div>

    <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
      <li><a href="#" class="nav-link px-2 text-p link-secondary">Home</a></li>
      <li><a href="#" class="nav-link px-2 text-p">Features</a></li>
      <li><a href="#" class="nav-link px-2 text-p">Pricing</a></li>
      <li><a href="#" class="nav-link px-2 text-p">FAQs</a></li>
      <li><a href="reports.php" class="nav-link px-2 text-p">Reports</a></li>
      <li><a href="#" class="nav-link px-2 text-p">About</a></li>
    </ul>

    <div class="col-md-3 text-end">
      <button type="button" class="btn btn-primary me-2" onclick="document.location.href='logout.php';">Logout</button>
      <i class="btn btn-outline-primary bi bi-moon-stars" value="False" id="moon" style="float: right;" onclick="themeMode()"></i>
    </div>
  </header>
  <script>
    function setCookie(name, value, days) {
      var expires = "";
      if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
      }
      document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
      var nameEQ = name + "=";
      var ca = document.cookie.split(';');
      for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
      }
      return null;
    }

    function themeMode() {
      const moonButton = document.getElementById('moon');
      const value = moonButton.getAttribute('value') === 'true'; 
      moonButton.setAttribute('value', !value);
      
      var element = document.body;
      element.dataset.bsTheme = element.dataset.bsTheme == "light" ? "dark" : "light";

      // Zaktualizuj klasę CSS na podstawie nowego motywu
      if(value) {
        moonButton.className = "btn btn-outline-primary bi bi-moon-stars";
      } else {
        moonButton.className = "btn btn-outline-primary bi bi-moon-stars-fill";
      }

      // Zaktualizuj ciasteczko 'darkmode'
      setCookie('darkmode', !value, 30);
    }

    // Ustaw motyw na podstawie ciasteczka przy załadowaniu strony
    window.onload = function() {
      const darkMode = getCookie("darkmode") === "true";
      const moonButton = document.getElementById("moon");
      moonButton.setAttribute('value', darkMode);

      
      var element = document.body;
      element.dataset.bsTheme = darkMode ? "dark" : "light";

      // Ustaw klasę CSS przycisku na podstawie ciasteczka
      if(darkMode) {
        moonButton.className = "btn btn-outline-primary bi bi-moon-stars-fill";

      } else {
        moonButton.className = "btn btn-outline-primary bi bi-moon-stars";
      }
    };
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


