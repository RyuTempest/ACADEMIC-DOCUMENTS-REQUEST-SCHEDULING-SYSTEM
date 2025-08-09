-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2024 at 07:51 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `book`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `price` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `slot_type` enum('AM','PM') NOT NULL,
  `slot_date` date NOT NULL,
  `reference_number` varchar(8) NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `status_type` varchar(255) NOT NULL,
  `other_document_type` varchar(255) NOT NULL,
  `other_status` varchar(255) NOT NULL,
  `board` varchar(255) NOT NULL,
  `year` varchar(255) NOT NULL,
  `term` varchar(255) NOT NULL,
  `SY` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `slot_id`, `price`, `created_at`, `slot_type`, `slot_date`, `reference_number`, `document_type`, `purpose`, `status_type`, `other_document_type`, `other_status`, `board`, `year`, `term`, `SY`) VALUES
(276, 15, 21, '₱700', '2024-10-09 17:20:05', 'AM', '2024-09-30', 'F51T4G2Q', 'Transcript of Record with Degree, Transcript of Record without Degree,', 'Employments', 'Withdrawn', '', '', '', '', 'sds', 'sds'),
(277, 15, 22, '₱700', '2024-10-09 17:20:29', 'AM', '2024-10-01', '6VFG39EB', 'Transcript of Record with Degree, Transcript of Record without Degree,', 'Employments', 'Withdrawn', '', '', '', '', 'axdasd', 'adasd');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `OR_Number` varchar(25) NOT NULL,
  `document_type` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('claimed','processing','pending') DEFAULT 'pending',
  `date_claim` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `booking_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `OR_Number`, `document_type`, `user_id`, `status`, `date_claim`, `created_at`, `updated_at`, `booking_id`) VALUES
(98, '', '0', 15, 'pending', NULL, '2024-10-09 17:20:05', '2024-10-09 17:20:05', 276),
(99, '', '0', 15, 'pending', NULL, '2024-10-09 17:20:29', '2024-10-09 17:20:29', 277);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `document_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `document_id`, `created_at`) VALUES
(49, 13, '1', 60, '2024-10-02 12:44:37'),
(50, 13, '1', 60, '2024-10-02 12:44:39'),
(51, 13, '1', 60, '2024-10-02 12:44:41'),
(52, 13, '1', 60, '2024-10-02 12:44:42'),
(53, 13, '[Twilio.Api.V2010.MessageInstance accountSid=ACd18a3a8cb13859183855ae512d70835d sid=SM82abbc90d37e2615c3233014111ee290]', 60, '2024-10-02 12:48:11'),
(54, 13, '1', 61, '2024-10-02 12:49:34'),
(55, 13, '1', 60, '2024-10-02 12:51:09'),
(56, 13, '1', 60, '2024-10-02 12:51:43'),
(57, 13, '0', 61, '2024-10-02 12:59:00'),
(58, 13, '0', 61, '2024-10-02 12:59:26'),
(59, 13, 'You can get your document on January 4, 2025', 61, '2024-10-02 13:03:31'),
(60, 13, 'You can get your document on January 4, 2030', 61, '2024-10-02 13:05:18'),
(61, 13, 'You can get your document on January 4, 2032', 61, '2024-10-02 13:06:06'),
(62, 13, 'You can get your document on January 4, 2032', 61, '2024-10-02 17:14:09'),
(63, 13, 'You can get your document on January 4, 2032', 61, '2024-10-02 17:22:09'),
(64, 13, 'You can get your document on January 4, 2032', 61, '2024-10-02 17:42:50');

-- --------------------------------------------------------

--
-- Table structure for table `slots`
--

CREATE TABLE `slots` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `slot_date` date NOT NULL,
  `start_time_am` time DEFAULT '08:00:00',
  `end_time_am` time DEFAULT '12:00:00',
  `am_capacity` int(11) DEFAULT 30,
  `am_booked` int(11) DEFAULT 0,
  `start_time_pm` time DEFAULT '13:00:00',
  `end_time_pm` time DEFAULT '17:00:00',
  `pm_capacity` int(11) DEFAULT 30,
  `pm_booked` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `slots`
--

