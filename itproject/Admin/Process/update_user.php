<?php
require 'C:\xampp\htdocs\itproject\DBconnect\Accounts\overall.php';

$id = $_POST['id'];
$type = $_POST['type'];
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

switch ($type) {
    case 'student':
        $table = 'students';
        $id_col = 'student_id';
        $name_col = 'student_name';
        $email_col = 'student_email';
        $password_col = 'student_password';
        
        break;
    case 'teacher':
        $table = 'teacher';
        $id_col = 'teacher_id';
        $name_col = 'teacher_name';
        $email_col = 'teacher_email';
        $password_col = 'teacher_password';
        break;
    case 'admin':
        $table = 'admin';
        $id_col = 'admin_id';
        $name_col = 'admin_name';
        $email_col = 'admin_email';
        $password_col = 'admin_password';
        break;
    default:
        http_response_code(400);
        echo "Invalid user type.";
        exit;
}

$statement = $conn->prepare("UPDATE $table SET $name_col = ?, $email_col = ? WHERE $id_col = ?");
$statement->bind_param("ssi", $name, $email, $id);

if ($statement->execute()) {
    echo "Success";
} else {
    http_response_code(500);
    echo "Error: " . $conn->error;
}

$statement->close();
$conn->close();
