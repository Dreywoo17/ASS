<?php
require 'C:\xampp\htdocs\itproject\DBconnect\Accounts\overall.php';

if (isset($_GET['department_name'])) {
    $dept = $_GET['department_name'];

    $statement = $conn->prepare("SELECT teacher_name FROM teacher WHERE department_name = ?");
    $statement->bind_param("s", $dept);
    $statement->execute();
    $result = $statement->get_result();

    $instructors = [];
    while ($row = $result->fetch_assoc()) {
        $instructors[] = $row['teacher_name'];
    }

    echo json_encode($instructors);
}
?>
