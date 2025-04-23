<?php
session_start();
require 'C:\xampp\htdocs\itproject\DBconnect\Accounts\Conn_overall.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Sanitize input
    $email = htmlspecialchars($email);
    $password = htmlspecialchars($password);

    // Validate inputs
    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "All fields are required.";
        header('Location: login.php');
        exit();
    }

    if (!preg_match('/@g\.cu\.edu\.ph$/', $email)) {
        $_SESSION['error_message'] = "Please use your CU corporate email (e.g., 2089@g.cu.edu.ph).";
        header('Location: login.php');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Invalid email format.";
        header('Location: login.php');
        exit();
    }

    // Define user types and their corresponding tables
    $user_types = [
        "Student" => ["table" => "students", "email_col" => "student_email", "redirect" => "/itproject/Login/Students/addappoint.php", "id_col" => "student_id", "name_col" => "student_name"],
        "Teacher" => ["table" => "teacher", "email_col" => "teacher_email", "redirect" => "/itproject/Login/Teacher/teacher.php", "name_col" => "teacher_name"],
        "Admin" => ["table" => "admin", "email_col" => "admin_email", "redirect" => "/itproject/Admin/viewadmin.php"]
    ];

    // Check if the email exists in any user type table
    foreach ($user_types as $type => $data) {
        $sql = "SELECT * FROM {$data['table']} WHERE {$data['email_col']} = ?";
        $statement = $conn->prepare($sql);
        $statement->bind_param("s", $email);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Debugging: Print the $user array to verify column names
            var_dump($user);  // Check the fetched data structure

            // Verify password
            if (password_verify($password, $user['user_password'])) {
                // Store user details in session
                $_SESSION['user_id'] = $user['id'];  // Store user ID in session
                $_SESSION['user_type'] = $type;  // Store user type (Student, Teacher, Admin)

                // Store student or teacher info in session
                if ($type == "Teacher") {
                    $_SESSION['teacher_name'] = $user[$data['name_col']];
                } elseif ($type == "Student") {
                    // Adjust this based on the correct column name
                    $_SESSION['student_id'] = $user[$data['id_col']];  // Correct the student ID column name if needed
                    $_SESSION['student_name'] = $user[$data['name_col']];  // Store the student name if needed
                } elseif ($type == "Admin") {
                    $_SESSION['admin_id'] = $user['admin_id'];
                }

                // Redirect to the appropriate page for the user type
                header("Location: " . $data['redirect']);
                exit();
            } else {
                $_SESSION['error_message'] = "Incorrect password.";
                header('Location: login.php');
                exit();
            }
        }
    }

    // If no user was found with that email
    $_SESSION['error_message'] = "No account found with that email.";
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Appointment Scheduling System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

        <?php
        if (isset($_SESSION['error_message'])) {
            echo "<div class='alert alert-danger text-center'>" . $_SESSION['error_message'] . "</div>";
            unset($_SESSION['error_message']);
        }
        ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label class="form-label">CU Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="2089@g.cu.edu.ph" required>
                </div>
                <small id="text">Use only your CU email (e.g., 2089----@g.cu.edu.ph).</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <small id="text">Never share your password with anyone.</small>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-danger">Login</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
