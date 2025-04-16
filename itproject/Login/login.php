<?php
session_start();
require 'C:\xampp\htdocs\itproject\DBconnect\Accounts\Conn_overall.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $email = htmlspecialchars($email);
    $password = htmlspecialchars($password);

    if (empty($email) || empty($password)) {
        exit("<div class='alert alert-danger text-center'>All fields are required.</div>");
    }

    if (!preg_match('/@g\.cu\.edu\.ph$/', $email)) {
        exit("<div class='alert alert-danger text-center'>Please use your CU corporate email (e.g., you@g.cu.edu.ph).</div>");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        exit("<div class='alert alert-danger text-center'>Invalid email format.</div>");
    }

    $user_types = [
        "Student" => ["table" => "students", "email_col" => "student_email", "redirect" => "/itproject/Login/Students/addappoint.php"],
        "Teacher" => ["table" => "teacher", "email_col" => "teacher_email", "redirect" => "/itproject/Login/Teacher/teacher.php", "name_col" => "teacher_name"],
        "Admin" => ["table" => "admin", "email_col" => "admin_email", "redirect" => "/itproject/Admin/viewadmin.php"]
    ];

    foreach ($user_types as $type => $data) {
        $sql = "SELECT * FROM {$data['table']} WHERE {$data['email_col']} = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['user_password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = $type;
                if ($type == "Teacher") {
                    $_SESSION['teacher_name'] = $user[$data['name_col']];
                }
                header("Location: " . $data['redirect']);
                exit();
            } else {
                echo "<div class='alert alert-danger text-center'>Incorrect password.</div>";
                exit();
            }
        }

        $stmt->close();
    }

    echo "<div class='alert alert-danger text-center'>No account found with that email.</div>";
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Appointment Scheduling System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background-image: url('../img/body.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
        }
        .login-card {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40,167,69,.25);
        }
        .logo {
            width: 60px;
            height: 60px;
        }
        #text {
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="../img/Alogo1.jpg" alt="Logo" class="logo d-inline-block align-middle me-2">
            Appointment Scheduling System
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-white" style="font-family: sans-serif;" href="/itproject/aboutus.php"><h5>About Us</h5></a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container d-flex justify-content-center align-items-center" style="height: 85vh;">
    <div class="login-card" style="width: 360px;">
        <h3 class="text-center mb-3">Welcome Back</h3>
        <p class="text-center text-muted mb-4">Log in to manage your appointments</p>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label class="form-label">CU Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="you@g.cu.edu.ph" required>
                </div>
                <small id="text">Use only your CU email (e.g., name@g.cu.edu.ph).</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <small id="text">Make sure your password is strong and secure.</small>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success">Login</button>
            </div>

            <div class="text-center mt-3">
                <a href="/itproject/Admin/registration.php" class="text-decoration-none" style="font-size: 13px;">Create Account</a>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap Bundle (no JavaScript needed from us) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
