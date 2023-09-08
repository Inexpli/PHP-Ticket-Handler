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

.btn-ticket {
    min-width: 80px !important;
}

@media only screen and (max-width: 992px) {
    
}
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/script.js"></script>
    <title>Reports</title>
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
        <li><a href="home.php" class="nav-link px-2">Home</a></li>
        <li><a href="#" class="nav-link px-2">Features</a></li>
        <li><a href="#" class="nav-link px-2">Pricing</a></li>
        <li><a href="#" class="nav-link px-2">FAQs</a></li>
        <li><a class="nav-link px-2 link-secondary">Reports</a></li>
        <li><a href="#" class="nav-link px-2">About</a></li>
      </ul>

      <div class="col-md-3 text-end">
        <button type="button" class="btn btn-primary" onclick="document.location.href='logout.php';">Logout</button>
      </div>
    </header>
    <div class="row no-gutter">
        <div class="col-md-12">
            <div class="login d-flex align-items-center py-5">
                <div class="container mt-5 mb-5">
                    <div class="row d-flex align-items-center justify-content-center">
                        <div class="col-12 col-md-8 col-lg-8 col-xl-8">
                            <form class="card px-4 px-sm-4 px-md-5 py-4 py-sm-4 py-md-5" action="reports.php" method="POST" id="reports">
                                <h3 class="mb-2 font-weight-bold text-center pb-2 pb-md-4">Submit a ticket</h3>
                                <div class="form-input pb-2">
                                    <select class="form-select form-control" aria-label="form-select" name="topic">
                                        <option selected>Topic</option>
                                        <option value="Questions">Questions</option>
                                        <option value="Technical Issues">Technical Issues</option>
                                        <option value="Transactions">Transactions</option>
                                        <option value="Refunds">Refunds</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="reports" style="padding-bottom: 0.25rem !important">Description</label>
                                    <textarea class="form-control" id="reports_textarea" rows="8" name="description"></textarea>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-primary mt-4 signup" type="submit" style="width: 150px;">Submit</button>
                                </div>
                            </form>
                            <?php
                                $stmt = $conn->prepare("SELECT * FROM `reports` WHERE user_id = ?");
                                $stmt->bind_param("i", $_SESSION['user_id']);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while ($row = $result->fetch_assoc()) {
                                    $topic = $row['topic'];
                                    $status = $row['status'];
                                    if($status == True) {
                                        $status = "Open";
                                    }
                                    else {
                                        $status = "Closed";
                                    }
                                    $last_updated = date("d.m.Y, H:i", strtotime($row['last_updated']));
                                    echo('
                                        <div class="ticket card">
                                            <div class="row">
                                                <div class="col-2 col-lg-4">
                                                    <button class="btn btn-primary btn-ticket">' . $status . '</button>
                                                </div>
                                                <div class="col-5 col-lg-4" style="padding-top: 6px; text-align: center;">
                                                    ' . $topic . '
                                                </div>
                                                <div class="col-5 col-lg-4 resp-text" style="padding-top: 6px; text-align: end; word-spacing: 0.10rem; padding-right: 20px;">
                                                    ' . $last_updated . '
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

<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $topic = $_POST['topic'];
    $description = $_POST['description'];
    if (isset($topic) && isset($description)) {
            $errors = array();

            if (empty($topic) || $topic == "Topic") {
                $errors[] = "Invalid topic.";
            }

            if (strlen($description) < 20) {
                $errors[] = "Description is too short, give more details.";
            }

            if (empty($errors)) {

                date_default_timezone_set('Europe/Warsaw');

                $stmt = $conn->prepare("INSERT INTO `reports` (user_id, topic, description, status, created, last_updated) VALUES (?, ?, ?, 1, date('d-m-Y, H:i'), date('d-m-Y, H:i'))");
                $stmt->bind_param("iss", $_SESSION['user_id'], $topic, $description);
                $stmt->execute();

                echo '<div class="alert alert-fixed alert-success alert-dismissible fade show text-center" role="alert">
                <strong>Ticket has been created. Our team will handle it.</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            } else {
                // Display error messages
                foreach ($errors as $error) {
                    echo '<div class="alert alert-fixed alert-danger alert-dismissible fade show text-center" role="alert">
                    <strong>' . $error . '</strong>.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
            }
        } 

        else {
            echo '<div class="alert alert-fixed alert-danger alert-dismissible fade show text-center" role="alert">
            <strong>Fill in all required fields</strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }

    exit();
}
?>