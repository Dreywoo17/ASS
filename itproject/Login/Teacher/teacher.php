<?php
session_start();

if (!isset($_SESSION['teacher_name'])) {
    header("Location: /itproject/Login/login.php");
    exit;
}

require 'C:\xampp\htdocs\itproject\DBconnect\Accounts\Conn_overall.php';

$teacherName = $_SESSION['teacher_name'];  


if (isset($_GET['action']) && isset($_GET['id'])) {
    $studentId = $_GET['id'];

    // If action is 'accept', update the appointment status to 'Ongoing'
    if ($_GET['action'] == 'accept') {
        $sql = "UPDATE appointmentdb SET Status = 'Ongoing' WHERE Student_ID = ? AND teacher_name = ?";
        $statement = $conn->prepare($sql);
        $statement->bind_param("is", $studentId, $teacherName); 

        if ($statement->execute()) {
            echo "<div class='alert alert-success text-center'>Appointment Accepted!</div>";
            header("Location: teacher.php");
            exit();
        } else {
            echo "<div class='alert alert-danger text-center'>Failed to accept the appointment.</div>";
        }
    }

    // If action is 'complete', update the appointment status to 'Completed'
    if ($_GET['action'] == 'complete') {
        $sql = "UPDATE appointmentdb SET Status = 'Completed' WHERE Student_ID = ? AND teacher_name = ?";
        $statement = $conn->prepare($sql);
        $statement->bind_param("is", $studentId, $teacherName);

        if ($statement->execute()) {
            echo "<div class='alert alert-success text-center'>Appointment Completed!</div>";
            header("Location: teacher.php"); 
        } else {
            echo "<div class='alert alert-danger text-center'>Failed to complete the appointment.</div>";
        }
    }
}


$sql = "SELECT Student_Name, Section, Appointment_Date, Student_ID, Description, Status FROM appointmentdb WHERE teacher_name = ?";
$statement = $conn->prepare($sql);
$statement->bind_param("s", $teacherName);
$statement->execute();
$result = $statement->get_result();

if ($result === false) {
    die("Database query failed: " . mysqli_error($conn));
}

$appointments = [
    'Pending' => [],
    'Ongoing' => [],
    'Completed' => []
];

while ($row = $result->fetch_assoc()) {
    $appointments[$row['Status']][] = $row;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/itproject/Login/Asset/addappoint.css">
    <link rel="stylesheet" href="/itproject/Login/Asset/viewappoint.css">
    <link rel="stylesheet" href="/itproject/Login/Asset/teacher.css">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img class="logo me-2" src="../../img/Alogo1.jpg" alt="Logo">
            <span class="text-white ms-2">Appointment Scheduling System</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="/itproject/Login/login.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5 bg-semi-transparent">
    <h2 class="mb-3 text-center">Manage Appointments</h2>
    <h4>Pending Appointments</h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Section</th>
                    <th>Student ID</th>
                    <th>Date & Time</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments['Pending'] as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Student_Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Section']); ?></td>
                        <td><?php echo htmlspecialchars($row['Student_ID']); ?></td>
                        <td><?php echo htmlspecialchars($row['Appointment_Date']); ?></td>
                        <td><?php echo htmlspecialchars($row['Description']); ?></td>
                        <td>
                            <a href="teacher.php?action=accept&id=<?php echo $row['Student_ID']; ?>" class="btn btn-success btn-sm">Accept</a>
                            <a href="teacher.php?action=cancel&id=<?php echo $row['Student_ID']; ?>" class="btn btn-danger btn-sm">Cancel</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h4 class="mt-4">Ongoing Appointments</h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Section</th>
                    <th>Student ID</th>
                    <th>Date & Time</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments['Ongoing'] as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Student_Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Section']); ?></td>
                        <td><?php echo htmlspecialchars($row['Student_ID']); ?></td>
                        <td><?php echo htmlspecialchars($row['Appointment_Date']); ?></td>
                        <td><?php echo htmlspecialchars($row['Description']); ?></td>
                        <td>
                            <a href="teacher.php?action=complete&id=<?php echo $row['Student_ID']; ?>" class="btn btn-primary btn-sm">Complete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <h4 class="mt-4">Completed Appointments</h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Section</th>
                    <th>Student ID</th>
                    <th>Date & Time</th>
                    <th>Description</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments['Completed'] as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Student_Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Section']); ?></td>
                        <td><?php echo htmlspecialchars($row['Student_ID']); ?></td>
                        <td><?php echo htmlspecialchars($row['Appointment_Date']); ?></td>
                        <td><?php echo htmlspecialchars($row['Description']); ?></td>
                        <td><span class="badge bg-success">Completed</span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap
