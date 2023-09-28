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
                $stmt = $conn->prepare("SELECT * FROM `users` WHERE is_admin = 0 ORDER BY is_mod DESC");
                $stmt->execute();
                $result = $stmt->get_result();
                echo('<table class="table table-dark">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">First name</th>
                        <th scope="col">Last name</th>
                        <th scope="col">PESEL</th>
                        <th scope="col">Login</th>
                        <th scope="col">Email</th>
                    </tr>
                    </thead>
                    <tbody>'
                );
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $login = $row['login'];
                    $email = $row['email'];
                    $is_mod = $row['is_mod'];
                    $stmt2 = $conn->prepare("SELECT * FROM `clientdb` WHERE client_id = ?");
                    $stmt2->bind_param("i", $id);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    while ($row2 = $result2->fetch_assoc()) {
                        $pesel = $row2['pesel'];
                        $first_name = $row2['first_name'];
                        $last_name = $row2['last_name'];
                    }
                    if($is_mod == 1) {
                        echo('
                            <tr>
                                <th scope="row" style="color: yellow;">'. $id .'</th>
                                <td style="color: yellow;">'. $first_name .'</td>
                                <td style="color: yellow;">'. $last_name .'</td>
                                <td style="color: yellow;">'. $pesel .'</td>
                                <td style="color: yellow;">'. $login .'</td>
                                <td style="color: yellow;">'. $email .'</td>
                            </tr>');
                    }
                    else {
                        echo('
                            <tr>
                                <th scope="row">'. $id .'</th>
                                <td>'. $first_name .'</td>
                                <td>'. $last_name .'</td>
                                <td>'. $pesel .'</td>
                                <td>'. $login .'</td>
                                <td>'. $email .'</td>
                            </tr>');
                    }
                }
                echo('</tbody></table>');
            }
            else {
                $stmt = $conn->prepare("SELECT * FROM `users` WHERE is_mod = 0 AND is_admin = 0");
                $stmt->execute();
                $result = $stmt->get_result();
                echo('<table class="table table-dark">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">First name</th>
                        <th scope="col">Last name</th>
                        <th scope="col">PESEL</th>
                        <th scope="col">Login</th>
                        <th scope="col">Email</th>
                    </tr>
                    </thead>
                    <tbody>'
                );
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $login = $row['login'];
                    $email = $row['email'];
                    $stmt2 = $conn->prepare("SELECT * FROM `clientdb` WHERE client_id = ?");
                    $stmt2->bind_param("i", $id);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    while ($row2 = $result2->fetch_assoc()) {
                        $pesel = $row2['pesel'];
                        $first_name = $row2['first_name'];
                        $last_name = $row2['last_name'];
                    }
                    echo('
                        <tr>
                            <th scope="row">'. $id .'</th>
                            <td>'. $first_name .'</td>
                            <td>'. $last_name .'</td>
                            <td>'. $pesel .'</td>
                            <td>'. $login .'</td>
                            <td>'. $email .'</td>
                        </tr>');
                }
                echo('</tbody></table>');
            }
        ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>