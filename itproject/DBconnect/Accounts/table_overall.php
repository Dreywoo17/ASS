<?php
require('Conn_overall.php');

// Create students table
$sql1 = "CREATE TABLE IF NOT EXISTS students (
    student_id INT PRIMARY KEY AUTO_INCREMENT,
    student_name VARCHAR(100) NOT NULL,
    student_email VARCHAR(100) NOT NULL UNIQUE,
    user_password VARCHAR(255) NOT NULL,
    department_id INT,
    department_name VARCHAR(100) NOT NULL,
    profile_image VARCHAR(255) DEFAULT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$query1 = mysqli_query($conn, $sql1);

// Create departments table
$sql2 = "CREATE TABLE IF NOT EXISTS departments (
    department_id INT PRIMARY KEY AUTO_INCREMENT,
    department_name VARCHAR(100) NOT NULL UNIQUE
)";
$query2 = mysqli_query($conn, $sql2);

// Insert sample departments
$departments = [
    'Computer Studies', 'Education', 'Business and Accountancy', 'Maritime Education',
    'Criminology', 'Engineering', 'Health and Sciences', 'Art and Sciences'
];
foreach ($departments as $dept) {
    $insert = "INSERT IGNORE INTO departments (department_name) VALUES ('$dept')";
    mysqli_query($conn, $insert);
}

// Create teacher table
$sql3 = "CREATE TABLE IF NOT EXISTS teacher (
    teacher_id INT PRIMARY KEY AUTO_INCREMENT,
    teacher_name VARCHAR(100) NOT NULL,
    teacher_email VARCHAR(100) NOT NULL UNIQUE,
    user_password VARCHAR(255) NOT NULL,
    department_id INT,
    department_name VARCHAR(100) NOT NULL,
    profile_image VARCHAR(255) DEFAULT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
)";
$query3 = mysqli_query($conn, $sql3);

// Create admin table
$sql4 = "CREATE TABLE IF NOT EXISTS admin (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    admin_name VARCHAR(100) NOT NULL,
    admin_email VARCHAR(100) NOT NULL UNIQUE,
    user_password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) DEFAULT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$query4 = mysqli_query($conn, $sql4);

// Create uploaded_images table
$sql5 = "CREATE TABLE IF NOT EXISTS uploaded_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    image_name VARCHAR(255) NOT NULL,
    image_type VARCHAR(100) NOT NULL,
    image_data VARCHAR(255) NOT NULL
)";
$query5 = mysqli_query($conn, $sql5);

// Create user_types table
$sql6 = "CREATE TABLE IF NOT EXISTS user_types (
    id INT AUTO_INCREMENT PRIMARY KEY,  
    type_name VARCHAR(50) NOT NULL,     
    description TEXT,                   
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  
)";
$query6 = mysqli_query($conn, $sql6);


$sql7 = "CREATE TABLE IF NOT EXISTS appointmentdb (
    ID INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    student_ID VARCHAR(15) NOT NULL,
    student_name VARCHAR(80) NOT NULL,
    teacher_name VARCHAR(80) NOT NULL,  
    department_name VARCHAR(80) NOT NULL,
    section VARCHAR(10) NOT NULL, 
    appointment_date DATETIME NOT NULL, 
    Description VARCHAR(255) NOT NULL,
    Status ENUM('Pending', 'Accepted', 'Ongoing', 'Completed', 'Cancelled') DEFAULT 'Pending',
    user_ID INT NOT NULL,
    user_type ENUM('student', 'teacher', 'admin') NOT NULL
)";
$query7 = mysqli_query($conn, $sql7);

// Optional success feedback
// if ($query1 && $query2 && $query3 && $query4 && $query5 && $query6 && $query7) {
//     echo "All tables created successfully.";
// } else {
//     echo "Error in table creation.";
// }
?>
