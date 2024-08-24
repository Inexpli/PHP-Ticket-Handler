<?php
  session_start();
  // Importing config
  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'\project\config.php');
  // If user has moderator or admin rights, he is redirected to the dashboard
  if((isset($_SESSION['mod']) && $_SESSION['mod'] == True) || (isset($_SESSION['admin']) && $_SESSION['admin'] == True)) {
    header('Location: panel/dashboard.php');
    exit; 
  }
  // If user is logged in, he is redirected to the home page
  if(isset($_SESSION['username'])){
    header('Location: home.php');
    exit;
  }
?>

<style>
  @media only screen and (max-width: 576px) {
    .btn-md {
      --bs-btn-padding-y: 0.25rem !important;
      --bs-btn-padding-x: 0.5rem !important;
      --bs-btn-font-size: 0.875rem !important;
      --bs-btn-border-radius: var(--bs-border-radius-sm) !important;
    }
  }
  
  @media only screen and (min-width: 992px) {
    .btn-md {
      --bs-btn-padding-y: 0.5rem !important;
      --bs-btn-padding-x: 1rem !important;
      --bs-btn-font-size: 1.25rem !important;
      --bs-btn-border-radius: var(--bs-border-radius-lg) !important;
    }
  }

</style>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Company</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/main.css">
  <!-- Bootstrap Icons CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
</head>
<body data-bs-theme="light">
<div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between mt-3 py-3 mb-3">
      <div class="col-md-3 mb-2 mb-md-0">
      </div>

      <ul class="nav col-12 col-md-12 col-lg-auto mb-2 justify-content-center mb-md-3 mb-lg-0">
        <li><a class="nav-link px-2 link-secondary">Home</a></li>
        <li><a href="" class="nav-link px-2 text-p">Features</a></li>
        <li><a href="" class="nav-link px-2 text-p">Pricing</a></li>
        <li><a href="" class="nav-link px-2 text-p">FAQs</a></li>
        <li><a href="reports.php" class="nav-link px-2 text-p">Reports</a></li>
        <li><a href="" class="nav-link px-2 text-p">About</a></li>
      </ul>

      <div class="col-md-12 col-lg-3 text-md-center text-end">
        <button type="button" class="btn btn-outline-primary me-2" onclick="document.location.href='login.php';">Login</button>
        <button type="button" class="btn btn-primary me-2" onclick="document.location.href='register.php';">Sign-up</button>
        <i class="btn btn-outline-primary bi bi-moon-stars" value="False" id="moon" style="padding: 10px; float: right;" onclick="themeMode()"></i>
      </div>
    </header>

    <div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-label="Slide 1" aria-current="true" style="background-color: white"></button>
        <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2" style="background-color: white"></button>
        <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3" style="background-color: white"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active">
        <img src="img/carousel_1.png" class="d-block w-100" alt="slide-image-1">
            <div class="carousel-caption text-start" style="color: white !important">
              <h1>Example headline.</h1>
              <p class="opacity-75">Some representative placeholder content for the first slide of the carousel.</p>
              <p><a class="btn btn-md btn-primary" href="register.php">Sign up today</a></p>
            </div>
        </div>
        <div class="carousel-item">
          <img src="img/carousel_2.png" class="d-block w-100" alt="slide-image-2">
            <div class="carousel-caption" style="color: white !important">
              <h1>Another example headline.</h1>
              <p>Some representative placeholder content for the second slide of the carousel.</p>
              <p><a class="btn btn-md btn-primary" href="#">Learn more</a></p>
            </div>
        </div>
        <div class="carousel-item">
          <img src="img/carousel_3.jpg" class="d-block w-100" alt="slide-image-3">
            <div class="carousel-caption text-end" style="color: white !important">
              <h1>One more for good measure.</h1>
              <p>Some representative placeholder content for the third slide of this carousel.</p>
              <p><a class="btn btn-md btn-primary" href="#">About</a></p>
            </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: none"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true" style="filter: none"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>

    <div class="container marketing pt-5" style="border:none !important;">
    <!-- Three columns of text below the carousel -->
    <div class="row text-center py-5">
      <div class="col-lg-4">
        <img src="img/cpu.svg" class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title>
        <h2 class="fw-normal pt-2">Heading</h2>
        <p class="px-2">In ultrices libero ut leo auctor, non lacinia libero pulvinar. Mauris tortor metus, euismod nec blandit nec.</p>
        <p><a class="btn btn-outline-primary" href="#">View details »</a></p>
      </div><!-- /.col-lg-4 -->
      <div class="col-lg-4">
      <img src="img/gpu.svg" class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title>
        <h2 class="fw-normal pt-2">Heading</h2>
        <p class="px-2">In ultrices libero ut leo auctor, non lacinia libero pulvinar. Mauris tortor metus, euismod nec blandit nec.</p>
        <p><a class="btn btn-outline-primary" href="#">View details »</a></p>
      </div><!-- /.col-lg-4 -->
      <div class="col-lg-4">
      <img src="img/network.svg" class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title>
        <h2 class="fw-normal pt-2">Heading</h2>
        <p class="px-2">In ultrices libero ut leo auctor, non lacinia libero pulvinar. Mauris tortor metus, euismod nec blandit nec.</p>
        <p><a class="btn btn-outline-primary" href="#">View details »</a></p>
      </div><!-- /.col-lg-4 -->
    </div><!-- /.row -->


    <!-- START THE FEATURETTES -->

    <hr class="featurette-divider">

    <div class="row featurette py-3">
      <div class="col-md-7">
        <h2 class="featurette-heading fw-normal lh-1">First featurette heading. <span class="text-body-secondary">It’ll blow your mind.</span></h2>
        <p class="lead">Some great placeholder content for the first featurette here. Imagine some exciting prose here.</p>
      </div>
      <div class="col-md-5">
        <svg class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="var(--bs-secondary-bg)"></rect><text x="50%" y="50%" fill="var(--bs-secondary-color)" dy=".3em">500x500</text></svg>
      </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette py-3">
      <div class="col-md-7 order-md-2">
        <h2 class="featurette-heading fw-normal lh-1">Oh yeah, it’s that good. <span class="text-body-secondary">See for yourself.</span></h2>
        <p class="lead">Another featurette? Of course. More placeholder content here to give you an idea of how this layout would work with some actual real-world content in place.</p>
      </div>
      <div class="col-md-5 order-md-1">
        <svg class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="var(--bs-secondary-bg)"></rect><text x="50%" y="50%" fill="var(--bs-secondary-color)" dy=".3em">500x500</text></svg>
      </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette py-3">
      <div class="col-md-7">
        <h2 class="featurette-heading fw-normal lh-1">And lastly, this one. <span class="text-body-secondary">Checkmate.</span></h2>
        <p class="lead">And yes, this is the last block of representative placeholder content. Again, not really intended to be actually read, simply here to give you a better view of what this would look like with some actual content. Your content.</p>
      </div>
      <div class="col-md-5">
        <svg class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="var(--bs-secondary-bg)"></rect><text x="50%" y="50%" fill="var(--bs-secondary-color)" dy=".3em">500x500</text></svg>
      </div>
    </div>

    <hr class="featurette-divider">

    <!-- /END THE FEATURETTES -->

  </div>
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
      const darkMode = getCookie('darkmode') === 'true';
      const moonButton = document.getElementById('moon');
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