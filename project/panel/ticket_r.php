<?php
  session_start();
  // Importing config
  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'\config.php');
  // Checking if the user is logged in, otherwise he will be redirected to the login page
  if(!isset($_SESSION['username'])){
    header('Location: ../login.php');
    exit;
  }
  // If the user does not have sufficient permissions, he is redirected to home
  if(!isset($_SESSION['mod']) && !isset($_SESSION['admin']) && !isset($_SESSION['redirected'])) {
    $_SESSION['redirected'] = true;
    header('Location: ../home.php');
    exit;
  }
?>

<script>
    document.addEventListener("DOMContentLoaded", function (event) {

        const showNavbar = (toggleId, navId, bodyId, headerId) => {
            const toggle = document.getElementById(toggleId),
                nav = document.getElementById(navId),
                bodypd = document.getElementById(bodyId),
                headerpd = document.getElementById(headerId)

            // Validating if all variables exist
            if (toggle && nav && bodypd && headerpd) {
                toggle.addEventListener('click', () => {
                    // Show navbar
                    nav.classList.toggle('show')
                    // Change icon
                    toggle.classList.toggle('bx-x')
                    // Add padding to body
                    bodypd.classList.toggle('body-pd')
                    // Add padding to header
                    headerpd.classList.toggle('body-pd')
                })
            }
        }

        showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header')

        /*===== LINK ACTIVE =====*/
        const linkColor = document.querySelectorAll('.nav_link')

        function colorLink() {
            if (linkColor) {
                linkColor.forEach(l => l.classList.remove('active'))
                this.classList.add('active')
            }
        }
        linkColor.forEach(l => l.addEventListener('click', colorLink))

        // Code to run since DOM is loaded and ready
    });
</script>

<style>
    @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap");
</style>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket: <?php echo $_GET['id']; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/dashboard.css">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
</head>

