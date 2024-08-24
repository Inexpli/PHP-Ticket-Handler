<?php
  session_start();
  // Importing config
  require_once "config.php";
  // If user is logged in, he will be redirected to the home page
  if(!isset($_SESSION['username'])){
    header('Location: login.php');
    exit;
  }
  
  if((isset($_SESSION['mod']) && $_SESSION['mod'] == True) || (isset($_SESSION['admin']) && $_SESSION['admin'] == True)) {
    header('Location: panel/dashboard.php');
    exit; 
  }
?>

<style>

.btn-ticket {
    min-width: 80px !important;
}
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
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
                            <form class="card px-4 px-sm-4 px-md-5 py-4 py-sm-4 py-md-5 mb-5" action="reports.php" method="POST" id="reports">
                                <h3 class="mb-2 font-weight-bold text-center pb-2 pb-md-4">Submit a ticket</h3>
                                <div class="form-input pb-2">
                                    <select class="form-select form-control" aria-label="form-select" name="category">
                                        <option selected>Category</option>
                                        <option value="Questions">Questions</option>
                                        <option value="Technical Issues">Technical Issues</option>
                                        <option value="Transactions">Transactions</option>
                                        <option value="Refunds">Refunds</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-input pb-2">
                                    <input class="form-control" type="text" placeholder="Topic" aria-label="login input" name="topic">
                                </div>
                                <div class="form-group">
                                    <span style="padding-bottom: 0.25rem !important">Description</span>
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
                                
                                if ($result->num_rows > 0) {
                                    echo '<div class="col-12 text-center pb-2 fs-5">Your tickets: </div>';
                                }

                                while ($row = $result->fetch_assoc()) {
                                    $category = $row['category'];
                                    $status = $row['status'];
                                    $id = $row['id'];
                                    if($status == True) {
                                        $status = "Waiting...";
                                    }
                                    else {
                                        $status = "Answered";
                                    }
                                    $last_updated = date("d.m.Y, H:i", strtotime($row['last_updated']));
                                    echo('
                                        <div class="ticket card p-2 p-sm-0">
                                            <div class="row">
                                                <div class="col-12 col-sm-2 col-md-4 text-center text-sm-start">
                                                    <button class="btn btn-success btn-ticket"><a href="ticket.php?id='. $id .'" style="color: white;">' . $status . '</a></button>
                                                </div>
                                                <div class="col-12 col-sm-5 col-md-4 text-center text-sm-end text-md-center" style="padding-top: 6px;">
                                                    ' . $category . '
                                                </div>
                                                <div class="col-12 col-sm-5 col-md-4 text-center text-sm-end" style="padding-top: 6px; word-spacing: 0.10rem; padding-right: 20px;">
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
    $category = $_POST['category'];
    $topic = $_POST['topic'];
    $description = $_POST['description'];
    if (isset($topic) && isset($description)) {
            $errors = array();

            if (empty($topic) || $category == "Category") {
                $errors[] = "Invalid topic.";
            }

            if (strlen($description) < 20) {
                $errors[] = "Description is too short, give more details.";
            }

            if (empty($errors)) {

                date_default_timezone_set('Europe/Warsaw');

                $stmt = $conn->prepare("INSERT INTO `reports` (user_id, category, topic, description, status, created, last_updated) VALUES (?, ?, ?, ?, 1, date('d-m-Y, H:i'), date('d-m-Y, H:i'))");
                $stmt->bind_param("isss", $_SESSION['user_id'], $category, $topic, $description);
                $stmt->execute();
                
                echo '<meta http-equiv="refresh" content="0">';

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
