-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2025 at 02:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `overalldb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `admin_email`, `user_password`, `profile_image`, `registration_date`) VALUES
(1, '3', '3@g.cu.edu.ph', '$2y$10$kEYYbJX.iAxPsNAIgACA7eoSh87GlEyDNDRbTZr8lQLIlyIA7Ps1S', 'uploads/410855338_6902156369899961_555800931182185015_n.jpg', '2025-04-22 08:21:40');

-- --------------------------------------------------------

--
-- Table structure for table `appointmentdb`
--

CREATE TABLE `appointmentdb` (
  `ID` int(11) NOT NULL,
  `student_ID` varchar(15) NOT NULL,
  `student_name` varchar(80) NOT NULL,
  `teacher_name` varchar(255) DEFAULT NULL,
  `department_name` varchar(80) NOT NULL,
  `section` varchar(10) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Status` enum('Pending','Accepted','Ongoing','Completed','Cancelled') DEFAULT 'Pending',
  `user_ID` int(11) NOT NULL,
  `user_type` enum('student','teacher','admin') NOT NULL,
  `Cancellation_Remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointmentdb`
--

INSERT INTO `appointmentdb` (`ID`, `student_ID`, `student_name`, `teacher_name`, `department_name`, `section`, `appointment_date`, `Description`, `Status`, `user_ID`, `user_type`, `Cancellation_Remark`) VALUES
(35, '1@g.cu.edu.ph', 'Andriemer S. Bonggo', 'lawas', 'Education', 'B', '2025-04-09 20:26:00', '123', 'Pending', 0, 'student', NULL),
(36, '1@g.cu.edu.ph', 'andriemer', 'drey', 'Maritime Education', 'B', '2025-04-09 20:30:00', 'Programming', 'Pending', 0, 'student', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`) VALUES
(8, 'Art and Sciences'),
(3, 'Business and Accountancy'),
(1, 'Computer Studies'),
(5, 'Criminology'),
(2, 'Education'),
(6, 'Engineering'),
(7, 'Health and Sciences'),
(4, 'Maritime Education');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `student_email` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `department_name` varchar(100) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `student_name`, `student_email`, `user_password`, `department_id`, `department_name`, `profile_image`, `registration_date`) VALUES
(1, '1', '1@g.cu.edu.ph', '$2y$10$7uiyf6spvT.MqkjW9.W4WOZBrxSIrPd77k5iPHPgU5OGve9aNQ96K', NULL, 'Computer Studies', 'uploads/1__y4BSlczZSLCkyOmCMC1Yw.png', '2025-04-21 12:48:54'),
(2, 'brandon', '2@g.cu.edu.ph', '$2y$10$fC.q3wjv9wSARtLCKbjfQeTPa0cFACjTOH0o5e/n259X4Go7H1QZW', NULL, 'Education', '', '2025-04-21 12:49:07'),
(4, 'Andriemer S. Bonggo', '4@g.cu.edu.ph', '$2y$10$rhxnphp.CWZDmgjTtune.uVkgrh9rInffIwuw.SMkpoqdPZLkWEZK', NULL, 'Computer Studies', '', '2025-04-23 11:47:56');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `teacher_id` int(11) NOT NULL,
  `teacher_name` varchar(100) NOT NULL,
  `teacher_email` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `department_name` varchar(100) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacher_id`, `teacher_name`, `teacher_email`, `user_password`, `department_id`, `department_name`, `profile_image`, `registration_date`) VALUES
(2, 'lawas', 'lawas@g.cu.edu.ph', '$2y$10$IKzYNkudZmIhEoPP.bgX4eVCVcOeIMff6.AMnci2G4re4tz.I/2t.', NULL, 'Education', 'uploads/CLASSIC-PEPPERONI.webp', '2025-04-21 13:02:06'),
(3, 'drey', '5@g.cu.edu.ph', '$2y$10$PPGK40VOCjr75Y/86n5Z8ubzX.ZTRR.aS1KXfBkAi1FQ3gq7F7AAS', NULL, 'Maritime Education', 'uploads/admin.png', '2025-04-23 12:25:46');

-- --------------------------------------------------------

--
-- Table structure for table `uploaded_images`
--

CREATE TABLE `uploaded_images` (
  `image_id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `image_type` varchar(100) NOT NULL,
  `image_data` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_email` (`admin_email`);

--
-- Indexes for table `appointmentdb`
--
ALTER TABLE `appointmentdb`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `department_name` (`department_name`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `student_email` (`student_email`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `teacher_email` (`teacher_email`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `uploaded_images`
--
ALTER TABLE `uploaded_images`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointmentdb`
--
ALTER TABLE `appointmentdb`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `uploaded_images`
--
ALTER TABLE `uploaded_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `teacher_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
