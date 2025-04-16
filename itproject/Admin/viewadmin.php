<?php

session_start();

// Optional: Admin access check (uncomment if needed)
// if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'Admin') {
//     header("Location: /itproject/Login/login.php");
//     exit();
// }

// Include the database connection
require 'C:\xampp\htdocs\itproject\DBconnect\Accounts\overall.php';

// Check if the connection was successful
$students_query = "SELECT * FROM students";
$teachers_query = "SELECT * FROM teacher";
$admins_query = "SELECT * FROM admin";

// Execute the queries
$students_result = $conn->query($students_query);
$teachers_result = $conn->query($teachers_query);
$admins_result = $conn->query($admins_query);

// Check for errors in the queries
if (!$students_result || !$teachers_result || !$admins_result) {
    die("Error: " . $conn->error);
}


$selected_role = isset($_POST['role']) ? $_POST['role'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/itproject/Admin/Asset/viewadmin.css" rel="stylesheet">
    <link href="/itproject/Admin/Asset/admin.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand img {
            width: 30px;
            height: 30px;
        }

        .container {
            margin-top: 50px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .table img {
            border-radius: 50%;
        }

        .table-dark {
            background-color: #343a40;
            color: white;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-sm {
            padding: 5px 10px;
        }

        .role-select-container {
            display: flex;
            justify-content: right;
            margin-bottom: 30px;
        }

        .role-select-container select {
            width: 200px;
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
            <li class="nav-item"><a class="nav-link text-white" href="/itproject/Admin/registration.php">Create Account</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/itproject/aboutus.php">About Us</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/itproject/Login/login.php">Log out</a></li>
        </ul>
    </div>
</nav>

<div class="container">

    <h1 class="text-center mb-4">Admin Dashboard</h1>

    <div class="role-select-container">
        <form method="POST" class="form-inline">
            <div class="form-group">
                <center>
                <select id="role" name="role" class="form-control" onchange="this.form.submit()">
                    <option value="">Select Role</option>
                    <option value="student" <?= $selected_role == 'student' ? 'selected' : ''; ?>>Student</option>
                    <option value="teacher" <?= $selected_role == 'teacher' ? 'selected' : ''; ?>>Teacher</option>
                    <option value="admin" <?= $selected_role == 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
        </form>
    </div>


    <?php if ($selected_role == 'student' || $selected_role == ''): ?>
        <h4 class="mt-4">Students</h4>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Role</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($student = $students_result->fetch_assoc()) { ?>
                        <tr>
                            <td>Student</td>
                            <td>
                                <img src="<?= $student['profile_image'] ? $student['profile_image'] : 'default-avatar.jpg' ?>" alt="Profile Image" width="50" height="50">
                                <?= htmlspecialchars($student['student_name']) ?>
                            </td>
                            <td><?= htmlspecialchars($student['student_email']) ?></td>
                            <td>
                                <a href="/itproject/Admin/Process/edit.php?id=<?= $student['student_id'] ?>&type=student" class="btn btn-primary btn-sm">Edit</a>
                                <a href="/itproject/Admin/Process/delete.php?id=<?= $student['student_id'] ?>&type=student" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>


    <?php if ($selected_role == 'teacher' || $selected_role == ''): ?>
        <h4 class="mt-4">Teachers</h4>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Role</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($teacher = $teachers_result->fetch_assoc()) { ?>
                        <tr>
                            <td>Teacher</td>
                            <td>
                                <img src="<?= $teacher['profile_image'] ? $teacher['profile_image'] : 'default-avatar.jpg' ?>" alt="Profile Image" width="50" height="50">
                                <?= htmlspecialchars($teacher['teacher_name']) ?>
                            </td>
                            <td><?= htmlspecialchars($teacher['department_name']) ?></td>
                            <td><?= htmlspecialchars($teacher['teacher_email']) ?></td>
                            <td>
                                <a href="/itproject/Admin/Process/edit.php?id=<?= $teacher['teacher_id'] ?>&type=teacher" class="btn btn-primary btn-sm">Edit</a>
                                <a href="/itproject/Admin/Process/delete.php?id=<?= $teacher['teacher_id'] ?>&type=teacher" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>


    <?php if ($selected_role == 'admin' || $selected_role == ''): ?>
        <h4 class="mt-4">Admins</h4>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Role</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($admin = $admins_result->fetch_assoc()) { ?>
                        <tr>
                            <td>Admin</td>
                            <td>
                                <img src="<?= $admin['profile_image'] ? $admin['profile_image'] : 'default-avatar.jpg' ?>" alt="Profile Image" width="50" height="50">
                                <?= htmlspecialchars($admin['admin_name']) ?>
                            </td>
                            <td><?= htmlspecialchars($admin['admin_email']) ?></td>
                            <td>
                                <a href="/itproject/Admin/Process/edit.php?id=<?= $admin['admin_id'] ?>&type=admin" class="btn btn-primary btn-sm">Edit</a>
                                <a href="/itproject/Admin/Process/delete.php?id=<?= $admin['admin_id'] ?>&type=admin" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
