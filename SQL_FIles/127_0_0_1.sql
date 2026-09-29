-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2026 at 03:10 AM
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
-- Database: `ems_db`
--
CREATE DATABASE IF NOT EXISTS `ems_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ems_db`;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs_tbl`
--

CREATE TABLE `activity_logs_tbl` (
  `log_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs_tbl`
--

INSERT INTO `activity_logs_tbl` (`log_id`, `user_name`, `action`, `description`, `created_at`) VALUES
(22, 'jared', 'Update Fire Extinguisher', 'Updated fire extinguisher FE-007', '2026-09-25 05:41:44'),
(23, 'jared', 'Delete Fire Extinguisher', 'Deleted fire extinguisher FE-007', '2026-09-25 05:41:59'),
(24, 'jared', 'Add Fire Extinguisher', 'Added fire extinguisher FE-007', '2026-09-25 05:44:17'),
(25, 'jared', 'Inspect Fire Extinguisher', 'Inspect fire extinguisher FE-001', '2026-07-25 05:45:16'),
(26, 'jared', 'Delete Fire Extinguisher', 'Deleted fire extinguisher FE-007', '2026-09-25 05:50:15'),
(27, 'jared', 'Delete Fire Extinguisher', 'Deleted fire extinguisher FE-006', '2026-09-25 05:50:17'),
(28, 'jared', 'Delete Fire Extinguisher', 'Deleted fire extinguisher FE-005', '2026-09-25 07:08:16'),
(29, 'jared', 'Update Fire Extinguisher', 'Updated fire extinguisher FE-002', '2026-09-25 07:35:44'),
(30, 'jared', 'Add Fire Extinguisher', 'Added fire extinguisher FE-005', '2026-09-25 07:36:26'),
(31, 'jared', 'Inspect Fire Extinguisher', 'Inspect fire extinguisher FE-001', '2026-09-25 07:50:01'),
(32, 'jared', 'Inspect Fire Extinguisher', 'Inspect fire extinguisher FE-002', '2026-09-25 08:17:31'),
(33, 'jared', 'Inspect Fire Extinguisher', 'Inspect fire extinguisher FE-002', '2026-09-25 08:34:27'),
(34, 'jared', 'Inspect Fire Extinguisher', 'Inspect fire extinguisher FE-002', '2026-09-25 08:49:27'),
(35, 'jared', 'Inspect Fire Extinguisher', 'Inspect fire extinguisher FE-004', '2026-09-25 08:52:11'),
(36, 'jared', 'Inspect Fire Extinguisher', 'Inspect fire extinguisher FE-004', '2026-09-25 09:03:06'),
(37, 'jared', 'Add Fire Extinguisher', 'Added fire extinguisher FE-006', '2026-09-28 01:03:00'),
(38, 'error while getting employee name', 'Update Fire Extinguisher', 'Updated fire extinguisher FE-006', '2026-09-28 06:28:16'),
(39, 'NOEL VICTOR PAGATPAT', 'Update Fire Extinguisher', 'Updated fire extinguisher FE-006', '2026-09-28 06:29:24'),
(40, 'NOEL VICTOR PAGATPAT', 'Update Fire Extinguisher', 'Updated fire extinguisher FE-005', '2026-09-28 06:38:07'),
(41, 'NOEL VICTOR PAGATPAT', 'Delete Fire Extinguisher', 'Deleted fire extinguisher FE-006', '2026-09-28 06:42:09'),
(42, 'Marivic Nagpala', 'Inspect Fire Extinguisher', 'Inspect fire extinguisher FE-001', '2026-09-28 07:56:00'),
(43, 'MARILYN LACANDAZO', 'Inspect Fire Extinguisher', 'Inspect fire extinguisher FE-001', '2026-09-28 08:23:37'),
(44, 'MARILYN LACANDAZO', 'Add Fire Extinguisher', 'Added fire extinguisher FE-006', '2026-09-28 08:30:14'),
(45, 'MARILYN LACANDAZO', 'Update Fire Extinguisher', 'Updated fire extinguisher FE-006', '2026-09-28 08:31:01'),
(46, 'MARILYN LACANDAZO', 'Delete Fire Extinguisher', 'Deleted fire extinguisher FE-006', '2026-09-28 08:33:17'),
(47, 'error while getting employee name', 'Add Fire Extinguisher', 'Added fire extinguisher FE-006', '2026-09-29 00:58:32'),
(48, 'error while getting employee name', 'Add Fire Extinguisher', 'Added fire extinguisher FE-007', '2026-09-29 01:04:13'),
(49, 'error while getting employee name', 'Add Fire Extinguisher', 'Added fire extinguisher FE-008', '2026-09-29 01:05:43');

-- --------------------------------------------------------

--
-- Table structure for table `fire_extinguishers_tbl`
--

CREATE TABLE `fire_extinguishers_tbl` (
  `extinguisher_id` int(11) NOT NULL,
  `extinguisher_code` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `capacity` varchar(50) NOT NULL,
  `location` varchar(255) NOT NULL,
  `branch` varchar(100) NOT NULL,
  `manufactured_date` date DEFAULT NULL,
  `class` varchar(50) NOT NULL,
  `placement` varchar(50) NOT NULL,
  `condition_status` enum('Good','Not Good') NOT NULL DEFAULT 'Good',
  `remarks` varchar(255) DEFAULT NULL,
  `expiration_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `archived` tinyint(1) NOT NULL DEFAULT 0,
  `refilled_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fire_extinguishers_tbl`
