-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 24, 2023 at 08:25 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
(115, 'IT Software Development Jobs', 'Apply to Software Engineer, Developer, Junior Software Engineer and more!', 'Software Engineering Jobs in Egypt· Computer Science &amp; Computer Engineering Fresh · Junior Software Engineer · Senior Odoo Developer.', '6780_www.usnews.jpg', '2023-07-24 20:36:22'),
(116, 'Android Mobile', 'Search Android jobs in Egypt with company ratings &amp; salaries.', 'Android Mobile Developer jobs available on Ifreelance.com. Apply to Android Developer, Mobile Developer, Senior .NET Developer and more!', '6254_Android_phone.jpg', '2023-07-24 20:39:18'),
(117, 'Education Teaching Jobs', 'New Teaching Jobs &amp; Vacancies this Month / Apply for the latest Teaching careers in Egypt ', 'As the majority of teaching jobs in Egypt are for certified public school teachers, there are not many ESL teaching jobs in Egypt for non-certified teachers.', '8442_Summer-Jobs-for-Teachers.jpg', '2023-07-24 20:42:12'),
(118, 'Fashion Jobs', 'Fashion, Sneakers for men, women, and kids.', 'Fashion is a form of self-expression with a specific context, such as time, place and purpose. Example of these are clothing, footwear, lifestyle.', '8283_img_2_03930c00-69d1-461b-89ba-d64871e48e0e.png', '2023-07-24 20:44:40'),
(119, 'Sales/Retail', 'A Retail Sales Representative is a customer-oriented professional who assists shoppers in finding the right products', 'Retail sales tracks consumer demand for finished goods by measuring the purchases of durable and non-durable goods over a defined period of time.', '2712_Retail-Sales-2.jpg', '2023-07-24 20:46:50'),
(120, 'Analyst/Research', 'Analyst Research Laboratories is a GLP, GMP and GCLP accredited, US-FDA inspected Chemical Analytical Contract Laboratory.', 'Analyst Research Jobs in Egypt162 Jobs found · GIS Database Manager · Project Coordinator · Cost Accountant · Digital Marketing Executive · Budget Analyst.', '2796_equity-research-career-path-and-qualifications_round1-ec5d344bad634c5999cecd376a547d7f.png', '2023-07-24 20:48:58'),
(121, 'Engineering Telecom Technology', 'Technology, engineering and telecom marketing strategies can help you dominate your market and gain a competitive advantage.', 'Telecommunications engineering is a subfield of electronics engineering which seeks to design and devise systems of communication at a distance.', '9300_networking-technologies.jpg', '2023-07-24 20:51:00');

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

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`FeedbackID`, `Name`, `Email`, `Subject`, `Message`, `FeedbackDate`) VALUES
(23, 'mohamed', 'mohamed@gmail.com', 'hello world', 'erorr erorr erorr erorr erorr erorr erorr erorr erorr erorr erorr erorr erorr erorr erorr erorr ', '2023-07-24 21:14:38'),
(24, 'ahmed', 'ahmed123@gmail.com', 'hi iam ahmed', 'mmcd cadscn scdcsnc cscmsc csdsm\r\n', '2023-07-24 21:15:28'),
(25, 'mostafa', 'mostafa545@gmail.com', 'hi iam mostafa', 'hack hack hack', '2023-07-24 21:16:00');

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
(122, 'Technical Support Specialist', 700, '- Providing IT assistance to staff.\r\n- Offer technical support to company staff and troubleshoot computer problems.\r\n- Training company staff on hardware functionality and software programs.\r\n- Resolving logged errors in a timely manner.\r\n- Monitoring hardware, software, and system performance metrics.\r\n- Updating computer software. as well as upgrading hardware and systems.\r\n- Maintaining databases and ensuring system security.\r\n- Documenting processes and performing diagnostic tests.\r\n- Keeping track of technological advancements and trends in IT support.\r\n- Review diagnostics and assess the functionality and efficiency of systems.\r\n- Implement security measures.', 'A bachelor&#039;s degree in computer science, information technology, or similar.-2-4 years of experience as an IT support specialist.-Exceptional ability to provide technical support and resolve queries.-In-depth knowledge of computer hardware, software, and networks.-Ability to determine IT needs and train end-users.-Proficiency in IT help desk software, such as Fresh service.-Experience in documenting processes and monitoring performance metrics.-Advanced knowledge of database maintenance and system security.-Ability to keep up with technical innovation and trends in IT support.-Exceptional interpersonal and communication skills.', '2023-07-24 20:56:45', '1', 121),
(123, 'Customer Service Manager', 1000, 'Negotiating all contracts with prospective clients.To build a strong relationship with customers and Take the extra mile to engage the customer.\r\nTo resolve product or service problems by clarifying the customer&#039;s complaint; determining the cause of the problem; selecting and explaining the best solution to solve the problem and following up to ensure resolution.\r\nHosting clients in events, draft newsletters and dispatch them to the clients to stay tuned.\r\nSend greetings to clients in social events (Ramadan, Eid , New Year….etc) by mail or SMS.\r\nCollecting installments from customers.\r\nNegotiating all contracts with prospective clients.', 'Proven Working Experience as Customer Service Specialist not less than 7years.-Real Estate Background is must-Excellent Communication, Presentation and problem solving skills.-Excellent user of Excel and MS Office use to merge information and emails.-Males Only.', '2023-07-24 20:59:00', '1', 119),
(124, 'Structural Technical Office Engineer', 1500, 'To perform the duties of Structural Technical Office Engineer, which includes preparing structural shop drawings bar bending Schedules, bill of quantities.\r\nDesign review.\r\nCollaborate and Coordinate with all disciplines ( Arch. , MEP, Civil works) through  Revit drawings .\r\nRaising RFI if needed due clashes or design problems (if needed).\r\nValue engineering of structural design to minimize cost if possible.\r\nPreparation of method of statements.', 'Experience in drafting software ( Revit).-Problem-solving and judgment skills.-Adaptability and communication skills.', '2023-07-24 21:00:51', '1', 115),
(125, 'Android Developer', 3000, '    Develop new features for our existing Android applications, debugging/fixing problems.\r\n    Collaborate with cross-functional teams to define, design, and ship new features. A Familiarity of Firebase analytics, cloud messaging, real-time database, storage and crash reporting.\r\n    Unit-test code for robustness, including edge cases, usability, and general reliability.\r\n    Work with the QA team.\r\n    Participate in team meetings.\r\n    Report regularly on work progress.\r\n    Work on bug fixing and improving application performance.\r\n    Good knowledge of Android SDK, different versions of Android, and how to deal with different screen sizes.\r\n    Ability to understand business requirements and translate them into technical.', ' Solid understanding of the full mobile development life cycle.-Good familiarity with RESTful APIs to connect Android applications to back-end services.-Understand the challenges being addressed by an engagement and collaborate with team members, clients, and stakeholders to deliver a valuable technical solution.-Good knowledge of Android UI design principles, patterns, and best practices.-Have a good experience with offline storage, threading, and performance tuning.', '2023-07-24 21:04:28', '8353297891690221742', 116),
(126, 'Operations and Logistics Coordinator', 6000, 'We are looking for an Motivated logistics and Operations coordinators to manage all aspects of logistics throughout our supply chain. The logistics and Operation coordinator will be responsible for organizing and providing efficient Operations and storage solutions.\r\n\r\n    Plan and follow up daily logistics, customs clearance, warehouse, transportation, and distribution.\r\n    Arrange warehouse, plan routes and process shipments like Loading Products to warehouses or warehouses to stores.\r\n    Maintaining inventory storage space in the store’s warehouse.\r\n    Ensuring the safe and timely pick-up and delivery of shipments.\r\n    Monitoring shipments, costs, timelines, and productivity.\r\n    Ensure effective and timely implementation of all Logistics daily operational goals.\r\n    Developing, monitoring, writing and updating product content contained on eCommerce website.\r\n    Cultivating standards, systems and best practices for content creation, distribution, maintenance, and new product listings.', 'To be Experienced is not mandatory for this Position , we are looking for Self Motivated people can be adapted.-Preferred to be Experienced in E-commerce Logistic and Retail Fashion. -Time Management, managing one&#039;s own time and the time of others.-Reading comprehension, understanding written sentences and paragraphs in work related documents.-Speaking, talking to others to convey information effectively. ', '2023-07-24 21:06:17', '8353297891690221742', 118),
(127, 'Senior Android Developer', 50000, 'NEXT Munich is a highly experienced, long standing App developer from Germany with a vast number of fascinating App projects and renown international clients. We are very excited to announce our venture into Egypt to broaden our developer base and to attract great talent. We have just opened our branch office in The GrEEK Campus Downtown Cairo! Do you feel like working intensively on the mobile platform for iOS and/or Android, developing cool apps for connected devices or dealing with exciting app scenarios both in a B2C and a B2B environment? Are you a dedicated developer looking to newly enter the workforce, or do you want to take the next step in your career? We are looking for you: outstanding talents who are passionate about all our App topics! As soon as you like we are hiring:', 'University degree in computer science or comparable education\r\n    -Very good knowledge of Java, Kotlin and Jetpack Compose\r\n    -3+ years experience in developing Android Apps\r\n    -Good technical understanding of web technologies such as XML, HTML, JSON-High quality standards and a sure flair for structured and readable code-Visual Studio for Mac, Git, Jira, Bitbucket, Confluence-Very good English, in speaking and writing-Nice to have: Experience with Xamarin Native Apps and MvvmCross 4', '2023-07-24 21:09:25', '2', 116);

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

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`MessageID`, `JobPublisher`, `FreelancerID`, `CategoryID`, `JobID`, `FreelancerSkills`, `Date`) VALUES
(28, '1', '3', 115, 124, 'skill-skill-skill-skill-skill', '2023-07-24 21:11:56'),
(29, '1', '3', 121, 122, 'skill-skill-skill-skill-skill', '2023-07-24 21:12:17'),
(30, '1', '3', 119, 123, 'one-two-three-four\r\n', '2023-07-24 21:12:49'),
(31, '2', '681916321690222269', 116, 127, 'skill\r\n-skill\r\n-skill\r\n-skill', '2023-07-24 21:13:30');

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
('1', 'Admin', 'admin@gmail.com', '$2y$10$h8cjYcNAuj7fYW0ivFlvM..8nvrghhG4uB2q4WcKrq/SQzf5u9KwS', 'Admin', NULL, '2023-07-23 01:26:21'),
('2', 'Client', 'client@gmail.com', '$2y$10$r62PGWFfZ99Ep9G8bUQ25.YJ.1.NfoBDXdAWtIUc5Lq7Nzw7Z9zqq', 'Client', NULL, '2023-07-23 01:27:46'),
('3', 'Freelancer', 'freelancer@gmail.com', '$2y$10$u0sxmphjNdmYSoRtl/JCY.9WsWEk1FXzB33KbppPne2NyTENDPRdO', 'Freelancer', 1141074777, '2023-07-23 01:28:57'),
('681916321690222269', 'ahmed', 'ahmed@gmail.com', '$2y$10$bed6vIobWLEX6Pq7/EGr6O0jrGyWI8x6sc4KrV/sLo3Im/JMAEvx.', 'Freelancer', 1141074777, '2023-07-24 21:11:09'),
('8353297891690221742', 'mohamed-client', 'mohamedclient@gmail.com', '$2y$10$sCGT7eCuQzvDhGXsBgbP1Ol5V2H25xqstIf2DobyB8hDwbNX5sERG', 'Client', NULL, '2023-07-24 21:02:22');

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
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `FeedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `JobID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `MessageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
