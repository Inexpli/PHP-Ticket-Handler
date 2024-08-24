<?php
  session_start();
  // Importing config
  require_once "config.php";

  // Ticket system
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    if (isset($message)) {
      $errors = array();

      if (strlen($message) < 10) {
          $errors[] = "Message is too short, give more details.";
      }

      if (empty($errors)) {
        $stmt1 = $conn->prepare("SELECT user_id, receiver FROM `reports` WHERE id = ?");
        $stmt1->bind_param("i", $_GET['id']);
        $stmt1->execute();
        $result = $stmt1->get_result();
        while ($row = $result->fetch_assoc()) {
          $receiver = $row['receiver'];
          $user_verify = $row['user_id'];
        }

        $stmt2 = $conn->prepare("INSERT INTO `messages` (message, sender_id, admin_id, report_id) VALUES (?, ?, ?, ?)");
        $stmt2->bind_param("siii", $message, $_SESSION['user_id'], $receiver, $_GET['id']);
        $stmt2->execute();

        $stmt3 = $conn->prepare("UPDATE `reports` SET last_updated = ?, status = 1 WHERE id = ?");
        $stmt3->bind_param("si", $currentTime, $_GET['id']);
        $stmt3->execute();
      } else {
          foreach ($errors as $error) {
              echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                  <strong>' . $error . '</strong>.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
          }
      }
      header("Location: ticket.php?id=" . $_GET['id']);
      exit;
    } else {
        echo '<div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
            <strong>Fill in all required fields</strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
  }

  // Checking if the user is logged in, otherwise he will be redirected to the login page
  if(!isset($_SESSION['username'])){
    header('Location: login.php');
    exit; 
  }
  // If the user does not have sufficient permissions, he is redirected to home
  if((isset($_SESSION['mod']) && $_SESSION['mod'] == True) || (isset($_SESSION['admin']) && $_SESSION['admin'] == True)) {
    header('Location: panel/dashboard.php');
    exit; 
  }

  // If the report does not belong to the user, he is redirected to home page
  $stmt_verify = $conn->prepare("SELECT user_id FROM `reports` WHERE id = ?");
  $stmt_verify->bind_param("i", $_GET['id']);
  $stmt_verify->execute();
  $result = $stmt_verify->get_result();
  while ($row = $result->fetch_assoc()) {
    $user_verify = $row['user_id'];
    if($_SESSION['user_id'] != $user_verify){
      header('Location: home.php');
      exit;
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ticket</title>
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
      <li><a href="home.php" class="nav-link px-2 text-p">Home</a></li>
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
    <div class="height-100" id="main-body">
      <div class="ticket p-2 mb-5 p-sm-0">
        <?php
          // Corespondention between user and admin in user view
            $stmt = $conn->prepare("SELECT * FROM `reports` WHERE id = ?");
            $stmt->bind_param("i", $_GET['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
              $category = $row['category'];
              $topic = $row['topic'];
              $user_id = $row['user_id'];
              $status = $row['status'];
              $description = $row['description'];
              $created = date("d.m.Y, H:i", strtotime($row['created']));

              echo('
              <div class="row p-3">
              <div class="col-12 text-center">'. $created .'</div>
              <div class="col-12 text-center">'. $category .'</div>
              <div class="col-12 pb-4 text-center">'. $topic .'</div>
              </div>
              <div class="row">
              <div class="col-6"></div>
              <div class="col-6">
              <div class="bubble p-2" style="display: inline-block; word-wrap: break-word; word-break: break-word; float:right; margin-bottom: 1px;">
              '. $description .'<br><br><div class="text-end">'. $created .'</div>
              </div>
              </div>
              </div>');

              $stmt2 = $conn->prepare("SELECT * FROM `messages` WHERE report_id = ?");
              $stmt2->bind_param("i", $_GET['id']);
              $stmt2->execute();
              $result2 = $stmt2->get_result();
              if(mysqli_num_rows($result2) > 0) {
                while ($row2 = $result2->fetch_assoc()) {
                  $id = $row2['id'];
                  $message = $row2['message'];
                  $sender = $row2['sender_id'];
                  $admin_id = $row2['admin_id'];
                  $created2 = $row2['created'];
                  $created2 = date("d.m.Y, H:i", strtotime($row2['created']));
                  if($sender == $user_id) {
                    echo('
                    <div class="row">
                    <div class="col-6"></div>
                    <div class="col-6">
                    <div class="bubble p-2" style="display: inline-block; word-wrap: break-word; word-break: break-word; float:right; margin-bottom: 1px;">
                    '. $message .'<br><br><div class="text-end">'. $created2 .'</div>
                    </div>
                    </div>
                    </div>');
                  }
                  else {
                    echo('
                    <div class="row">
                    <div class="col-6">
                    <div class="bubble p-2" style="word-wrap: word-wrap: break-word; word-break: break-word; display: inline-block; margin-bottom: 1px;">
                    '. $message .'<br><br><div class="text-start">'. $created2 .'</div>
                    </div>
                    </div>
                    <div class="col-6"></div>
                    </div>');
                  }
                }
              }
            echo("</div>");
          }
        ?>
      </div>
      <div class="row d-flex align-items-center justify-content-center mb-5">
        <div class="col-12 col-sm-10 col-md-9 col-lg-7 col-xl-6">
          <form action="ticket.php?id=<?php echo($_GET['id']) ?>" method="POST" id="message">
              <div class="form-group">
                  <textarea class="form-control" id="message_textarea" rows="8" name="message"></textarea>
              </div>
              <div class="text-center">
                  <button class="btn btn-primary mt-4 signup" type="submit" style="width: 150px;">Submit</button>
              </div>
          </form>
        </div>
      </div>
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
      
      // Update theme depending on button press
      var element = document.body;
      element.dataset.bsTheme = element.dataset.bsTheme == "light" ? "dark" : "light";

      // Update button css class depending on theme
      if(value) {
        moonButton.className = "btn btn-outline-primary bi bi-moon-stars";
      } else {
        moonButton.className = "btn btn-outline-primary bi bi-moon-stars-fill";
      }

      // Update cookie 'darkmode'
      setCookie('darkmode', !value, 30);
    }

    // Update theme depending on cookie value
    window.onload = function() {
      const darkMode = getCookie("darkmode") === "true";
      const moonButton = document.getElementById("moon");
      moonButton.setAttribute('value', darkMode);

      
      var element = document.body;
      element.dataset.bsTheme = darkMode ? "dark" : "light";

      // Update button css class depending on theme
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
