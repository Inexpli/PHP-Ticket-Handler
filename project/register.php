<?php
    session_start();
    require_once "config.php";

    if(isset($_SESSION['staff']) && $_SESSION['staff'] == True) {
        header('Location: dashboard.php');
    }

    if(isset($_SESSION['username'])){
        header('Location: home.php');
    }
?>

<!doctype html>
<html lang="en">
  <head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/script.js"></script>
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" rel="stylesheet">
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

<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $login = $_POST['login'];
        $email = $_POST['email'];
        $password1 = $_POST['password'];
        $password2 = $_POST['password_r'];
        $epassword = password_hash($password1, PASSWORD_DEFAULT);
        if(isset($login) && isset($email) && isset($password1) && isset($password2)) {
            $login_valid = mysqli_query($conn, "SELECT * FROM users WHERE login = '".$_POST['login']."'");
            $email_valid = mysqli_query($conn, "SELECT * FROM users WHERE email = '".$_POST['email']."'");
            if(count(array_filter($_POST))!=count($_POST)){
                echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                <strong>Fill in all fields</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
            else if(!preg_match('/^\w{5,}$/', $login)) {
                echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                <strong>Invalid username format. Username should be alphanumeric (only english characters) & longer than or equal to 5 characters</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            }
            else if(mysqli_num_rows($login_valid) > 0) {
                echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                <strong>This username already exists</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            }
            else if(mysqli_num_rows($email_valid) > 0) {
                echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                <strong>This email is already taken</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
            else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                <strong>Invalid email format</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
            else if($password1!=$password2) {
                echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                <strong>Passwords doesn\'t match</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
            else if(strlen($password1) < 8) {
                echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                <strong>Password should have atleast 8 characters</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
            else {
                $stmt = $conn->prepare("INSERT INTO `users` (login,password,email) VALUES(?,?,?)");
                $stmt->bind_param("sss", $login, $epassword, $email);
                $stmt->execute();
                echo '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                <strong>Account has been created, you will be redirected to login panel</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                header('Location: login.php');
            }
        }
        else {
            echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
            <strong>Fill in all fields</strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    }
    exit();
?>