--

INSERT INTO `fire_extinguishers_tbl` (`extinguisher_id`, `extinguisher_code`, `type`, `capacity`, `location`, `branch`, `manufactured_date`, `class`, `placement`, `condition_status`, `remarks`, `expiration_date`, `created_at`, `updated_at`, `archived`, `refilled_date`) VALUES
(1, 'FE-001', 'HCFC', '10 lbs', 'Main Lobby', '', '2023-01-15', 'ABC', 'Wall Mounted', 'Good', 'ASBDJBHDAS', '2029-09-28', '2026-09-22 00:58:02', '2026-09-28 08:23:37', 0, '2026-09-28'),
(2, 'FE-002', 'HCFC', '10 lbs', 'Reception Area', '', '2023-02-20', 'ABC', 'Wall Mounted', 'Good', 'sdfsdfsd', '2029-09-25', '2026-09-22 00:58:02', '2026-09-25 08:49:27', 0, '2026-09-25'),
(3, 'FE-003', 'AFFF', '5 lbs', 'Server Room', '', '2022-06-10', 'BC', 'Wall Mounted', 'Not Good', 'No visible damage', '2027-06-10', '2026-09-22 00:58:02', '2026-09-28 09:03:55', 0, NULL),
(4, 'FE-004', 'AFFF', '20 lbs', 'storage', '', '2023-03-05', 'ABC', 'Floor Stand', 'Good', 'asdadasdasdas', '2028-03-05', '2026-09-22 00:58:02', '2026-09-25 09:03:06', 0, NULL),
(38, 'FE-005', 'HCFC', '50 lbs', 'BUILDING A', '', '2026-09-19', 'B', 'Cabinet', 'Good', 'sdfsdfdsfsdf', '2029-09-19', '2026-09-25 07:36:26', '2026-09-28 06:38:07', 0, NULL),
(41, 'FE-006', 'Dry Chemical', '50 lbs', 'BUILDING A', 'laguna', '2026-09-17', 'ABC', 'Wall Mounted', 'Good', 'asdadas', '2029-09-17', '2026-09-29 00:58:32', '2026-09-29 00:58:32', 0, NULL),
(42, 'FE-007', 'Dry Chemical', '20 lbs', 'BUILDING A', 'Cavite', '2026-09-25', 'ABC', 'Floor Standing', 'Good', '', '2029-09-25', '2026-09-29 01:04:13', '2026-09-29 01:04:13', 0, NULL),
(43, 'FE-008', 'AFFF', '20 lbs', 'BUILDING A', 'Cebu', '2026-09-26', 'BC', 'Floor Standing', 'Good', '', '2029-09-26', '2026-09-29 01:05:43', '2026-09-29 01:05:43', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inspection_checklist_tbl`
--

CREATE TABLE `inspection_checklist_tbl` (
  `inspect_id` int(11) NOT NULL,
  `extinguisher_code` varchar(50) NOT NULL,
  `branch` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `capacity` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `class` varchar(50) NOT NULL,
  `date_inspected` date NOT NULL,
  `inspected_by` varchar(100) NOT NULL,
  `verified_and_approved_by` varchar(100) NOT NULL,
  `evaluation_status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `action_taken` varchar(50) DEFAULT NULL,
  `target_date_of_implementation` date DEFAULT NULL,
  `is_seal_ok` tinyint(1) DEFAULT 0,
  `is_pin_ok` tinyint(1) DEFAULT 0,
  `is_pressure_ok` tinyint(1) DEFAULT 0,
  `is_hose_ok` tinyint(1) DEFAULT 0,
  `is_nozzle_ok` tinyint(1) DEFAULT 0,
  `is_belt_ok` tinyint(1) DEFAULT 0,
  `is_cylinder_body_ok` tinyint(1) DEFAULT 0,
  `is_demarcation_line_ok` tinyint(1) DEFAULT 0,
  `is_signage_ok` tinyint(1) DEFAULT 0,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inspection_checklist_tbl`
--

INSERT INTO `inspection_checklist_tbl` (`inspect_id`, `extinguisher_code`, `branch`, `location`, `capacity`, `type`, `class`, `date_inspected`, `inspected_by`, `verified_and_approved_by`, `evaluation_status`, `action_taken`, `target_date_of_implementation`, `is_seal_ok`, `is_pin_ok`, `is_pressure_ok`, `is_hose_ok`, `is_nozzle_ok`, `is_belt_ok`, `is_cylinder_body_ok`, `is_demarcation_line_ok`, `is_signage_ok`, `status`) VALUES
(3, 'FE-005', '', 'storage', '20 lbs', 'Foam', 'AB', '2026-09-23', 'juan', 'juan', 'Approved', 'Repaired', '2026-09-25', 0, 0, 1, 1, 1, 0, 1, 0, 0, 1),
(5, 'FE-005', '', 'storage', '20 lbs', 'Foam', 'AB', '2026-09-23', 'juan', 'juan', 'Rejected', 'Refilled', '2026-09-11', 1, 1, 1, 0, 0, 1, 0, 1, 1, 0),
(6, 'FE-005', '', 'storage', '20 lbs', 'Foam', 'AB', '2026-09-23', 'juan', 'sdfsdf', 'Pending', 'Refilled', '2026-09-26', 1, 1, 1, 0, 1, 1, 1, 1, 1, 0),
(7, 'FE-005', '', 'storage', '20 lbs', 'Foam', 'AB', '2026-09-23', 'juan', 'juan', 'Pending', 'Refilled', '2026-09-24', 1, 1, 1, 1, 0, 1, 1, 1, 1, 0),
(8, 'FE-005', '', 'storage', '20 lbs', 'Foam', 'AB', '2026-09-23', 'juan', 'n/a', 'Pending', 'Refilled', '2026-09-24', 1, 1, 1, 1, 1, 1, 0, 1, 1, 0),
(9, 'FE-005', '', 'storage', '20 lbs', 'Foam', 'AB', '2026-09-23', 'juan', 'juan', 'Pending', 'No Action', '2026-10-01', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(10, 'FE-005', '', 'storage', '20 lbs', 'Foam', 'AB', '2026-09-23', 'ert', 'erter', 'Pending', 'Refilled', '2026-09-17', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(11, 'FE-005', '', 'storage', '20 lbs', 'Foam', 'AB', '2026-09-23', 'rtyrtyrt', 'rtyrtyrtyrt', 'Pending', 'Refilled', '2026-09-18', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(12, 'FE-005', '', 'storage', '20 lbs', 'Foam', 'AB', '2026-09-23', 'fdgdfgdfg', 'dfgdfgdf', 'Pending', 'Refilled', '2026-09-24', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(13, 'FE-005', '', 'storage', '20 lbs', 'Foam', 'AB', '2026-09-23', 'fghfhfgh', 'fghfghfgh', 'Pending', 'No Action', '2026-09-17', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(14, 'FE-005', '', 'storage', '20 lbs', 'Foam', 'AB', '2026-09-23', 'fghfghfgh', 'fghfghfgh', 'Pending', 'Refilled', '2026-09-24', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(15, 'FE-001', '', 'Main Lobby', '10 lbs', 'ABC Dry Chemical', 'ABC', '2026-09-25', 'jaja I DEV', 'N/A', 'Pending', 'Refilled', '2026-09-23', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(16, 'FE-001', '', 'Main Lobby', '10 lbs', 'ABC Dry Chemical', 'ABC', '2026-09-25', 'dfdgdfgd', 'N/A', 'Pending', 'Refilled', '2026-09-15', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(17, 'FE-001', '', 'Main Lobby', '10 lbs', 'ABC Dry Chemical', 'ABC', '2026-09-25', 'juan', 'N/A', 'Pending', 'Refilled', '2026-09-16', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(18, 'FE-001', '', 'Main Lobby', '10 lbs', 'ABC Dry Chemical', 'ABC', '2026-09-25', 'dfgdfgdfg', 'N/A', 'Pending', 'Refilled', '2026-09-27', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(19, 'FE-001', '', 'Main Lobby', '10 lbs', 'ABC Dry Chemical', 'ABC', '2026-09-25', 'fghfghfgh', 'N/A', 'Pending', 'Refilled', '2026-09-17', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(20, 'FE-001', '', 'Main Lobby', '10 lbs', 'ABC Dry Chemical', 'ABC', '2026-09-25', 'dsgdfs', 'N/A', 'Pending', 'Refilled', '2026-09-17', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(21, 'FE-001', '', 'Main Lobby', '10 lbs', 'HCFC', 'ABC', '2026-09-25', 'dsfdsfsdf', 'N/A', 'Pending', 'Refilled', '2026-09-08', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(22, 'FE-002', '', 'Reception Area', '10 lbs', 'HCFC', 'ABC', '2026-09-25', 'jaja.devs', 'N/A', 'Pending', 'Refilled', '2026-09-22', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(23, 'FE-002', '', 'Reception Area', '10 lbs', 'HCFC', 'ABC', '2026-09-25', 'SADFSDFSD', 'N/A', 'Pending', 'Refilled', '2026-09-15', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(24, 'FE-002', '', 'Reception Area', '10 lbs', 'HCFC', 'ABC', '2026-09-25', 'juan', 'N/A', 'Pending', 'Refilled', '2026-09-14', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(25, 'FE-004', '', 'storage', '20 lbs', 'AFFF', 'ABC', '2026-09-25', 'juan', 'N/A', 'Pending', 'Repaired', '2026-09-15', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(26, 'FE-004', '', 'storage', '20 lbs', 'AFFF', 'ABC', '2026-09-25', 'jaja', 'N/A', 'Pending', 'Repaired', '2026-09-16', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(27, 'FE-004', '', 'storage', '20 lbs', 'AFFF', 'ABC', '2026-09-25', 'jaja', 'N/A', 'Pending', 'Repaired', '2026-09-16', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(28, 'FE-001', '', 'Main Lobby', '10 lbs', 'HCFC', 'ABC', '2026-09-28', 'Marivic Nagpala', 'N/A', 'Pending', 'Refilled', '2026-09-16', 1, 1, 1, 1, 0, 1, 1, 1, 1, 0),
(29, 'FE-001', '', 'Main Lobby', '10 lbs', 'HCFC', 'ABC', '2026-09-28', 'MARILYN LACANDAZO', 'N/A', 'Pending', 'Refilled', '2026-09-11', 1, 0, 1, 1, 1, 1, 1, 0, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs_tbl`
--
ALTER TABLE `activity_logs_tbl`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `fire_extinguishers_tbl`
--
ALTER TABLE `fire_extinguishers_tbl`
  ADD PRIMARY KEY (`extinguisher_id`),
  ADD UNIQUE KEY `extinguisher_code` (`extinguisher_code`);

--
-- Indexes for table `inspection_checklist_tbl`
--
ALTER TABLE `inspection_checklist_tbl`
  ADD PRIMARY KEY (`inspect_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs_tbl`
--
ALTER TABLE `activity_logs_tbl`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `fire_extinguishers_tbl`
--
ALTER TABLE `fire_extinguishers_tbl`
  MODIFY `extinguisher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `inspection_checklist_tbl`
--
ALTER TABLE `inspection_checklist_tbl`
  MODIFY `inspect_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--

