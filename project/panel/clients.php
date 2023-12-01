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
                    <a href="clients.php" class="nav_link active" id="users"> <i
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
    <div class="container-fluid">
        <div class="row no-gutter">
            <div class="col-md-12">
                <div class="login d-flex align-items-center py-5">
                    <div class="container mt-5 mb-5">
                        <div class="row d-flex align-items-center justify-content-center">
                            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                                <form class="card px-5 py-5" action="clients.php" method="POST">
                                    <h3 class="mb-2 font-weight-bold text-center pb-4">Add client</h3>
                                    <div class="form-input pb-2">
                                        <input class="form-control" type="text" placeholder="First name" aria-label="first name input" name="firstname">
                                    </div>
                                    <div class="form-input pb-2">
                                        <input class="form-control" type="text" placeholder="Last name" aria-label="last name input" name="lastname">
                                    </div>
                                    <div class="form-input pb-2">
                                        <input class="form-control" type="number" placeholder="Pesel" aria-label="pesel input" name="pesel" maxlength="11" id="pesel">
                                    </div>
                                    <div class="text-center">
                                        <button class="btn btn-primary mt-4" type="submit" style="width: 150px;">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

<script>
// Limiting the number of input digits
$('#pesel').keydown(function(e) {
    if (this.value.length > 10) 
        if ( !(e.which == '46' || e.which == '8' || e.which == '13') ) // backspace/enter/del
            e.preventDefault();
});
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $pesel = $_POST['pesel'];

    if(isset($firstname) && isset($lastname) && isset($pesel)) {
        // Preparing statements for validation
        $pesel_stmt = $conn->prepare("SELECT * FROM clientdb WHERE pesel = ?");
        $pesel_stmt->bind_param("s", $pesel);
        $pesel_stmt->execute();
        $pesel_result = $pesel_stmt->get_result();

        $errors = array();
        if(count(array_filter($_POST)) != count($_POST)){
            $errors[] = "• Fill in all fields";
        }
        // Validation checks...
        if(mysqli_num_rows($pesel_result) > 0) {
            $errors[] = "• Client with this pesel already exist";
        }

        if (empty($errors)) {
            $stmt = $conn->prepare("INSERT INTO `clientdb` (pesel,first_name,last_name,added_by) VALUES(?,?,?,?)");
            $stmt->bind_param("sssi", $pesel, $firstname, $lastname, $_SESSION['user_id']);
            $stmt->execute();

            $stmt2 = $conn->prepare("UPDATE `statistics` SET clients_added = clients_added + 1 WHERE mod_id = ?");
            $stmt2->bind_param("i", $_SESSION['user_id']);
            $stmt2->execute();
        }
        else {
            echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">';
            echo '<strong>Error(s):</strong><br>';
            foreach ($errors as $error) {
                echo $error . '<br>';
            }
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
        }
    }
    else {
        echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
        <strong>Fill in all fields</strong>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
}
