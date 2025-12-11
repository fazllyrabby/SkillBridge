-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2025 at 10:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jobseekerbd`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','reviewed','shortlisted','interview','accepted','rejected') DEFAULT 'pending',
  `cover_letter` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `job_id`, `user_id`, `application_date`, `status`, `cover_letter`) VALUES
(1, 2, 3, '2025-05-08 22:37:43', 'pending', NULL),
(2, 1, 7, '2025-05-08 22:41:42', 'pending', NULL),
(3, 1, 3, '2025-05-09 06:58:28', 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `established_date` date DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `industry` varchar(100) DEFAULT NULL,
  `employee_count` varchar(50) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `description`, `location`, `established_date`, `website`, `logo`, `industry`, `employee_count`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'JobSeekerBD', 'Bangladesh\'s leading job portal', 'Dhaka, Bangladesh', '2023-01-01', 'https://jobseekerbd.com', NULL, NULL, NULL, 1, '2025-05-08 18:00:42', '2025-05-08 18:00:42'),
(2, 'Apple Com.ltd', 'branded company', 'california', '1975-02-10', 'https://www.facebook.com/bidhan.bormon.343799', NULL, NULL, NULL, 5, '2025-05-08 21:32:09', '2025-05-08 21:32:09'),
(3, 'jhujhuX_op', 'general service', 'dhaka', '0000-00-00', 'https://www.xop.com', NULL, NULL, NULL, 6, '2025-05-08 22:31:36', '2025-05-08 22:31:36'),
(4, 'T_force', 'ITSolution', 'Dhaka', '2010-02-16', 'https://www.tforce.com', NULL, NULL, NULL, 12, '2025-05-14 19:50:54', '2025-05-14 19:50:54');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `role` enum('job_seeker','employer') NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `subject`, `message`, `role`, `is_read`, `created_at`) VALUES
(1, 'Bidhan Bormon', 'bidhanbormon08@gmail.com', '01830161365', 'ccvsav', 'sasc', 'job_seeker', 0, '2025-05-08 20:14:27');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `responsibilities` text DEFAULT NULL,
  `salary_range` varchar(50) DEFAULT NULL,
  `job_type` enum('full-time','part-time','contract','internship','temporary','freelance') NOT NULL,
  `location` varchar(255) NOT NULL,
  `experience_level` varchar(50) DEFAULT NULL,
  `education_level` varchar(50) DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `company_id` int(11) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `description`, `requirements`, `responsibilities`, `salary_range`, `job_type`, `location`, `experience_level`, `education_level`, `deadline`, `is_active`, `company_id`, `posted_by`, `created_at`, `updated_at`) VALUES
(1, 'developer', 'day shift', 'bsc pass', NULL, '20k-30k', 'part-time', 'dhaka', NULL, NULL, '2025-05-23', 1, 2, 5, '2025-05-08 21:38:22', '2025-05-08 21:38:22'),
(2, 'delivery man', 'honest', 'hsc pass', NULL, '10k-15k', 'part-time', 'dhaka', NULL, NULL, '2025-05-05', 1, 3, 6, '2025-05-08 22:33:24', '2025-05-08 22:33:24'),
(3, 'developer', 'hdhhd', 'hfhfhf', NULL, '20k-30k', 'contract', 'dhaka', NULL, NULL, '2025-05-28', 1, 2, 5, '2025-05-09 08:20:21', '2025-05-09 08:20:21'),
(4, 'designer', 'dfdfff', 'adobe photoshop', NULL, '20k - 30k', 'part-time', 'dhaka', NULL, NULL, '2025-05-30', 1, 4, 12, '2025-05-14 19:54:38', '2025-05-14 19:54:38');

-- --------------------------------------------------------

--
-- Table structure for table `job_alerts`
--

CREATE TABLE `job_alerts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `keyword` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `job_type` varchar(50) DEFAULT NULL,
  `frequency` enum('daily','weekly','monthly') DEFAULT 'weekly',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_categories`
--

CREATE TABLE `job_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_categories`
--

INSERT INTO `job_categories` (`id`, `name`, `description`, `icon`, `created_at`) VALUES
(1, 'Development', 'Software development and programming jobs', 'fa-code', '2025-05-08 18:00:42'),
(2, 'Finance', 'Accounting and financial services jobs', 'fa-hand-holding-dollar', '2025-05-08 18:00:42'),
(3, 'Labour', 'Manual labor and skilled trade jobs', 'fa-person-digging', '2025-05-08 18:00:42'),
(4, 'Service', 'Customer service and hospitality jobs', 'fa-headset', '2025-05-08 18:00:42'),
(5, 'Engineer', 'Engineering and technical jobs', 'fa-wrench', '2025-05-08 18:00:42'),
(6, 'Marketing', 'Marketing and advertising jobs', 'fa-bullhorn', '2025-05-08 18:00:42'),
(7, 'Teacher', 'Education and teaching jobs', 'fa-chalkboard-user', '2025-05-08 18:00:42'),
(8, 'Designer', 'Graphic and UI/UX design jobs', 'fa-pen', '2025-05-08 18:00:42');

-- --------------------------------------------------------

--
-- Table structure for table `job_category_mapping`
--

CREATE TABLE `job_category_mapping` (
  `job_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_category_mapping`
--

INSERT INTO `job_category_mapping` (`job_id`, `category_id`) VALUES
(1, 4),
(2, 6),
(3, 1),
(4, 8);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` decimal(2,1) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `title` varchar(100) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `company_id`, `user_id`, `rating`, `title`, `comment`, `is_approved`, `created_at`) VALUES
(1, 2, 5, 4.0, NULL, 'this is good', 0, '2025-05-08 21:57:19'),
(2, 2, 8, 5.0, NULL, 'yeah this is excellent', 0, '2025-05-09 09:16:10');

