<?php
session_start();
session_unset(); // Clear all session variables
session_destroy(); // Destroy the session

// Redirect to login page with message
header("Location: login.php?msg=logged_out");
exit();