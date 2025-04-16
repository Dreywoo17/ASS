<?php
session_start();
require 'C:\xampp\htdocs\itproject\DBconnect\Accounts\overall.php';

// Handle appointment status update
if (isset($_GET['action']) && isset($_GET['id'])) {
    $appointment_id = (int) $_GET['id']; 
    $action = $_GET['action'];
    $Vstatus = ['cancel' => 'Cancelled', 'ongoing' => 'Ongoing'];

    if (array_key_exists($action, $Vstatus)) {
        $status = $Vstatus[$action];
        $statement = $conn->prepare("UPDATE appointmentdb SET Status = ? WHERE ID = ?");
        $statement->bind_param("si", $status, $appointment_id);

        if ($statement->execute()) {
            header("Location: viewappoint.php");
            exit();
        } else {
            exit("Failed to update status: " . $statement->error);
        }
    }
}

// Fetch appointment details with department and teacher name (join with teacher table)
$sql = "SELECT 
            a.ID,
            a.student_name,
            a.student_ID,
            a.section,
            a.appointment_date,
            a.Description,
            a.Status,
            a.department_name,
            t.teacher_name
        FROM appointmentdb a
        JOIN teacher t ON a.teacher_name = t.teacher_name"; 

$result = $conn->query($sql);

if (!$result) {
    die("Database query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Scheduling System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/itproject/Login/Asset/addappoint.css">
    <link rel="stylesheet" href="/itproject/Login/Asset/viewappoint.css">
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
                <li class="nav-item"><a class="nav-link" href="\itproject\aboutus.php">About Us</a></li>
                <li class="nav-item">
                    <a class="nav-link btn btn-light text-dark" href="\itproject\Login\login.php">
                        <i class="fa-regular fa-user"></i> Log in
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container d-flex justify-content-center mt-4">
    <div class="container3">
        <h1>Scheduled Appointments</h1>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Section</th>
                    <th>Student ID</th>
                    <th>Department</th>
                    <th>Teacher Name</th>
                    <th>Date & Time</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['student_name']); ?></td>
                            <td><?= htmlspecialchars($row['section']); ?></td>
                            <td><?= htmlspecialchars($row['student_ID']); ?></td>
                            <td><?= htmlspecialchars($row['department_name']); ?></td>
                            <td><?= htmlspecialchars($row['teacher_name']); ?></td>
                            <td><?= htmlspecialchars($row['appointment_date']); ?></td>
                            <td><?= htmlspecialchars($row['Description']); ?></td>
                            <td><?= htmlspecialchars($row['Status'] ?? 'Pending'); ?></td>
                            <td>
                                <?php if ($row['Status'] == 'Pending'): ?>
                                    <a href="viewappoint.php?action=cancel&id=<?= $row['ID']; ?>" class="btn btn-danger btn-sm">Cancel</a>
                                <?php elseif ($row['Status'] == 'Accepted'): ?>
                                    <a href="viewappoint.php?action=ongoing&id=<?= $row['ID']; ?>" class="btn btn-warning btn-sm">Ongoing</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center">No appointments scheduled.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="addappoint.php" class="btn btn-danger mt-3">Back</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