-- --------------------------------------------------------

--
-- Table structure for table `saved_jobs`
--

CREATE TABLE `saved_jobs` (
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved_jobs`
--

INSERT INTO `saved_jobs` (`user_id`, `job_id`, `saved_at`) VALUES
(3, 2, '2025-05-08 22:37:53'),
(5, 1, '2025-05-08 22:19:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('job_seeker','employer') NOT NULL DEFAULT 'job_seeker',
  `photo` varchar(255) DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `headline` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `photo`, `resume`, `headline`, `bio`, `skills`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@jobseekerbd.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employer', NULL, NULL, NULL, NULL, NULL, '2025-05-08 18:00:42', '2025-05-08 18:00:42'),
(2, 'bidhan', 'bidhanbormon08@gmail.com', '$2y$10$e4d//Dg0erT3wgVompAmD.S54iTct.NxJoUfHjz.HUubJ0fvU96T6', 'job_seeker', NULL, NULL, NULL, NULL, NULL, '2025-05-08 19:22:53', '2025-05-08 19:22:53'),
(3, 'bidhan', 'bormon404@gmail.com', '$2y$10$z8qGiS9j9V6aCNIM3ALiVeWxgAH05Ua8O6arDd9RjLL9ffeXscn.O', 'job_seeker', NULL, NULL, NULL, NULL, NULL, '2025-05-08 20:17:20', '2025-05-08 20:17:20'),
(4, 'bidhan', 'bidhan@gmail.com', '$2y$10$v4yPIkp.VsNPQjTvsl6GVeyxva28zD4i1m61DeSyNGFiaBO99lxPS', 'job_seeker', NULL, NULL, NULL, NULL, NULL, '2025-05-08 20:30:44', '2025-05-08 20:30:44'),
(5, 'Bidhan Bormon', 'bidhanbormon404@gmail.com', '$2y$10$IcW0jv4yIsQgHS2f9zWOQuE/k3z1kTXAaxbbNlD4Qx/3PrZCdCAAG', 'employer', NULL, NULL, NULL, NULL, NULL, '2025-05-08 20:52:43', '2025-05-08 20:52:43'),
(6, 'rabby', 'rabby@gmail.com', '$2y$10$Un7mVno3rTHKpeKRKAWklOQ/JlrAaGP7Jt694bAJcmtjZyedT6dfq', 'employer', NULL, NULL, NULL, NULL, NULL, '2025-05-08 22:28:40', '2025-05-08 22:28:40'),
(7, 'rabby', 'fazlly@gmail.com', '$2y$10$xh/E2to4WPo002bvrGAhUeA0Wte/wdHFEe2s1NEkCusxvFC9ZBV/.', 'job_seeker', NULL, NULL, NULL, NULL, NULL, '2025-05-08 22:41:16', '2025-05-08 22:41:16'),
(8, 'taj uddin', 'taj@gmail.com', '$2y$10$loQ42NI3bIWl5jJcMz9Le.R63elOXYB83uk2UBcPKIlEHH2Sxt3ou', 'job_seeker', NULL, NULL, NULL, NULL, NULL, '2025-05-09 09:09:16', '2025-05-09 09:09:16'),
(12, 'saki', 'saki@gmail.com', '$2y$10$owCo1ZVJaipGETSvZJVtVeWjXWt913hCsdkMG6GoRncOqBpOnJHm2', 'employer', NULL, NULL, NULL, NULL, NULL, '2025-05-14 19:39:58', '2025-05-14 19:39:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `posted_by` (`posted_by`);

--
-- Indexes for table `job_alerts`
--
ALTER TABLE `job_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `job_categories`
--
ALTER TABLE `job_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_category_mapping`
--
ALTER TABLE `job_category_mapping`
  ADD PRIMARY KEY (`job_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  ADD PRIMARY KEY (`user_id`,`job_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `job_alerts`
--
ALTER TABLE `job_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_categories`
--
ALTER TABLE `job_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jobs_ibfk_2` FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_alerts`
--
ALTER TABLE `job_alerts`
  ADD CONSTRAINT `job_alerts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_alerts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `job_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `job_category_mapping`
--
ALTER TABLE `job_category_mapping`
  ADD CONSTRAINT `job_category_mapping_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_category_mapping_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `job_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  ADD CONSTRAINT `saved_jobs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_jobs_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
