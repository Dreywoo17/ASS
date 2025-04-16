<?php
require 'C:\xampp\htdocs\itproject\DBconnect\Accounts\overall.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];  
    $type = $_GET['type'];

    // Determine the correct table and column for deletion
    if ($type === "student") {
        $table = "students";
        $column = "student_id";
    } elseif ($type === "teacher") {
        $table = "teacher";
        $column = "teacher_id";
    } elseif ($type === "admin") {
        $table = "admin";
        $column = "admin_id";
    } else {
        die("Invalid user type.");
    }

    // Delete query with prepared statement
    $deleteQuery = "DELETE FROM $table WHERE $column = ?";
    $statement = $conn->prepare($deleteQuery);
    $statement->bind_param("i", $id);
    
    if ($statement->execute()) {
        header("Location: /itproject/Admin/viewadmin.php");
        exit();
    } else {
        echo "Error deleting record.";
    }

    $statement->close();
    $conn->close();
}
?>
