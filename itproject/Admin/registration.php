<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'C:\xampp\htdocs\itproject\DBconnect\Accounts\overall.php';
$feedback = '';
$targetDir = "uploads/";

// Example departments
$departments = ['Department 1', 'Department 2', 'Department 3'];

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

        move_uploaded_file($tmpName, $imagePath);
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
                $sql = "INSERT INTO students (student_name, student_email, user_password, profile_image) VALUES (?, ?, ?, ?)";
                $statement = $conn->prepare($sql);
                $statement->bind_param("ssss", $name, $email, $hashed_password, $imagePath);
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
                // Redirect to login page on success
                header("Location: /itproject/Login/login.php?msg=registered");
                exit();
            } else {
                $feedback = "<div class='alert alert-danger text-center'>Error: " . ($statement ? $statement->error : 'Unknown error') . "</div>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/itproject/Admin/Asset/registration.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .navbar {
            margin-bottom: 30px;
        }

        .container {
            max-width: 600px;
        }

        .card {
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-label i {
            margin-right: 8px;
        }

        .footer {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
            margin-top: 40px;
        }
    </style>
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
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="card p-4">
            <?php echo $feedback; ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                <div class="header text-center mb-4">
                    <h2 class="text-dark">Appointment Scheduling System</h2>
                    <p>Register for an account</p>
                </div>

                <!-- Profile Image Upload -->
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-image"></i> Upload Profile Image</label>
                    <input type="file" class="form-control" name="profile_image" accept="image/*">
                    <small class="text-muted">Max size: 2MB (Optional)</small>
                </div>

                <!-- User Type Selection -->
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-user-circle"></i> User Type</label>
                    <select name="user_type" class="form-control" required onchange="this.form.submit()">
                        <option value="">Select User Type</option>
                        <option value="Student" <?php echo isset($user_type) && $user_type == 'Student' ? 'selected' : ''; ?>>Student</option>
                        <option value="Teacher" <?php echo isset($user_type) && $user_type == 'Teacher' ? 'selected' : ''; ?>>Teacher</option>
                        <option value="Admin" <?php echo isset($user_type) && $user_type == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                    <noscript><input type="submit" value="Update Form" class="btn btn-secondary mt-2"></noscript>
                </div>

                <!-- Full Name Input -->
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-user"></i> Full Name</label>
                    <input type="text" class="form-control" name="name" required value="<?php echo isset($name) ? $name : ''; ?>" placeholder="Enter your full name">
                </div>

                <!-- CU Corporate Email Input -->
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-envelope"></i> CU Corporate Email</label>
                    <input type="email" class="form-control" name="email" required value="<?php echo isset($email) ? $email : ''; ?>" placeholder="e.g. name@g.cu.edu.ph">
                    <small class="text-muted">Please use your CU corporate email (e.g., name@g.cu.edu.ph).</small>
                </div>

                <!-- Password Input -->
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" class="form-control" name="password" required placeholder="Enter your password">
                    <small class="text-muted">Your password must be at least 8 characters long.</small>
                </div>

                <!-- Confirm Password Input -->
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-lock"></i> Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password" required placeholder="Confirm your password">
                </div>

                <!-- Department Selection (For Teachers) -->
                <?php if (isset($user_type) && $user_type == "Teacher"): ?>
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-building"></i> Department</label>
                    <select name="department" class="form-control">
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $dep): ?>
                            <option value="<?php echo $dep; ?>" <?php echo isset($department) && $department == $dep ? 'selected' : ''; ?>><?php echo $dep; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <!-- Submit and Logout Button -->
                <div class="d-flex justify-content-between mt-3">
                    <button type="submit" class="btn btn-success">Register</button>
                    <a href="/itproject/Login/login.php" class="btn btn-danger">Log out</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        <p>&copy; 2025 Appointment Scheduling System. All rights reserved.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