<body id="body-pd" style="background-color: #212529;">
<header class="header bg-dark" id="header">
        <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
    </header>
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div> <a class="nav_logo active" href="dashboard.php"> <i class='bx bx-grid-alt nav_logo-icon'></i> <span
                        class="nav_logo-name">Dashboard</span> </a>
                <div class="nav_list">
                    <?php
                    if(isset($_SESSION['admin'])) {
                        echo("
                        <a href='stats.php' class='nav_link'> <i class='bx bx-bar-chart-alt-2 nav_icon'></i> <span
                            class='nav_name'>Statistics</span> </a> 
                        ");
                    }
                    ?>
                    <a href="clients.php" class="nav_link" id="users"> <i
                            class='bx bx-user nav_icon'></i> <span class="nav_name">Users</span> </a> <a
                        href="tickets.php" class="nav_link"> <i class='bx bx-message-square-detail nav_icon'></i>
                        <span class="nav_name">Reports</span> </a>
                        <a href="formulas.php" class="nav_link"> <i
                            class='bx bx-bookmark nav_icon'></i> <span class="nav_name">Formulas</span> </a> <a href="#"
                        class="nav_link"> <i class='bx bx-folder nav_icon'></i> <span class="nav_name">Files</span> </a>
                        
                    </div>
            </div> <a href="../logout.php" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span
                    class="nav_name">Sign out</span> </a>
        </nav>
    </div>
    <!--Container Main start-->
    <div class="bg-dark" id="main-body" style="color: white">
        <?php
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
                $handling_by = $row['handling_by'];
                if($handling_by != NULL) { 
                    $checked = "checked";
                }
                else {
                    $checked = "";
                }
                echo('
                <div class="row p-3 pt-4 mb-5">
                <div class="col-12 text-center">'. $created .'</div>
                <div class="col-12 text-center">'. $category .'</div>
                <div class="col-12 pb-4 text-center">'. $topic .'</div>
                </div>
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="bubble p-2" style="word-wrap: break-word;">
                        '. $description .'<br><br><div class="text-start">'. $created .'</div>
                        </div>
                    </div>
                </div>'
                );

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
                      <div class="col-6">
                      <div class="bubble p-2" style="word-wrap: break-word; display: inline-block; margin-bottom: 1px;">
                      '. $message .'<br><br><div class="text-start">'. $created2 .'</div>
                      </div>
                      </div>
                      <div class="col-6"></div>
                      </div>');
                    }
                    else {
                      echo('
                      <div class="row">
                      <div class="col-6"></div>
                      <div class="col-6">
                      <div class="bubble p-2" style="word-wrap: break-word; margin-bottom: 1px;">
                      '. $message .'<br><br><div class="text-end">'. $created2 .'</div>
                      </div>
                      </div>
                      </div>');
                    }
                  }
                }
              echo("</div>");
            }
            echo('
            <div class="row no-gutter">
                <div class="col-md-12">
                    <div class="login d-flex align-items-center pt-5">
                        <div class="container-fluid mt-5 mb-3">
                            <div class="row d-flex align-items-center justify-content-center">
                                <div class="col-12 col-sm-10 col-md-9 col-lg-7 col-xl-6">
                                    <form class="px-2 py-2 px-md-4 py-md-4" action="ticket_r.php?id='. $_GET['id'] .'" method="POST">
                                        <div class="form-group">
                                            <textarea class="form-control" id="answer_textarea" rows="8" name="answer" id="answer"></textarea>
                                        </div>
                                        <div class="form-check text-center pt-3">
                                            <input class="form-check-input" type="checkbox" name="handling_by" id="handleCheckbox" style="float: none; margin-right: 4px;" '. $checked .'>
                                            <label class="form-check-label" for="handleCheckbox" style="color: white;">
                                                I can handle this report.
                                            </label>
                                        </div>
                                        <div class="text-center">
                                            <button class="btn btn-primary mt-4 signup" type="submit" style="width: 150px;">Answer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ');
            echo('</div>');
        ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = $_SESSION['user_id'];
    $sender_id = $admin_id;
    $message = $_POST['answer'];
    if (isset($_POST['handling_by'])) {
        $handling_by = $admin_id;
    }
    else {
        $handling_by = NULL;
    }


    if (isset($message) && isset($admin_id)) {

        if (strlen($message) < 20) {
            echo('
                <div class="row text-center d-flex align-items-center justify-content-center pb-3">
                    <div class="alert alert-fixed alert-danger alert-dismissible fade show text-center" role="alert">
                    <strong>Answer is too short, give more details.</strong>.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>');
            echo("<script> window.scrollTo(0, document.body.scrollHeight); </script>");
        }
        else {
            $stmt = $conn->prepare("INSERT INTO `messages` (message, sender_id, admin_id, report_id) VALUES(?,?,?,?)");
            $stmt->bind_param("siii", $message, $sender_id, $admin_id, $_GET['id']);
            $stmt->execute();

            $currentTime = date("Y.m.d H:i:s");
            $status = 0;

            $stmt2 = $conn->prepare("UPDATE `reports` SET last_updated = ?, receiver = ?, handling_by = ?, status = ? WHERE id = ?");
            $stmt2->bind_param("siiii", $currentTime, $admin_id, $handling_by, $status, $_GET['id']);
            $stmt2->execute();
            
            // Adding statistics depending on action
            if($handling_by != NULL) {
                $stmt3 = $conn->prepare("UPDATE `statistics` SET reports_done = reports_done + 1, reports_handled = reports_handled + 1 WHERE mod_id = ?");
                $stmt3->bind_param("i", $_SESSION['user_id']);
                $stmt3->execute();
            }
            else {
                $stmt3 = $conn->prepare("UPDATE `statistics` SET reports_done = reports_done + 1 WHERE mod_id = ?");
                $stmt3->bind_param("i", $_SESSION['user_id']);
                $stmt3->execute();
            }

            header('Location: tickets.php');
        }
    }
    exit();
}
?>