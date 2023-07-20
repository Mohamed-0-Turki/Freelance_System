-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2023 at 10:16 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `freelance`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(255) NOT NULL,
  `CategoryTitle` text NOT NULL,
  `CategoryDescription` text NOT NULL,
  `CategoryPhoto` varchar(255) NOT NULL,
  `CategoryDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryName`, `CategoryTitle`, `CategoryDescription`, `CategoryPhoto`, `CategoryDate`) VALUES
(1, 'Programming', 'Programming is the process of creating a set of instructions that tell a computer how to perform a task.', 'Computer programs (or software) are what makes computers work. Without software, modern computers are just complicated machines for turning electricity into heat. It’s software on your computer that runs your operating system, browser, email, games, movie player – just about everything.', '9374_3426526.jpg', '2020-04-16 00:00:00'),
(3, 'Cook', 'You will cook dishes that will delight our customers with their taste and timely delivery.', 'An excellent cook must be able to follow instructions in cooking and delivering well-prepared meals. They must be deft in moving around the kitchen and apt in multi-tasking. Experience in using various ingredients and cooking techniques is also important.', '4137_double-hamburger-isolated-white-background-fresh-burger-fast-food-with-beef-cream-cheese.jpg', '2022-04-16 00:00:00'),
(4, 'Commerce', 'Commerce involves the exchange of goods and services, often for a profit', 'Commerce is essential to the global economy. It encompasses everything related to the buying and selling of goods and services at both the wholesale and retail levels.', '7605_634.jpg', '2021-04-16 00:00:00'),
(5, 'Other', 'Uncategorized jobs', 'There are too many jobs here for which there is no proper classification.', '5857_3185113.jpg', '2024-04-16 00:00:00'),
(102, 'Engineering Oil Gas Energy', 'Engineering Oil Gas Energy Jobs in Egypt', 'Oil and gas engineering refers to the design, implementation and operation of technical processes focused on the exploration and production of crude oil and natural gas. Once oil and gas are discovered, oil and gas engineers become involved in the process of extracting these resources from reservoirs, often working with specialists such as geoscientists to understand the underlying rock formation, determine the best drilling methods and to monitor daily operations.', '7808_44655.jpg', '2023-04-24 00:00:00'),
(103, 'Android jobs', 'An Android Developer is a Software Developer who specializes in designing applications for the Android marketplace.', 'An Android Developer is a Software Developer who specializes in designing applications for the Android marketplace. The Android marketplace is the direct competitor to Apple&#039;s app store. This means most of an Android Developer&#039;s job revolves around creating the apps we use on our smartphones and tablets.\r\n', '9589_19021594.jpg', '2023-04-25 00:00:00'),
(105, 'Doctor', 'A doctor is responsible for all sides of care of a patient.', 'A doctor is responsible for all sides of care of a patient. They diagnose, educate, and treat patients to ensure that they have the best possible care. A few of the main duties of a doctor are performing diagnostic tests, recommending specialists for patients, document patient&#039;s medical history, and educating patients.\r\n', '7685_young-handsome-physician-medical-robe-with-stethoscope.jpg', '2023-05-01 23:29:12');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `FeedbackID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Subject` varchar(255) NOT NULL,
  `Message` text NOT NULL,
  `FeedbackDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `JobID` int(11) NOT NULL,
  `JobTitle` varchar(255) NOT NULL,
  `JobSalary` int(11) NOT NULL,
  `JobDescription` text NOT NULL,
  `JobSkills` text NOT NULL,
  `JobDate` datetime NOT NULL,
  `MemberID` varchar(255) NOT NULL,
  `CatID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`JobID`, `JobTitle`, `JobSalary`, `JobDescription`, `JobSkills`, `JobDate`, `MemberID`, `CatID`) VALUES
