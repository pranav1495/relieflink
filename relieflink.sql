-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 05, 2025 at 09:36 AM
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
-- Database: `relieflink`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_public`
--

CREATE TABLE `chat_public` (
  `id` int(11) NOT NULL,
  `sender_username` varchar(100) NOT NULL,
  `receiver_username` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disaster_alerts`
--

CREATE TABLE `disaster_alerts` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `help_requests`
--

CREATE TABLE `help_requests` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `location` varchar(255) NOT NULL,
  `need` text NOT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_username` varchar(100) NOT NULL,
  `receiver_username` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_username`, `receiver_username`, `message`, `sent_at`) VALUES
(3, 'admin', 'akash123', 'Kollath nalla flood annenn.Vegam team ne erakkk', '2025-07-01 14:58:14'),
(4, 'akash123', 'admin', 'Ok sir', '2025-07-01 14:58:47'),
(9, 'sheeba123', 'admin', 'Halo', '2025-07-02 05:40:10'),
(10, 'admin', 'sheeba123', 'Hii , Sheeba123', '2025-07-02 05:40:38'),
(11, 'admin', 'sheeba123', 'Hi there , in tvm an alert happened', '2025-07-03 10:38:49'),
(12, 'sheeba123', 'admin', 'What?', '2025-07-03 10:40:05'),
(14, 'sheeba123', 'prathap123', 'hi', '2025-07-04 19:36:24'),
(15, 'sheeba123', 'prathap123', 'hello', '2025-07-04 19:39:26'),
(16, 'prathap123', 'sheeba123', 'hlo', '2025-07-04 20:11:30'),
(17, 'publicUser123', 'volunteerABC', 'Hello! This is a test message', '2025-07-05 05:38:12'),
(18, 'dev20', 'pranav123', 'hlo', '2025-07-05 06:01:09'),
(19, 'pranav123', 'dev20', 'halo', '2025-07-05 06:21:36'),
(20, 'pranav123', 'prathap123', 'Hi', '2025-07-05 06:24:10'),
(21, 'prathap123', 'pranav123', 'hi', '2025-07-05 06:26:59'),
(22, 'sheeba123', 'prathap123', 'hi', '2025-07-05 06:27:51'),
(38, 'pranav123', 'dev20', 'hi', '2025-07-05 06:56:56'),
(42, 'admin', 'pranav123', 'hello', '2025-07-05 07:03:36'),
(43, 'pranav123', 'admin', 'Hi', '2025-07-05 07:05:21'),
(44, 'pranav123', 'prathap123', 'hi', '2025-07-05 07:07:55'),
(45, 'ram123', 'all', 'Hello All', '2025-07-05 07:20:54'),
(46, 'ram123', 'admin', 'hi admin', '2025-07-05 07:21:10'),
(47, 'ram123', 'dev20', 'Hello', '2025-07-05 07:21:28'),
(48, 'dev20', 'ram123', 'hi sir', '2025-07-05 07:22:22'),
(49, 'pranav123', 'all', 'this is for volunteers chating purpose', '2025-07-05 07:23:52'),
(50, 'sheeba123', 'all', 'Yes', '2025-07-05 07:24:25'),
(51, 'admin', 'pranav123', 'Yeah', '2025-07-05 07:25:17'),
(52, 'admin', 'sheeba123', 'There is a heavy rain, they are under traffic', '2025-07-05 07:25:39'),
(53, 'admin', 'ram123', 'hi , ram', '2025-07-05 07:25:49'),
(54, 'dev20', 'pranav123', 'help', '2025-07-05 07:27:21'),
(55, 'pranav123', 'dev20', 'what needed?', '2025-07-05 07:27:44');

-- --------------------------------------------------------

--
-- Table structure for table `public_users`
--

