<?php
session_start();

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__ . '\project\config.php');

if((isset($_SESSION['mod']) && $_SESSION['mod'] == True) || (isset($_SESSION['admin']) && $_SESSION['admin'] == True)) {
    header('Location: panel/dashboard.php');
    exit; 
}

if (isset($_SESSION['username'])) {
    header('Location: home.php');
    exit; 
}
?>

<!doctype html>
<html lang="en">
  <head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/main.css">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  </head>
<body>
<div class="container-fluid">
    <a href="javascript:history.back()">
      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-left m-3 mt-4 text-p" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
      </svg>
    </a>
    <div class="row no-gutter">
        <div class="col-md-12">
            <div class="login d-flex align-items-center py-5">
                <div class="container mt-5 mb-5">
                    <div class="row d-flex align-items-center justify-content-center">
                        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                            <form class="card px-5 py-5" action="register.php" method="POST">
                                <h3 class="mb-2 font-weight-bold text-center pb-4">Create an account</h3>
                                <div class="form-input pb-2">
                                    <input class="form-control" type="text" placeholder="Login" aria-label="login input" name="login">
                                </div>
                                <div class="form-input pb-2">
                                    <input class="form-control" type="text" placeholder="Email" aria-label="email input" name="email">
                                </div>
                                <div class="form-input pb-2">
                                    <input class="form-control" type="number" placeholder="Pesel" aria-label="pesel input" name="pesel" maxlength="11" id="pesel">
                                </div>
                                <div class="form-input pb-2">
                                    <input class="form-control" type="password" placeholder="Password" aria-label="password input" name="password">
                                </div>
                                <div class="form-input pb-2">
                                    <input class="form-control" type="password" placeholder="Confirm password" aria-label="confirm password input" name="password_r">
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-primary mt-4 signup" type="submit" style="width: 150px;">Register</button>
                                </div>
                                <div class="text-center mt-4"> <span>Already have an account?</span> <a href="login.php" class="text-decoration-none text-p">Login</a> </div>
                            </form>
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

<script>
$('#pesel').keydown(function(e) {
    if (this.value.length > 10) 
        if ( !(e.which == '46' || e.which == '8' || e.which == '13') ) // backspace/enter/del
            e.preventDefault();
});
</script>

<?php 

$errors = array(); // Initialize the errors array

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $email = $_POST['email'];
    $pesel = $_POST['pesel'];
    $password1 = $_POST['password'];
    $password2 = $_POST['password_r'];
    $epassword = password_hash($password1, PASSWORD_DEFAULT);

    if (isset($login) && isset($email) && isset($pesel) && isset($password1) && isset($password2)) {
        // Prepare statements for validation
        $login_stmt = $conn->prepare("SELECT * FROM users WHERE login = ?");
        $login_stmt->bind_param("s", $login);
        $login_stmt->execute();
        $login_result = $login_stmt->get_result();

        $email_stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $email_stmt->bind_param("s", $email);
        $email_stmt->execute();
        $email_result = $email_stmt->get_result();

        $pesel_stmt = $conn->prepare("SELECT * FROM clientdb WHERE pesel = ?");
        $pesel_stmt->bind_param("s", $pesel);
        $pesel_stmt->execute();
        $pesel_result = $pesel_stmt->get_result();

        // Validation checks...
        
        if (count(array_filter($_POST)) != count($_POST)) {
            $errors[] = "• Fill in all fields";
        }
        // Other validation checks...
        else {
            if (mysqli_num_rows($login_result) > 0) {
                $errors[] = "• This username already exists";
            }
            if (strlen($login) < 3) {
                $errors[] = "• Username has to be longer than 2 characters";
            }
            if (mysqli_num_rows($email_result) > 0) {
                $errors[] = "• This email is already taken";
            }
            if (mysqli_num_rows($pesel_result) <= 0) {
                $errors[] = "• Client with this pesel doesn't exist, consider pesel validation";
            }
        }
        // Other validation checks...

        if (empty($errors)) {
            $stmt = $conn->prepare("INSERT INTO `users` (login,password,email) VALUES(?,?,?)");
            $stmt->bind_param("sss", $login, $epassword, $email);
            $stmt->execute();

            $stmt2 = $conn->prepare("SELECT id FROM `users` WHERE login = ?");
            $stmt2->bind_param("s", $login);
            $stmt2->execute();
            $result = $stmt2->get_result();
            $row = $result->fetch_assoc();
            $client_ID = $row['id'];

            $stmt3 = $conn->prepare("UPDATE `clientdb` SET client_id = ? WHERE pesel = ?");
            $stmt3->bind_param("is", $client_ID, $pesel);
            $stmt3->execute();

            header('Location: login.php');
            exit(); // Make sure to exit after redirection
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
}
?>