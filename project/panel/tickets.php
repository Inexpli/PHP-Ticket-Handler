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

<style>
    @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap");
</style>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
                        href="tickets.php" class="nav_link active"> <i class='bx bx-message-square-detail nav_icon'></i>
                        <span class="nav_name">Reports</span> </a>
                        <a href="#" class="nav_link"> <i
                            class='bx bx-bookmark nav_icon'></i> <span class="nav_name">Formulas</span> </a> <a href="#"
                        class="nav_link"> <i class='bx bx-folder nav_icon'></i> <span class="nav_name">Files</span> </a>
                        
                    </div>
            </div> <a href="../logout.php" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span
                    class="nav_name">Sign out</span> </a>
        </nav>
    </div>
    <!--Container Main start-->
    <div class="height-100 bg-dark" id="main-body" style="color: white">
        <?php
            if(isset($_SESSION['admin'])) {
                $stmt = $conn->prepare("SELECT id, category, topic, SUBSTRING_INDEX(description, ' ', 15) AS short_description, handling_by, created, last_updated, status FROM `reports` ORDER BY status DESC");
                $stmt->execute();
                $result = $stmt->get_result();
                echo('<table class="table table-dark">
                    <thead>
                      <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Category</th>
                        <th scope="col">Topic</th>
                        <th scope="col">Description</th>
                        <th scope="col">Handling by</th>
                        <th scope="col">Created</th>
                        <th scope="col">Last updated</th>
                        <th scope="col">Status</th>
                      </tr>
                    </thead>
                    <tbody>'
                );
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $category = $row['category'];
                    $topic = $row['topic'];
                    $description = $row['short_description'];
                    $handling_by = $row['handling_by'];
                    $created = $row['created'];
                    $last_updated = $row['last_updated'];
                    $status = $row['status'];
                    if($status == True) {
                        $status = "Answered";
                    }
                    else {
                        $status = "Waiting...";
                    }
                    if($row['handling_by'] != NULL) {
                        echo('
                        <tr onclick="redirect('. $id .')" style="cursor: pointer;" class="ticketrow">
                          <th scope="row" style="color: yellow;">'. $id .'</th>
                          <td style="color: yellow;">'. $category .'</td>
                          <td style="color: yellow;">'. $topic .'</td>
                          <td style="color: yellow;">'. $description .'...</td>
                          <td style="color: yellow;">'. $handling_by .'</td>
                          <td style="color: yellow;">'. $created .'</td>
                          <td style="color: yellow;">'. $last_updated .'</td>
                          <td style="color: yellow;">'. $status .'</td>
                        </tr>');
                    } else {
                    echo('
                        <tr onclick="redirect('. $id .')" style="cursor: pointer;" class="ticketrow">
                        <th scope="row">'. $id .'</th>
                        <td>'. $category .'</td>
                        <td>'. $topic .'</td>
                        <td>'. $description .'...</td>
                        <td>'. $handling_by .'</td>
                        <td>'. $created .'</td>
                        <td>'. $last_updated .'</td>
                        <td>'. $status .'</td>
                        </tr>');
                    }
                }
                echo('</tbody></table>');
            }
            else {
                $stmt = $conn->prepare("SELECT id, category, topic, SUBSTRING_INDEX(description, ' ', 15) AS short_description, handling_by, created, last_updated FROM `reports` WHERE status = 1 AND handling_by = ? OR handling_by IS NULL ORDER BY last_updated ASC");
                $stmt->bind_param("i", $_SESSION['user_id']); 
                $stmt->execute();
                $result = $stmt->get_result();
                echo('<table class="table table-dark">
                    <thead>
                      <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Category</th>
                        <th scope="col">Topic</th>
                        <th scope="col">Description</th>
                        <th scope="col">Created</th>
                        <th scope="col">Last updated</th>
                      </tr>
                    </thead>
                    <tbody>'
                );
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $category = $row['category'];
                    $topic = $row['topic'];
                    $description = $row['short_description'];
                    $created = $row['created'];
                    $last_updated = $row['last_updated'];
                    if($row['handling_by'] == $_SESSION['user_id']) {
                        echo('
                        <tr onclick="redirect('. $id .')" style="cursor: pointer;" class="ticketrow">
                          <th scope="row" style="color: yellow;">'. $id .'</th>
                          <td style="color: yellow;">'. $category .'</td>
                          <td style="color: yellow;">'. $topic .'</td>
                          <td style="color: yellow;">'. $description .'...</td>
                          <td style="color: yellow;">'. $created .'</td>
                          <td style="color: yellow;">'. $last_updated .'</td>
                        </tr>');
                    } else {
                    echo('
                        <tr onclick="redirect('. $id .')" style="cursor: pointer;" class="ticketrow">
                        <th scope="row">'. $id .'</th>
                        <td>'. $category .'</td>
                        <td>'. $topic .'</td>
                        <td>'. $description .'...</td>
                        <td>'. $created .'</td>
                        <td>'. $last_updated .'</td>
                        </tr>');
                    }
                }
                echo('</tbody></table>');
            }

        ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<script>
    function redirect(id) {
        window.location = `ticket_r.php?id=${id}`;
    }
</script>