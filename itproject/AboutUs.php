<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Computer Studies Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        body {
            background-color: #ffffff;
            color: #333;
            font-family: 'Arial', sans-serif;
        }
        .hero-section {
            padding: 100px 0;
            background-color: #ffffff;
        }
        .hero-section h1 {
            font-size: 2.5rem;
            color: #222;
        }
        .stats-card {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px 0;
        }
        .stats-card h2 {
            font-size: 2rem;
            color: #007bff;
        }
        .navbar {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .social-icons i {
            font-size: 1.5rem;
            margin: 10px;
            color: #007bff;
            transition: transform 0.3s;
        }
        .social-icons i:hover {
            transform: scale(1.2);
            color: #0056b3;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img class="logo me-1" src="img/Alogo1.jpg" alt="Logo" width="40">
                <span>Appointment Scheduling System</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="AboutUs.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="\itproject\Login\login.php" target="_blank"><i class="fa-regular fa-user"></i> Log in</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="text-primary">How it Started</h6>
                    <h1 class="fw-bold">Our Dream is Global Learning Transformation</h1>
                    <p>The Appointment Scheduling System is a digital tool designed to streamline appointment management...</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="img/abouthead.jpg" class="img-fluid rounded" alt="About Us">
                </div>
            </div>
        </div>
    </section>

    <!-- Google Map & YouTube Video -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <iframe class="w-100" height="250" src="https://www.google.com/maps/embed?" allowfullscreen></iframe>
            </div>
            <div class="col-md-5">
                <iframe class="w-100" height="250" src="https://www.youtube.com/embed/F4fbwKV9dBU" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <!-- Social Media Section -->
    <div class="container my-5 text-center">
        <h2>Connect with us</h2>
        <div class="social-icons mt-3">
            <i class="fa-brands fa-facebook"></i>
            <i class="fa-brands fa-twitter"></i>
            <i class="fa-brands fa-linkedin"></i>
            <i class="fa-brands fa-instagram"></i>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