CREATE TABLE `public_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `public_users`
--

INSERT INTO `public_users` (`id`, `username`, `password`, `full_name`, `email`, `phone`, `location`, `gender`, `photo`, `created_at`) VALUES
(1, 'prathap123', '$2y$10$WIx.ASbAqRTHD4qWc./gkO9/cFIVLMj5LpKLfsEKcMjgDz8o2j.Lq', 'Prathap', 'prathap123@gmail.com', '9876543210', 'Chennai, Tamil Nadu', 'Male', 'public_6867ed4a8afcb.jpg', '2025-07-04 15:03:38'),
(2, 'dev20', '$2y$10$mLCCAZI1jFAgYiHYzda8UOTdUu/CbinT8CPo5hMhJVtZVaqk/LGqC', 'Dev', NULL, NULL, NULL, NULL, 'public_6868b78d62ae4.jpg', '2025-07-05 05:26:37'),
(3, 'vai123', '$2y$10$WAfisEjUr1VZ7Ta0h.9fKuAXyi8rUHD2wbi55m5eXXZbknN/oIRaW', 'Vaisakh', NULL, NULL, NULL, NULL, 'public_6868d43dedb31.jpeg', '2025-07-05 07:29:02');

-- --------------------------------------------------------

--
-- Table structure for table `relief_centers`
--

CREATE TABLE `relief_centers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `location` varchar(100) NOT NULL,
  `status` enum('Available','Used','In Transit') DEFAULT 'Available',
  `added_on` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `name`, `category`, `quantity`, `location`, `status`, `added_on`) VALUES
(1, 'Mundu', 'Clothes', 20, 'kottiyam', 'Used', '2025-07-02 09:41:28'),
(2, 'Rice Bags', 'Food', 100, 'Thiruvananthapuram', 'Available', '2025-07-02 09:45:37'),
(3, 'First Aid Kits', 'Medicine', 30, 'Kollam', 'Available', '2025-07-02 09:45:37'),
(4, 'Blankets', 'Shelter', 50, 'Ernakulam', 'In Transit', '2025-07-02 09:45:37'),
(5, 'Water Bottles', 'Water', 200, 'Palakkad', 'Used', '2025-07-02 09:45:37'),
(6, 'Glucose Packets', 'Food', 75, 'Alappuzha', 'Used', '2025-07-02 09:45:37'),
(7, 'Sanitary Napkins', 'Hygiene', 90, 'Thrissur', 'Available', '2025-07-02 09:45:37'),
(8, 'Medicines for Fever', 'Medicine', 120, 'Kozhikode', 'Available', '2025-07-02 09:45:37'),
(9, 'Temporary Tents', 'Shelter', 15, 'Kannur', 'In Transit', '2025-07-02 09:45:37'),
(10, 'Children Clothes', 'Clothes', 70, 'Kasaragod', 'Available', '2025-07-02 09:45:37'),
(11, 'ORS Sachets', 'Medicine', 150, 'Idukki', 'Used', '2025-07-02 09:45:37'),
(12, 'Shirts', 'Clothes', 10, 'Kattakada', 'Available', '2025-07-05 12:48:29');

-- --------------------------------------------------------

--
-- Table structure for table `resource_allocations`
--

