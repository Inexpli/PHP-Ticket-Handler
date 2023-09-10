<?php
  session_start();
  require_once "config.php";

  if(!isset($_SESSION['username'])){
    header('Location: login.php');
  }
  
  if(isset($_SESSION['staff']) && $_SESSION['staff'] == True) {
    header('Location: dashboard.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/script.js"></script>
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
    <div class="row no-gutter">
        <div class="col-md-12">
            <div class="login d-flex align-items-center py-5">
                <div class="container-fluid mt-5 mb-5">
                    <div class="row d-flex align-items-center justify-content-center">
                        <div class="col-12 col-sm-10 col-md-9 col-lg-7 col-xl-6">
                            <?php
                                $stmt = $conn->prepare("SELECT * FROM `reports` WHERE id = ?");
                                $stmt->bind_param("i", $_GET['id']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ($row = $result->fetch_assoc()) {
                                    $topic = $row['topic'];
                                    $status = $row['status'];
                                    $description = $row['description'];
                                    if($status == True) {
                                        $status = "Open";
                                    }
                                    else {
                                        $status = "Closed";
                                    }
                                    $last_updated = date("d.m.Y, H:i", strtotime($row['last_updated']));
                                    echo('
                                        <div class="ticket card p-2 p-sm-0">
                                            <div class="row text-center p-3">
                                                <div class="col-6"></div>
                                                <div class="col-6">
                                                    ' . $last_updated . '
                                                </div>
                                                <div class="col-6"></div>
                                                <div class="col-6 pb-2">
                                                    ' . $topic . '
                                                </div>
                                                <div class="col-6"></div>
                                                <div class="col-6">
                                                    ' . $description . '
                                                </div>
                                            </div>
                                        </div>'
                                    );
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>