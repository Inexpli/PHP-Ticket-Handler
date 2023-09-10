<?php
session_start();

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'\project\config.php');

if(isset($_SESSION['staff']) && $_SESSION['staff'] == True) {
  header('Location: panel/dashboard.php');
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
    <script src="js/script.js"></script>
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/main.css">
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
      <div class="row g-0">
          <div class="col-md-12">
              <div class="login d-flex align-items-center py-5">
                  <div class="container mt-5 mb-5">
                      <div class="row d-flex align-items-center justify-content-center">
                          <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                              <form class="card px-5 py-5" action="login.php" method="post">
                                  <h3 class="mb-2 font-weight-bold text-center pb-4">Login to an account</h3>
                                  <div class="row">
                                    <div class="col-12">
                                      <div class="form-input pb-2">
                                        <input class="form-control" type="text" placeholder="Login" aria-label="login input" name="login">
                                      </div>
                                    </div>
                                    <div class="col-12">
                                      <div class="form-input pb-2">
                                        <input class="form-control" type="password" placeholder="Password" aria-label="password input" name="password">
                                      </div> 
                                    </div>
                                    <div class="text-center">
                                        <button class="btn btn-primary mt-4 signup" type="submit" style="width: 150px;">Login</button>
                                    </div>
                                    <div class="text-center mt-4"> <span>Don't have an account yet?</span> <a href="register.php" class="text-decoration-none text-p">Register</a> </div>
                                    <div class="text-center mt-4"><a href="#" class="text-p">Forgot password?</a></div>
                                  </div>
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
    $password = $_POST['password'];
    
    if (empty($login) || empty($password)) {
        echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
            <strong>Fill in empty fields</strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        exit();
    }

    $stmt = $conn->prepare("SELECT id, password, is_staff FROM `users` WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $login;
            $_SESSION['user_id'] = $row['id'];

            if ($row['is_staff'] == 1) {
                $_SESSION['staff'] = True;
                header('Location: panel/dashboard.php');
            } else {
                $_SESSION['staff'] = False;
                header('Location: home.php');
            }
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                <strong>Incorrect login or password</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
            <strong>Incorrect login or password</strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }

    exit();
}
?>
