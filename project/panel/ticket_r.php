<?php
  session_start();

  define('__ROOT__', dirname(dirname(__FILE__)));
  require_once(__ROOT__.'\config.php');

  if(!isset($_SESSION['username'])){
    header('Location: ../login.php');
    exit;
  }

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

            // Validate that all variables exist
            if (toggle && nav && bodypd && headerpd) {
                toggle.addEventListener('click', () => {
                    // show navbar
                    nav.classList.toggle('show')
                    // change icon
                    toggle.classList.toggle('bx-x')
                    // add padding to body
                    bodypd.classList.toggle('body-pd')
                    // add padding to header
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

        // Your code to run since DOM is loaded and ready
    });
</script>

<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = $_SESSION['user_id'];
    $sender_id = $admin_id;
    $message = $_POST['answer'];
    if (isset($message) && isset($admin_id)) {
            $errors = array();

            if (strlen($message) < 20) {
                $errors[] = "Answer is too short, give more details.";
            }

            if (empty($errors)) {

                $stmt = $conn->prepare("INSERT INTO `messages` (message, sender_id, admin_id, report_id) VALUES(?,?,?,?)");
                $stmt->bind_param("siii", $message, $sender_id, $admin_id, $_GET['id']);
                $stmt->execute();

                $currentTime = date("Y-m-d H:i:s");

                $stmt2 = $conn->prepare("UPDATE `reports` SET last_updated = ?, receiver = $admin_id, status = 0 WHERE id = ?");
                $stmt2->bind_param("si", $currentTime, $_GET['id']);
                $stmt2->execute();

                header('Location: tickets.php');

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
            <strong>You cannot send empty answer.</strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }

    exit();
}
?>

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
            <div> <a class="nav_logo" href="dashboard.php"> <i class='bx bx-grid-alt nav_logo-icon'></i> <span
                        class="nav_logo-name">Dashboard</span> </a>
                <div class="nav_list"><a href="clients.php" class="nav_link" id="users"> <i
                            class='bx bx-user nav_icon'></i> <span class="nav_name">Users</span> </a> <a
                        href="tickets.php" class="nav_link active"> <i class='bx bx-message-square-detail nav_icon'></i>
                        <span class="nav_name">Reports</span> </a>
                        <a href="chat.php" class="nav_link"> <i
                            class='bx bx-message nav_icon'></i> <span class="nav_name">Chat</span> </a>
                        <a href="#" class="nav_link"> <i
                            class='bx bx-bookmark nav_icon'></i> <span class="nav_name">Formulas</span> </a> <a href="#"
                        class="nav_link"> <i class='bx bx-folder nav_icon'></i> <span class="nav_name">Files</span> </a>
                    <a href="#" class="nav_link"> <i class='bx bx-bar-chart-alt-2 nav_icon'></i> <span
                            class="nav_name">Stats</span> </a> </div>
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
                echo('
                <div class="row p-3 pt-4 mb-5">
                <div class="col-12 text-center">'. $created .'</div>
                <div class="col-12 text-center">'. $category .'</div>
                <div class="col-12 pb-4 text-center">'. $topic .'</div>
                </div>
                <div class="col-6">
                  <div class="bubble p-2" style="word-wrap: break-word; display: inline-block;">
                  '. $description .'<br><br><div class="text-start">'. $created .'</div>
                  </div>
                </div>
                <div class="col-6"></div>'
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
                      <div class="bubble p-2" style="display: inline-block; word-wrap: break-word; float:right; margin-bottom: 1px;">
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
                    <div class="login d-flex align-items-center py-5">
                        <div class="container-fluid mt-5 mb-5">
                            <div class="row d-flex align-items-center justify-content-center">
                                <div class="col-12 col-sm-10 col-md-9 col-lg-7 col-xl-6">
                                    <form class="px-2 py-2 px-md-4 py-md-4" action="ticket_r.php?id='. $_GET['id'] .'" method="POST">
                                        <div class="form-group">
                                            <textarea class="form-control" id="answer_textarea" rows="8" name="answer" id="answer"></textarea>
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