(1, 'web developer', 2500, 'A web developer is a programmer who develops World Wide Web applications using a client–server model. The applications typically use HTML, CSS, and JavaScript in the client, and any general-purpose programming language in the server.\r\n', 'HTML-CSS-JS-PHP-MySQL', '2023-04-17 00:00:00', '1', 1),
(102, 'Medical Claims Coordinator (Medicine universities ONLY)', 500, 'Performing technical audit on medical claims (inpatient and outpatient).\r\nAuditing of invoice and Ensuring that all the medical bills are within the contracted price list and the approval criteria according to Mersal policy.\r\nJustifying the rejection reasons on system in rejection cases to facilitate the reconciliation process.\r\nEnsuring the best service given to the patients by providers.\r\nReport to the direct manager any irregularity or problems noticed during the audit and/or closing of claims.\r\nPrepares reports by collecting, analyzing, and summarizing information of the reviewed claims to assess the quality of our payment and create a cost effective solution.\r\nCoordinate workflow &amp; meet deadlines.', 'Bachelor&#039;s Degree in Medicine and Surgery.-Strong communication skills.\r\n', '2023-05-01 00:00:00', '1', 105),
(113, 'Flutter Developer', 5000, 'We are looking for an experienced Flutter Developer who will join our talented software team that works on mission-critical applications. Your duties will include managing Flutter (Android, iOS) application development while providing expertise in the full software development lifecycle, from concept and design to testing.', 'Good communication skills.-Strong problem-solving skills.-Team worker.-Very comfortable learning new technologies, tools, and platforms.-Highly motivated.-Initiative and passionate.-Strong problem-solving skills.-Team worker.-Very comfortable learning new technologies, tools,and platforms.-Highly motivated.-Have shipped applications to the App Store or Google Play Store.', '2023-05-09 00:00:00', '7743439441683647536', 103),
(115, 'Executive Chef', 33000, 'Our restaurant is searching for a creative and motivated sous chef to join our talented kitchen team. In this position, you will act as the third in command in our kitchen, following and enforcing our executive chef’s and Head chefs’ requirements and guidelines. Our ideal candidate is a creative professional who is willing to participate in creating delicious seasonal menus and meal designs.', 'Proven working experience as a Head Chef-Excellent record of kitchen management-Ability to spot and resolve problems efficiently-Capable of delegating multiple tasks-Communication and leadership skills-Keep up with cooking trends and best practices-Working knowledge of various computer software programs (MS Office, restaurant management software, POS)', '2023-05-09 00:00:00', '7743439441683647536', 3);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `MessageID` int(11) NOT NULL,
  `JobPublisher` varchar(255) NOT NULL,
  `FreelancerID` varchar(255) NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `JobID` int(11) NOT NULL,
  `FreelancerSkills` text DEFAULT NULL,
  `Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `UserRole` enum('Admin','Client','Freelancer') NOT NULL,
  `PhoneNumber` int(11) DEFAULT NULL,
  `UserDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Name`, `Email`, `Password`, `UserRole`, `PhoneNumber`, `UserDate`) VALUES
('1', 'Admin', 'Admin@gmail.com', '$2y$10$P3pL444ecQSxVScURIW.De2PbY.OPGFHWFJHUPzHJu2Vr9ghr.1ve', 'Admin', NULL, '2023-04-27 00:00:00'),
('1683326213', 'subway', 'subway123123@gmail.com', '$2y$10$UPnI5EGR7gcJHXznsnHuAuH91WLwAw67zMWreuaw/Wt5BZfe0QGzW', 'Freelancer', 1283856454, '2023-05-06 01:36:53'),
('1914374711683367008', 'Ahmed', 'Ahmedt234234@gmail.com', '$2y$10$jNlx09ZVv5Kwz94Z0WyGmePHSox0CVURHvwebeUCQ296psgwXmgs.', 'Client', NULL, '2023-05-06 12:56:48'),
('2', 'Client', 'client@gmail.com', '$2y$10$PgNMfgz9Lho22D1b/Q/uD.wC3IObRUJQVMBLKvFrHfY1eTVcvnEde', 'Client', NULL, '2023-04-27 00:00:00'),
('3', 'Freelancer', 'freelancer@gmail.com', '$2y$10$PgNMfgz9Lho22D1b/Q/uD.wC3IObRUJQVMBLKvFrHfY1eTVcvnEde', 'Freelancer', 1141074777, '2023-04-27 00:00:00'),
('3742929471683625580', 'mmm', 'mmmmm@ggg', '$2y$10$kbfyVkdeCYjUt9XiCCrVh.23a.ljzzwA/Njp/E6p8m/V.x0Si8T76', 'Client', NULL, '2023-05-09 12:46:20'),
('39556151683626337', 'Mohamed', 'M@dcs.com', '$2y$10$ngaXNA1Do745a6mjo8Z3ve8/4Dz9mEPw6vKlZWDqi1ga3KFJISnE6', 'Client', 5, '2023-05-09 12:58:57'),
('4886991151683648966', 'ffff', 'fffff@mail.com', '$2y$10$Yco.t6lrGGdLeYzEGj8nSuEFKbXpzeFtmq8s7K.e8.bZCbXO5g9I2', 'Freelancer', 1141074777, '2023-05-09 19:16:06'),
('5674040541683626138', 'Mohamed', 'mohadsdmed@gmail.com', '$2y$10$tLYiERL1Fg3ALaDiDCa11eMwezLAGpx3oS.Xks8VkuhzOz5ZPOpSa', 'Freelancer', 0, '2023-05-09 12:55:38'),
('750441691683532087', 'Mohamed', 'freelancer1234@gmail.com', '$2y$10$ZW1JTiO1cINz4pjNgrskQOH9Olk0dZKQCtQk0LqTwGhiUA/0ug1S6', 'Freelancer', 1119759939, '2023-05-08 10:48:07'),
('7743439441683647536', 'Mohamed', 'mohamed@gmail.com', '$2y$10$DvB45pCvA/Bl5BgktKArpeQOgPl6f/.ZpjPQUwNFtc9xWDtAYIuG2', 'Client', NULL, '2023-05-09 18:52:16'),
('8229967761683531720', 'Mohamed', 'mohamed4444@gmail.com', '$2y$10$gpcuRs21NJ1BBG6UMcNYa.YXVyRs0Kfbxy16G0EAgcDkCDqQcztg.', 'Client', NULL, '2023-05-08 10:42:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`),
  ADD UNIQUE KEY `CategoryName` (`CategoryName`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`FeedbackID`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`JobID`),
  ADD KEY `CatID` (`CatID`),
  ADD KEY `MemberID` (`MemberID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`MessageID`),
  ADD KEY `JobID` (`JobID`),
  ADD KEY `CategoryID` (`CategoryID`),
  ADD KEY `FreelancerID` (`FreelancerID`),
  ADD KEY `JobPublisher` (`JobPublisher`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `FeedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `JobID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `MessageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `CatID` FOREIGN KEY (`CatID`) REFERENCES `categories` (`CategoryID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `MemberID` FOREIGN KEY (`MemberID`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `CategoryID` FOREIGN KEY (`CategoryID`) REFERENCES `categories` (`CategoryID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FreelancerID` FOREIGN KEY (`FreelancerID`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `JobID` FOREIGN KEY (`JobID`) REFERENCES `jobs` (`JobID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `JobPublisher` FOREIGN KEY (`JobPublisher`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