INSERT INTO `slots` (`id`, `user_id`, `slot_date`, `start_time_am`, `end_time_am`, `am_capacity`, `am_booked`, `start_time_pm`, `end_time_pm`, `pm_capacity`, `pm_booked`) VALUES
(21, 11, '2024-09-30', '09:00:00', '12:00:00', 30, 19, '13:00:00', '17:00:00', 30, 19),
(22, 11, '2024-10-01', '09:00:00', '12:00:00', 30, 3, '13:00:00', '17:00:00', 30, 10),
(23, 11, '2024-10-02', '09:00:00', '12:00:00', 30, 5, '13:00:00', '17:00:00', 30, 13),
(24, 11, '2024-10-03', '09:00:00', '12:00:00', 30, 2, '13:00:00', '17:00:00', 30, 0),
(25, 11, '2024-10-04', '09:00:00', '12:00:00', 30, 1, '13:00:00', '17:00:00', 30, 1),
(26, 11, '2024-10-07', '09:00:00', '12:00:00', 30, 2, '13:00:00', '17:00:00', 30, 1),
(27, 11, '2024-10-11', '09:00:00', '12:00:00', 30, 2, '13:00:00', '17:00:00', 30, 1),
(28, 11, '2024-10-15', '09:00:00', '12:00:00', 30, 0, '13:00:00', '17:00:00', 30, 2);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Completed') DEFAULT 'Pending',
  `price` varchar(255) NOT NULL,
  `document_type` text NOT NULL,
  `application` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `purpose` varchar(255) NOT NULL,
  `status_type` varchar(255) NOT NULL,
  `other_document_type` varchar(255) NOT NULL,
  `other_status` varchar(255) NOT NULL,
  `board` varchar(255) NOT NULL,
  `year` varchar(255) NOT NULL,
  `term` varchar(255) NOT NULL,
  `SY` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `booking_id`, `payment_method`, `status`, `price`, `document_type`, `application`, `created_at`, `purpose`, `status_type`, `other_document_type`, `other_status`, `board`, `year`, `term`, `SY`) VALUES
(225, 13, 249, 'Over the Counter', 'Completed', '₱300', 'Transcript of Record without Degree', 'Appearance', '2024-10-02 07:51:58', '', '', '', '', '', '', '', ''),
(238, 15, 267, 'Over the Counter', 'Pending', '₱700', 'Transcript of Record with Degree, Transcript of Record without Degree,', 'Appearance', '2024-10-09 15:59:55', 'Employments', 'Dismissed', '', '', '', '', 'jhbjb', 'kjb'),
(239, 15, 268, 'Over the Counter', 'Pending', '₱500', 'Transcript of Record with Degree, Certificate of Transfer Credentials, Others,', 'Appearance', '2024-10-09 16:44:39', 'Schooling', 'Dismissed', 'sfddsdf', '', '', '', 'Sdxasd', 'adasd'),
(240, 15, 269, 'Over the Counter', 'Pending', '₱700', 'Transcript of Record with Degree, Transcript of Record without Degree,', 'Appearance', '2024-10-09 17:01:32', 'Employments', 'Dismissed', '', '', '', '', 'sdasd', 'asdasd'),
(241, 15, 270, 'Over the Counter', 'Pending', '₱700', 'Transcript of Record with Degree, Transcript of Record without Degree,', 'Appearance', '2024-10-09 17:03:45', 'Schooling', 'Withdrawn', '', '', '', '', 'sadcas', 'asdasd'),
(242, 15, 271, 'Over the Counter', 'Pending', '₱700', 'Transcript of Record with Degree, Transcript of Record without Degree,', 'Appearance', '2024-10-09 17:14:39', 'Schooling', 'Dismissed', '', '', '', '', 'esdf', 'sdfcsd'),
(243, 15, 272, 'Over the Counter', 'Pending', '₱700', 'Transcript of Record with Degree, Transcript of Record without Degree,', 'Appearance', '2024-10-09 17:15:03', 'Reference', 'Withdrawn', '', 'dsfsdf', '', '', 'sdfs', 'sdfsd'),
(244, 15, 276, 'Over the Counter', 'Pending', '₱700', 'Transcript of Record with Degree, Transcript of Record without Degree,', 'Appearance', '2024-10-09 17:20:05', 'Employments', 'Withdrawn', '', '', '', '', 'sds', 'sds'),
(245, 15, 277, 'Over the Counter', 'Pending', '₱700', 'Transcript of Record with Degree, Transcript of Record without Degree,', 'Appearance', '2024-10-09 17:20:29', 'Employments', 'Withdrawn', '', '', '', '', 'axdasd', 'adasd');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `suffix` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `cellphone` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `middlename`, `lastname`, `suffix`, `email`, `cellphone`, `username`, `password`, `password_reset_token`, `role`, `created_at`, `updated_at`) VALUES
(11, 'julius', '0', 'mandia', '', 'juliusmandia@gmail', '54645645123', 'admin', '$2y$10$Gs6oMTGe6ujyeUT7XZgc0OjuXajIEEYUfzPouwnGCgjwe/SXToL3G', '0', 'admin', '2024-09-21 11:41:32', '2024-09-21 11:41:57'),
(12, 'iyah', '0', 'hbjh', '', 'hvbn@gmail.com', '09093604965', 'TUPV-21-3506', '$2y$10$BmqkBGgqkxhXeNEwhoDMBOG.qjV84D0tKp71HRuWz37CmFkuZ8EFy', '0', 'user', '2024-09-23 14:46:19', '2024-09-23 14:46:19'),
(13, 'angelyn', 'sdgsdg', 'galario', 'gdsgsd', 'angelyngalario@gmail.com', '+6390936049', 'TUPV-21-0696', '$2y$10$HtqBofsn34dIG9XnhnQVk.faEGUEZl44b6LYRHu89LSC5DAK0Nfh.', '955b1c3bc70a9313219b8eeca4ccb25b', 'user', '2024-09-26 08:50:02', '2024-10-09 12:31:22'),
(14, 'dsfsdf', 'dfsdf', 'sdfsdf', 'sadas', 'agie@gmail.com', '09456237512', 'TUPV-23-0024', '$2y$10$NgUk1mGQHCVlN93upDOBzuymA2z0O9Jif7PkQ6q2JI4kNHZyHf9/i', '0', 'user', '2024-10-02 06:25:40', '2024-10-02 06:25:40'),
(15, 'ABBEGAIL', 'OSANO', 'DUCAY', '', 'abbegailducay14@gmail.com', '+639093604963', 'TUPV-23-0001', '$2y$10$hI7fI/Om4Y0m9szd.2qNHeK2px02pclaYOLBqYgsAASduc3rR4Qju', '', 'user', '2024-10-09 12:42:04', '2024-10-09 15:09:23');

