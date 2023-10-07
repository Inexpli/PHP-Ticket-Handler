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

  if(!isset($_SESSION['admin']) && !isset($_SESSION['redirected'])) {
    $_SESSION['redirected'] = true;
    header('Location: dashboard.php');
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
                        <a href='stats.php' class='nav_link active'> <i class='bx bx-bar-chart-alt-2 nav_icon'></i> <span
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

            $stmt = $conn->prepare("SELECT * FROM `statistics`");
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0) {
                $reports_done = array();
                $mods = array();
                while ($row = $result->fetch_assoc()) {
                    $reports_done[] = $row['reports_done'];
                    $reports_handled[] = $row['reports_handled'];
                    $clients_added[] = $row['clients_added'];
                    $mod_id = $row['mod_id'];
                    $stmt2 = $conn->prepare("SELECT * FROM `clientdb` WHERE client_id = ?");
                    $stmt2->bind_param("i", $mod_id);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    $first_name = '';
                    $last_name = '';  
                    while ($row2 = $result2->fetch_assoc()) {
                        $first_name = $row2['first_name'];
                        $last_name = $row2['last_name'];    
                    }
                    $mod = $first_name . ' ' . $last_name;
                    $mods[] = $mod;
                }
            unset($result);
            }

        ?>
        <div class="row pt-5 pb-5">
            <div class="col-6">
                <div>
                    <canvas id="reports"></canvas>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
                <script>
                    const rtx = document.getElementById('reports');
                    const reports_done = <?php echo json_encode($reports_done); ?>;
                    const mods = <?php echo json_encode($mods); ?>;
                    new Chart(rtx, {
                        type: 'bar',
                        data: {
                        labels: mods,
                        datasets: [{
                            label: '# of Reports done',
                            data: reports_done,
                            borderWidth: 1,
                            borderColor: ['#4723D9'],
                        }]
                        },
                        options: {
                        scales: {
                            y: {
                            beginAtZero: true
                            }
                        }
                        }
                    });
                </script>
            </div>
            <div class="col-6">
                <div>
                    <canvas id="handled"></canvas>
                </div>
                <script>
                    const htx = document.getElementById('handled');
                    const reports_handled = <?php echo json_encode($reports_handled); ?>;
                    new Chart(htx, {
                        type: 'bar',
                        data: {
                        labels: mods,
                        datasets: [{
                            label: '# of Reports handled',
                            data: reports_handled,
                            borderWidth: 1,
                            borderColor: ['#4723D9'],
                        }]
                        },
                        options: {
                        scales: {
                            y: {
                            beginAtZero: true
                            }
                        }
                        }
                    });
                </script>
            </div>
        </div>
        <div class="row pt-5">
            <div class="col-6">
                <div>
                    <canvas id="clients"></canvas>
                </div>
    
                <script>
                    const ctx = document.getElementById('clients');
                    const clients_added = <?php echo json_encode($clients_added); ?>;
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                        labels: mods,
                        datasets: [{
                            label: '# of Clients added',
                            data: clients_added,
                            borderWidth: 1,
                            borderColor: ['#4723D9'],
                        }]
                        },
                        options: {
                        scales: {
                            y: {
                            beginAtZero: true
                            }
                        }
                        }
                    });
                </script>
            </div>
            <div class="col-6">
                <div>
                    <canvas id="all"></canvas>
                </div>
    
                <script>
                    const atx = document.getElementById('all');
                    new Chart(atx, {
                        type: 'bar',
                        data: {
                        labels: mods,
                        datasets: [{
                            label: '# of Reports done',
                            data: reports_done,
                            borderWidth: 1,
                            borderColor: ['aqua'],
                        }, 
                        {
                            label: '# of Reports handled',
                            data: reports_handled,
                            borderWidth: 1,
                            borderColor: ['blue'],
                        },
                        {
                            label: '# of Clients added',
                            data: clients_added,
                            borderWidth: 1,
                            borderColor: ['purple'],
                        }
                    ]
                        },
                        options: {
                        scales: {
                            y: {
                            beginAtZero: true
                            }
                        }
                        }
                    });
                </script>
            </div>
        </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>