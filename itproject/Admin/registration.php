<?php

require 'C:\xampp\htdocs\itproject\DBconnect\Accounts\overall.php';

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'Admin') {
    header("Location: /itproject/Login/login.php");
    exit();
}

require 'C:\xampp\htdocs\itproject\DBconnect\Accounts\overall.php';
$feedback = '';
$targetDir = "uploads/";

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $user_type = $_POST['user_type'] ?? '';
    $department = $_POST['department'] ?? ''; 

    $imagePath = '';
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $imageName = basename($_FILES['profile_image']['name']);
        $imageType = $_FILES['profile_image']['type'];
        $tmpName = $_FILES['profile_image']['tmp_name'];
        $imagePath = $targetDir . $imageName;

     
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2 MB

        if (in_array($imageType, $allowedTypes) && $_FILES['profile_image']['size'] <= $maxFileSize) {
            move_uploaded_file($tmpName, $imagePath);
        } else {
            $feedback = "<div class='alert alert-danger text-center'>Invalid file type or size. Only JPG, PNG, and GIF files are allowed (max 2MB).</div>";
        }
    }

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($user_type)) {
        $feedback = "<div class='alert alert-danger text-center'>All fields are required.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $feedback = "<div class='alert alert-danger text-center'>Invalid email format.</div>";
    } elseif ($password !== $confirm_password) {
        $feedback = "<div class='alert alert-danger text-center'>Passwords do not match.</div>";
    } elseif (!preg_match('/@g\.cu\.edu\.ph$/', $email)) {
        $feedback = "<div class='alert alert-danger text-center'>Please use your CU corporate email.</div>";
    } else {
        $check_sql = "
            SELECT email FROM (
                SELECT student_email AS email FROM students
                UNION
                SELECT teacher_email AS email FROM teacher
                UNION
                SELECT admin_email AS email FROM admin
            ) AS all_users
            WHERE email = ?";
        $check_statement = $conn->prepare($check_sql);
        $check_statement->bind_param("s", $email);
        $check_statement->execute();
        $check_statement->store_result();

        if ($check_statement->num_rows > 0) {
            $feedback = "<div class='alert alert-danger text-center'>Email is already registered.</div>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            if ($user_type == "Student") {
                $sql = "INSERT INTO students (student_name, student_email, user_password, department_name, profile_image) VALUES (?, ?, ?, ?, ?)";
                $statement = $conn->prepare($sql);
                $statement->bind_param("sssss", $name, $email, $hashed_password, $department, $imagePath);
            } elseif ($user_type == "Teacher") {
                if (empty($department)) {
                    $feedback = "<div class='alert alert-danger text-center'>Department is required for teachers.</div>";
                    $statement = null;  
                } else {
                    $sql = "INSERT INTO teacher (teacher_name, teacher_email, user_password, department_name, profile_image) VALUES (?, ?, ?, ?, ?)";
                    $statement = $conn->prepare($sql);
                    $statement->bind_param("sssss", $name, $email, $hashed_password, $department, $imagePath);
                }
            } elseif ($user_type == "Admin") {
                $sql = "INSERT INTO admin (admin_name, admin_email, user_password, profile_image) VALUES (?, ?, ?, ?)";
                $statement = $conn->prepare($sql);
                $statement->bind_param("ssss", $name, $email, $hashed_password, $imagePath);
            }

            if ($statement && $statement->execute()) {
                $feedback = "<div class='alert alert-success text-center'>Registration successful!</div>";
            } else {
                $feedback = "<div class='alert alert-danger text-center'>Error: " . $statement->error . "</div>";
            }

            if ($statement) {
                $statement->close();
            }
        }
        $check_statement->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/itproject/Admin/Asset/registration.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="#">
        <img src="../img/Alogo1.jpg" alt="Logo"> Appointment Scheduling
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link text-white" href="/itproject/aboutus.php">About Us</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/itproject/Login/login.php">Log in</a></li>
        </ul>
    </div>
</nav>

<div class="container"><br><br>
    <div class="card p-4">
        <?php echo $feedback; ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
            <div class="header">
                <h2 class="text-dark">Appointment Scheduling System</h2>
                <p>Register for an account</p>
            </div>

            <div class="mb-3">
                <label class="form-label">Upload Profile Image</label>
                <input type="file" class="form-control" name="profile_image" accept="image/*">
            </div>

            <div class="mb-3">
                <label class="form-label">User Type</label>
                <select name="user_type" id="user_type" class="form-control">
                    <option value="">Select User Type</option>
                    <option value="Student">Student</option>
                    <option value="Teacher">Teacher</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>

                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <select name="department" id="department" class="form-control">
                        <option value="">Select Department</option>
                        <option value="Computer Studies">Computer Studies</option>
                        <option value="Education">Education</option>
                        <option value="Business and Accountancy">Business and Accountancy</option>
                        <option value="Maritime Education">Maritime Education</option>
                        <option value="Criminology">Criminology</option>
                        <option value="Engineering">Engineering</option>
                        <option value="Health and Sciences">Health and Sciences</option>
                        <option value="Art and Sciences">Art and Sciences</option>
                    </select>
                </div>

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">CU Corporate Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" required>
            </div>

            <button type="submit" class="btn btn-custom w-100 text-white">Register</button>
            <button type="button" class="btn btn-secondary w-100 text-white" onclick="window.location.href='/itproject/Admin/viewadmin.php'">View Account</button><br>
        </form>
    </div>
</div>

</body>
</html>