-- --------------------------------------------------------

--
-- Table structure for table `user_submissions`
--

CREATE TABLE `user_submissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `birth_date` date NOT NULL,
  `course_id` int(11) NOT NULL,
  `year_section` varchar(50) NOT NULL,
  `year_graduated` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_submissions`
--

INSERT INTO `user_submissions` (`id`, `user_id`, `birth_date`, `course_id`, `year_section`, `year_graduated`, `created_at`, `updated_at`) VALUES
(1, 5, '0000-00-00', 2, 'XZ', 'sdfasfs', '2024-09-09 18:29:14', '2024-09-13 13:40:45'),
(2, 1, '0000-00-00', 2, 'dfhdf', 'ZCZC', '2024-09-09 18:30:05', '2024-09-20 04:31:38'),
(3, 9, '0000-00-00', 0, '0', '', '2024-09-18 07:17:51', '2024-09-18 07:17:51'),
(4, 10, '0000-00-00', 0, '', '', '2024-09-21 11:02:26', '2024-09-21 11:37:31'),
(5, 12, '0000-00-00', 2, '', '', '2024-09-23 15:04:51', '2024-09-23 15:04:51'),
(6, 13, '0000-00-00', 3, '', '', '2024-09-26 08:50:46', '2024-10-08 18:00:17'),
(7, 15, '0000-00-00', 2, '', '', '2024-10-09 14:16:08', '2024-10-09 14:16:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_documents_bookings` (`booking_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `document_id` (`document_id`);

--
-- Indexes for table `slots`
--
ALTER TABLE `slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `transactions_ibfk_2` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cellphone` (`cellphone`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_submissions`
--
ALTER TABLE `user_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=278;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `slots`
--
ALTER TABLE `slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_submissions`
--
ALTER TABLE `user_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`slot_id`) REFERENCES `slots` (`id`);

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_documents_bookings` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`);

--
-- Constraints for table `slots`
--
ALTER TABLE `slots`
  ADD CONSTRAINT `slots_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_submissions`
--
ALTER TABLE `user_submissions`
  ADD CONSTRAINT `user_submissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
