<?php
  session_start();
  require_once "config.php";

  if(!isset($_SESSION['username'])){
    header('Location: login.php');
  }
  
  if(isset($_SESSION['staff']) && $_SESSION['staff'] == True) {
    header('Location: panel/dashboard.php');
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
      <div class="col-md-2 col-lg-3 mb-2 mb-md-0">
      </div>

      <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <li><a href="home.php" class="nav-link px-2">Home</a></li>
        <li><a href="#" class="nav-link px-2">Features</a></li>
        <li><a href="#" class="nav-link px-2">Pricing</a></li>
        <li><a href="#" class="nav-link px-2">FAQs</a></li>
        <li><a class="nav-link px-2 link-secondary">Reports</a></li>
        <li><a href="#" class="nav-link px-2">About</a></li>
      </ul>

      <div class="col-md-2 col-lg-3 text-end">
        <button type="button" class="btn btn-primary" onclick="document.location.href='logout.php';">Logout</button>
      </div>
    </header>
    <div class="height-100" id="main-body">
        <?php
            $stmt = $conn->prepare("SELECT * FROM `reports` WHERE id = ?");
            $stmt->bind_param("i", $_GET['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            echo('<div class="ticket p-2 p-sm-0" style="border: 2px dashed #4723D9; border-radius: 0.525rem;">');
            while ($row = $result->fetch_assoc()) {
                $topic = $row['topic'];
                $status = $row['status'];
                $description = $row['description'];
                $created1 = $row['created'];
                if($status == True) {
                    $status = "Open";
                }
                else {
                    $status = "Closed";
                }
                $created = date("d.m.Y, H:i", strtotime($row['created']));

                echo('<div class="row p-3 mb-5">
                <div class="col-12 text-center">'. $created .'</div>
                <div class="col-12 pb-4 text-center">'. $topic .'</div>
                <div class="row">
                  <div class="col-6 p-3 bubble">'. $description .'<br><br><div class="text-start">'. $created .'</div></div>
                  <div class="col-6"></div>
                </div>');

                $stmt2 = $conn->prepare("SELECT * FROM `answers` WHERE report_id = ?");
                $stmt2->bind_param("i", $_GET['id']);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                if(mysqli_num_rows($result2) > 0) {
                  while ($row2 = $result2->fetch_assoc()) {
                      $answer = $row2['answer'];
                      $created2 = $row2['created'];
                      $created2 = date("d.m.Y, H:i", strtotime($row2['created']));
                  }
                echo('
                <div class="row">
                    <div class="col-6"></div>
                    <div class="col-6 p-3 mt-5 bubble">'. $answer .'<br><br><div class="text-end">'. $created2 .'</div></div>
                </div>
                </div>');
                }
            }
        ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>