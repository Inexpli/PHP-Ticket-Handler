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
    // Navbar script
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
    /* Font */
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
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $(".p-card").click(function() {
                $(".panel-cards").load("issues.php");
            });
        }) ;
    </script>
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
                        href="tickets.php" class="nav_link"> <i class='bx bx-message-square-detail nav_icon'></i>
                        <span class="nav_name">Reports</span> </a>
                        <a href="formulas.php" class="nav_link active"> <i
                            class='bx bx-bookmark nav_icon'></i> <span class="nav_name">Formulas</span> </a> <a href="#"
                        class="nav_link"> <i class='bx bx-folder nav_icon'></i> <span class="nav_name">Files</span> </a>
                        
                    </div>
            </div> <a href="../logout.php" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span
                    class="nav_name">Sign out</span> </a>
        </nav>
    </div>
    <div class="height-90 bg-dark" id="main-body" style="color: white">
        <div class="row no-gutter">
            <div class="col-md-12">
                <div class="login d-flex align-items-center py-5">
                    <div class="container mt-5 mb-5">
                        <div class="row d-flex align-items-center justify-content-center">
                            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                                <div class="panel-cards text-center" style="padding-top: 30%;">
                                    <div class="row mb-5">
                                        <div class="col-1"></div>
                                        <div class="col-4 p-card p-4">
                                            <div class="row">
                                                <div class="col-12 pb-3">
                                                    <i class='bx bx-bug nav_icon' style="font-size: 30px;"></i>
                                                </div>
                                                <div class="col-12">
                                                    Technical Issues
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1"></div>
                                        <div class="col-4 p-card p-4">
                                            <div class="row">
                                                <div class="col-12 pb-3">
                                                    <i class='bx bx-credit-card nav_icon' style="font-size: 30px;"></i>
                                                </div>
                                                <div class="col-12">
                                                    Transaction
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1"></div>
                                    </div>
                                    <div class="row">
                                    <div class="col-1"></div>
                                        <div class="col-4 p-card p-4">
                                            <div class="row">
                                                <div class="col-12 pb-3">
                                                    <i class='bx bx-money-withdraw nav_icon' style="font-size: 30px;"></i>
                                                </div>
                                                <div class="col-12">
                                                    Refunds
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1"></div>
                                        <div class="col-4 p-card p-4">
                                            <div class="row">
                                                <div class="col-12 pb-3">
                                                    <i class='bx bx-folder nav_icon' style="font-size: 30px;"></i>
                                                </div>
                                                <div class="col-12">
                                                    Others
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
$('#pesel').keydown(function(e) {
    if (this.value.length > 10) 
        if ( !(e.which == '46' || e.which == '8' || e.which == '13') ) // backspace/enter/del
            e.preventDefault();
});
</script>

</html>


