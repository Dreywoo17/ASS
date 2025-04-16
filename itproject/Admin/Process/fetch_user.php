<?php
require 'C:\xampp\htdocs\itproject\DBconnect\Accounts\overall.php';

$id = $_GET['id'];
$type = $_GET['type'];

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
        echo json_encode(['error' => 'Invalid type']);
        exit;
}

$statement = $conn->prepare("SELECT $name_col, $email_col FROM $table WHERE $id_col = ?");
$statement->bind_param("i", $id);
$statement->execute();
$statement->bind_result($name, $email);
$statement->fetch();
$statement->close();

echo json_encode(['name' => $name, 'email' => $email]);
$conn->close();
