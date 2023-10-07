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

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !(isset($_POST['search']))) {
        $id = $_POST['id'];
        $mycheck = $_POST['mycheck'];
    
        if ($mycheck === 'true') {
            $stmt = $conn->prepare("UPDATE `users` SET is_mod = 1 WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $stmt2 = $conn->prepare("INSERT INTO `statistics` (mod_id,reports_done,reports_handled,clients_added) VALUES (?,0,0,0)");
            $stmt2->bind_param("i", $id);
            $stmt2->execute();
        }
        else {
            $stmt = $conn->prepare("UPDATE `users` SET is_mod = 0 WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            $stmt2 = $conn->prepare("DELETE FROM `statistics` WHERE mod_id = ?");
            $stmt2->bind_param("i", $id);
            $stmt2->execute();
        }
    }  
    
?>

<style>
    @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap");

    #savedButton {
      display: none;
      opacity: 0;
      transition: opacity 0.3s ease-in-out;
    }
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
    <div class="row pb-4 pt-3">
        <div class="col-5"></div>
        <div class="col-5">
        </div>
        <div class="col-2" style="max-width: 300px;">
            <div class="content">
                <form action="dashboard.php" method="POST">
                    <div class="input-group">
                        <input class="form-control" type="number" name="pesel" id="pesel" placeholder="Enter PESEL">
                        <button class="btn btn-primary" type="submit" name="search"><i
                            class='bx bx-search-alt nav_icon'></i></button>
                    </div>
                <form>
            </div>
        </div>
    </div>
        <form action="dashboard.php" method="POST">
        <?php
            if(isset($_SESSION['admin']) && (isset($_POST['search']))) {

                if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST['search']))) {
                    $pesel = $_POST['pesel'];

                    echo('<table class="table table-dark">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">First name</th>
                        <th scope="col">Last name</th>
                        <th scope="col">PESEL</th>
                        <th scope="col">Login</th>
                        <th scope="col">Email</th>
                        <th scope="col">Moderator</th>
                    </tr>
                    </thead>
                    <tbody>');
                
                    if (!empty($pesel)) {
                        $stmt = $conn->prepare("SELECT * FROM `clientdb` WHERE pesel = ?");
                        $stmt->bind_param("i", $pesel);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            $user_id = $row['client_id'];
                            $pesel = $row['pesel'];
                            $first_name = $row['first_name'];
                            $last_name = $row['last_name'];

                            $stmt2 = $conn->prepare("SELECT * from `users` WHERE id = ?");
                            $stmt2->bind_param("i", $user_id);
                            $stmt2->execute();
                            $result2 = $stmt2->get_result();
                            while ($row2 = $result2->fetch_assoc()) {
                                $id = $row2['id'];
                                $login = $row2['login'];
                                $email = $row2['email'];
                                $is_mod = $row2['is_mod'];
                                if($is_mod == 1) {
                                    $checked = "checked";
                                }
                                else {
                                    $checked = "";
                                }
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
                                        <td style="color: yellow;">
                                        <div class="form-check">
                                        <input class="form-check-input" id="modCheckbox'.$id.'" type="checkbox" name="mod"'. $checked .' onclick="toUpdate('. $id .')">
                                        </div>
                                        </td>
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
                                        <td>
                                        <div class="form-check">
                                        <input class="form-check-input" id="modCheckbox'.$id.'" type="checkbox" name="mod"'. $checked .' onclick="toUpdate('. $id .')">
                                        </div>
                                        </td>
                                    </tr>');
                            }
                        }
                        echo('</tbody></table>');
                    }
                }
            }
            
            else if(isset($_SESSION['admin'])) {
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
                        <th scope="col">Moderator</th>
                    </tr>
                    </thead>
                    <tbody>'
                );
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $login = $row['login'];
                    $email = $row['email'];
                    $is_mod = $row['is_mod'];
                    if($is_mod == 1) {
                        $checked = "checked";
                    }
                    else {
                        $checked = "";
                    }
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
                                <td style="color: yellow;">
                                <div class="form-check">
                                <input class="form-check-input" id="modCheckbox'.$id.'" type="checkbox" name="mod"'. $checked .' onclick="toUpdate('. $id .')">
                                </div>
                                </td>
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
                                <td>
                                <div class="form-check">
                                <input class="form-check-input" id="modCheckbox'.$id.'" type="checkbox" name="mod"'. $checked .' onclick="toUpdate('. $id .')">
                                </div>
                                </td>
                            </tr>');
                    }
                }
                echo('</tbody></table>');
            }
            else {
                if((($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST['search'])))) {
                    $pesel = $_POST['pesel'];

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
                
                    if (!empty($pesel)) {
                        $stmt = $conn->prepare("SELECT * FROM `clientdb` WHERE pesel = ?");
                        $stmt->bind_param("i", $pesel);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            $user_id = $row['client_id'];
                            $pesel = $row['pesel'];
                            $first_name = $row['first_name'];
                            $last_name = $row['last_name'];

                            $stmt2 = $conn->prepare("SELECT * from `users` WHERE id = ?");
                            $stmt2->bind_param("i", $user_id);
                            $stmt2->execute();
                            $result2 = $stmt2->get_result();
                            while ($row2 = $result2->fetch_assoc()) {
                                $id = $row2['id'];
                                $login = $row2['login'];
                                $email = $row2['email'];
                                $is_mod = $row2['is_mod'];
                                $is_admin = $row2['is_admin'];
                            }
                            if(($is_mod != 1)&&($is_admin != 1)) {
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
                }
                else 
                {
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
            }
        ?>
            <button class="btn btn-primary m-4" style="display:none; position: fixed; bottom: 15px; right: 15px;" id="saved">Saved...</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <script>

    function handleCheckboxChange() {

      const checkboxes = document.querySelectorAll('input[type="checkbox"]');


      let hasChanges = false;


      checkboxes.forEach(checkbox => {
        if (checkbox.checked !== checkbox.defaultChecked) {
          hasChanges = true;
        }
      });

      const savedBtn = document.getElementById('saved');
      if (hasChanges) {
      setTimeout(() => {
        savedBtn.style.display = 'block';
      }, 300);
      savedBtn.style.opacity = 1;

      setTimeout(() => {
        savedBtn.style.opacity = 0;
        setTimeout(() => {
          savedBtn.style.display = 'none';
        }, 300);

        location.reload();
      }, 2000);
        } else {
        savedBtn.style.opacity = 0;
        setTimeout(() => {
            savedBtn.style.display = 'none';
        }, 300);
        }
    }

    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
      checkbox.addEventListener('change', handleCheckboxChange);
    });

    function toUpdate(id) {
        const mycheck = document.getElementById(`modCheckbox${id}`).checked;

        fetch('dashboard.php', {
            method: 'POST',
            headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            },
        body: `id=${id}&mycheck=${mycheck}`,
        })
        .then(response => response.text());
    }
  </script>
</body>

<script>
$('#pesel').keydown(function(e) {
    if (this.value.length > 10) 
        if ( !(e.which == '46' || e.which == '8' || e.which == '13') ) // backspace/enter/del
            e.preventDefault();
});
</script>

</html>