CREATE TABLE `resource_allocations` (
  `id` int(11) NOT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `allocated_to` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `status` enum('Sent','Received') DEFAULT 'Sent',
  `allocated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','volunteer') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=pending, 1=approved, 2=declined'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `photo`, `password_hash`, `role`, `created_at`, `full_name`, `email`, `phone`, `address`, `status`) VALUES
(1, 'admin', NULL, '$2y$10$hDD5IBaKjK.GZXclPe1gVepdihM3mMjgC/SR4Y2uWTAn9GM8zr2km', 'admin', '2025-07-01 13:08:42', NULL, NULL, NULL, NULL, 1),
(4, 'akash123', 'volunteers/akash.jpg', '$2y$10$Qwhx8mYe7EWY6mT9z7Z/9.09R.ooQwfnk/7E4u2vFvlPGR4pwIrsm', 'volunteer', '2025-07-01 13:23:23', 'Akash B', 'akashb@example.com', '9876543210', 'Kottayam, Kerala', 1),
(5, 'pranav123', 'D-7254.jpg', '$2y$10$wLek96bEMZsxbxuUxFsa1OLL2p.gAFACZh0O1wDYi97lQbS8plqP.', 'volunteer', '2025-07-01 16:01:28', 'Pranav Eswar', 'pranavartist1@gmail.com', '9074261433', 'Tvm,Kerala', 1),
(6, 'sheeba123', 'sneha1.jpg', '$2y$10$Ju/Gv0.AGBBtAOaKD./t1uF8rwEtslYMMtEhoq3lYqgcym0urwvfK', 'volunteer', '2025-07-02 05:37:58', 'Sheeba', 'sheeba123@gmail.com', '9945873465', 'Kollam', 1),
(7, 'ram123', 'rahul.jpeg', '$2y$10$TD1GST5D2QW27MG2kZaUKODPZvnYescHx1xbJKHuHn1/pItD8KzGi', 'volunteer', '2025-07-05 07:17:40', 'Ram', 'ram123@gmail.com', '7248957625', 'Ernakulam', 0);

-- --------------------------------------------------------

--
-- Table structure for table `venquiry`
--

CREATE TABLE `venquiry` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `confirm_password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','declined') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venquiry`
--

INSERT INTO `venquiry` (`id`, `name`, `username`, `password`, `confirm_password`, `email`, `phone`, `photo`, `status`, `created_at`) VALUES
(1, 'Pranav Eswar', 'pranav123', '$2y$10$wLek96bEMZsxbxuUxFsa1OLL2p.gAFACZh0O1wDYi97lQbS8plqP.', '', 'pranav123@gmail.com', '9074261433', 'D-7254.jpg', 'approved', '2025-07-01 15:48:42'),
(3, 'Sheeba', 'sheeba123', '$2y$10$Ju/Gv0.AGBBtAOaKD./t1uF8rwEtslYMMtEhoq3lYqgcym0urwvfK', '', 'sheeba123@gmail.com', '9945873465', 'sneha1.jpg', 'approved', '2025-07-02 05:22:04'),
(4, 'Ram', 'ram123', '$2y$10$TD1GST5D2QW27MG2kZaUKODPZvnYescHx1xbJKHuHn1/pItD8KzGi', '', 'ram123@gmail.com', '7248957625', 'rahul.jpeg', 'approved', '2025-07-03 10:37:33'),
(5, 'Sathya', 'sathya123', '$2y$10$YHEAzgSZpPRHdaTaBn.t1efEMMP6.zdN/wmvawImHM/84./.2vI3K', '', 'sathya123@gmail.com', '7824576852', '4.jpeg', 'pending', '2025-07-05 07:16:51');

-- --------------------------------------------------------

--
-- Table structure for table `victims`
--

CREATE TABLE `victims` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `need` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  `approved_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `viewed_by` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `victims`
--

INSERT INTO `victims` (`id`, `name`, `phone`, `email`, `location`, `need`, `status`, `approved_by`, `created_at`, `viewed_by`) VALUES
(6, 'Tinu', '9939489744', 'tinu123@gmail.com', 'Tvm', 'Help', 1, 'sheeba123', '2025-07-02 05:19:52', NULL),
(7, 'soman', '7436890346', 'soman123@gmail.com', 'Kattakada', 'Food', 1, 'pranav123', '2025-07-02 14:07:11', NULL),
(8, 'SreeVidya', '8946745624', 'sreevids@gmail.com', 'Tvm', 'Help please', 1, 'sheeba123', '2025-07-03 10:34:42', NULL),
(9, 'Ram', '8357643654', 'ram123@gmail.com', 'wayanadu', 'Help please', 1, 'ram123', '2025-07-05 07:15:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `victim_requests`
--

CREATE TABLE `victim_requests` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `location` varchar(255) NOT NULL,
  `need` text NOT NULL,
  `status` enum('Pending','Approved','Declined') DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `viewed_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_public`
--
ALTER TABLE `chat_public`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disaster_alerts`
--
ALTER TABLE `disaster_alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_requests`
--
ALTER TABLE `help_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `public_users`
--
ALTER TABLE `public_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `relief_centers`
--
ALTER TABLE `relief_centers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resource_allocations`
--
ALTER TABLE `resource_allocations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `venquiry`
--
ALTER TABLE `venquiry`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `victims`
--
ALTER TABLE `victims`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `victim_requests`
--
ALTER TABLE `victim_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_public`
--
ALTER TABLE `chat_public`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disaster_alerts`
--
ALTER TABLE `disaster_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `help_requests`
--
ALTER TABLE `help_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `public_users`
--
ALTER TABLE `public_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `relief_centers`
--
ALTER TABLE `relief_centers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `resource_allocations`
--
ALTER TABLE `resource_allocations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `venquiry`
--
ALTER TABLE `venquiry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `victims`
--
ALTER TABLE `victims`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `victim_requests`
--
ALTER TABLE `victim_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
