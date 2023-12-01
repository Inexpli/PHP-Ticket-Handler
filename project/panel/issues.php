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