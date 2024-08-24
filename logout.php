<?php
    session_start();
    $_SESSION = array(); // Clear all session variables by assigning an empty array to $_SESSION

    // Clear session cookies if they are being used
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params(); // Get the session cookie parameters
        setcookie(
            session_name(),    // Cookie name is set to the current session name
            '',                // Empty value indicates the cookie should be deleted
            time() - 42000,    // Set the expiration time in the past to delete the cookie
            $params["path"],   // Path for the cookie
            $params["domain"], // Domain for the cookie
            $params["secure"], // If true, the cookie will only be sent over secure connections
            $params["httponly"] // If true, the cookie will be accessible only through the HTTP protocol
        );
    }
    // Destroy the session and redirect the user to index.php
    if(session_destroy()) {
        header("Location: index.php");
        exit;
    }
?>