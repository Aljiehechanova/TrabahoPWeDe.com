/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.3-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: u227479843_TrabahoPWede
-- ------------------------------------------------------
-- Server version	11.8.3-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `activitylog`
--

DROP TABLE IF EXISTS `activitylog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activitylog` (
  `activity_id` int(255) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `complete` varchar(255) NOT NULL,
  `offered` varchar(255) NOT NULL,
  `available` varchar(255) NOT NULL,
  `entry_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`activity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activitylog`
--

/*!40000 ALTER TABLE `activitylog` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `activitylog` VALUES
(1,1,101,'0','0','1','2025-05-01 00:00:00'),
(2,1,102,'0','1','1','2025-05-02 00:00:00'),
(3,1,103,'1','1','1','2025-05-03 00:00:00'),
(4,2,104,'0','0','1','2025-05-04 00:00:00'),
(5,2,105,'1','1','1','2025-05-05 00:00:00'),
(6,3,106,'0','1','1','2025-05-06 00:00:00'),
(7,3,107,'0','0','1','2025-05-07 00:00:00'),
(8,3,108,'1','1','1','2025-05-08 00:00:00'),
(9,1,201,'0','0','1','2025-01-10 00:00:00'),
(10,1,202,'0','1','1','2025-01-15 00:00:00'),
(11,1,203,'1','1','1','2025-01-20 00:00:00'),
(12,2,204,'0','0','1','2025-02-05 00:00:00'),
(13,2,205,'0','1','1','2025-02-12 00:00:00'),
(14,2,206,'1','1','1','2025-02-22 00:00:00'),
(15,3,207,'0','0','1','2025-03-01 00:00:00'),
(16,3,208,'0','1','1','2025-03-10 00:00:00'),
(17,3,209,'1','1','1','2025-03-18 00:00:00'),
(18,1,210,'0','0','1','2025-04-03 00:00:00'),
(19,1,211,'0','1','1','2025-04-14 00:00:00'),
(20,1,212,'1','1','1','2025-04-21 00:00:00'),
(21,2,213,'0','0','1','2025-05-05 00:00:00'),
(22,2,214,'0','1','1','2025-05-12 00:00:00'),
(23,2,215,'1','1','1','2025-05-20 00:00:00');
/*!40000 ALTER TABLE `activitylog` ENABLE KEYS */;
commit;

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `applications` (
  `application_id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'Applied',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`application_id`),
  UNIQUE KEY `applicant_id` (`applicant_id`,`job_id`),
  KEY `job_id` (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `applications`
--

/*!40000 ALTER TABLE `applications` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `applications` VALUES
(1,2,3,'On Hold','2025-05-25 08:33:19'),
(2,2,19,'Hired','2025-05-28 05:22:46');
/*!40000 ALTER TABLE `applications` ENABLE KEYS */;
commit;

--
-- Table structure for table `apply`
--

DROP TABLE IF EXISTS `apply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `apply` (
  `apply_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `jobpost_id` int(11) NOT NULL,
  `applied_at` datetime DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending',
  PRIMARY KEY (`apply_id`),
  KEY `user_id` (`user_id`),
  KEY `jobpost_id` (`jobpost_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `apply`
--

/*!40000 ALTER TABLE `apply` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `apply` VALUES
(2,2,3,'2025-05-27 23:22:11','Pending'),
(3,2,19,'2025-05-28 13:22:37','Pending'),
(4,168,5,'2025-09-11 06:32:16','Pending');
/*!40000 ALTER TABLE `apply` ENABLE KEYS */;
commit;

--
-- Table structure for table `client_registration`
--

DROP TABLE IF EXISTS `client_registration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_registration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` enum('client') NOT NULL DEFAULT 'client',
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `location` text DEFAULT NULL,
  `gov_id_type` varchar(100) NOT NULL,
  `gov_id_front` varchar(255) NOT NULL,
  `gov_id_back` varchar(255) NOT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `session_token` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_registration`
--

/*!40000 ALTER TABLE `client_registration` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `client_registration` ENABLE KEYS */;
commit;

--
-- Table structure for table `facial_verification`
--

DROP TABLE IF EXISTS `facial_verification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `facial_verification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qr_id` int(11) NOT NULL,
  `id_face_path` varchar(255) DEFAULT NULL,
  `selfie_face_path` varchar(255) DEFAULT NULL,
  `similarity_score` decimal(5,2) DEFAULT NULL,
  `status` enum('pending','matched','mismatch') DEFAULT 'pending',
  `verified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `qr_id` (`qr_id`),
  CONSTRAINT `facial_verification_ibfk_1` FOREIGN KEY (`qr_id`) REFERENCES `qr_data` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facial_verification`
--

/*!40000 ALTER TABLE `facial_verification` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `facial_verification` ENABLE KEYS */;
commit;

--
-- Table structure for table `job_appointments`
--

DROP TABLE IF EXISTS `job_appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_appointments` (
  `appointment_id` int(11) NOT NULL AUTO_INCREMENT,
  `jobpost_id` int(11) NOT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `status` enum('Pending','Scheduled','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`appointment_id`),
  KEY `jobpost_id` (`jobpost_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_appointments`
--

/*!40000 ALTER TABLE `job_appointments` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `job_appointments` VALUES
(3,19,NULL,NULL,'Completed','2025-05-27 17:01:31'),
(5,21,NULL,NULL,'Completed','2025-05-28 04:28:44'),
(6,22,NULL,NULL,'Completed','2025-05-28 04:37:18'),
(7,23,NULL,NULL,'Completed','2025-05-28 05:00:44'),
(8,229,NULL,NULL,'Pending','2025-07-31 08:29:20'),
(9,2,'2025-08-06','09:00:00','Scheduled','2025-07-31 09:16:14');
/*!40000 ALTER TABLE `job_appointments` ENABLE KEYS */;
commit;

--
-- Table structure for table `jobpost`
--

DROP TABLE IF EXISTS `jobpost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobpost` (
  `jobpost_id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `jobpost_title` varchar(100) NOT NULL,
  `disability_requirement` varchar(100) DEFAULT NULL,
  `years_experience` varchar(50) DEFAULT NULL,
  `skills_requirement` text DEFAULT NULL,
  `optional_skills` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `job_type` varchar(50) DEFAULT NULL,
  `shift_type` varchar(50) DEFAULT NULL,
  `expected_salary` int(11) DEFAULT NULL,
  `shift` varchar(255) NOT NULL,
  `appointment_time` datetime DEFAULT NULL,
  PRIMARY KEY (`jobpost_id`),
  KEY `fk_user_jobpost` (`user_id`),
  CONSTRAINT `fk_user_jobpost` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=230 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobpost`
--

/*!40000 ALTER TABLE `jobpost` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `jobpost` VALUES
(1,132,'Call Center Agent','Hearing Impairment','5+ years','Massage Therapy, Programming, Computer Literacy','Technical Support','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(2,114,'Data Encoder','Speech Impairment','N/A','Data Entry, Customer Service','Video Editing, Content Writing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(3,104,'Freelance Writer','Speech Impairment','N/A','Technical Support, Programming, Public Speaking','Social Media Management','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(4,127,'Administrative Assistant','Physical - Upper Limb','3-5 years','Public Speaking, Computer Literacy, Graphic Design, Technical Support','None, Content Writing','approved',4,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(5,127,'Customer Support Representative','Visual - Full','1-2 years','Graphic Design, Data Entry, Customer Service','Content Writing, Video Editing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(6,101,'Customer Support Representative','Speech Impairment','N/A','Public Speaking, Massage Therapy, Customer Service','Social Media Management','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(7,138,'Data Encoder','Physical - Upper Limb','3-5 years','Customer Service, Computer Literacy','Marketing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(8,137,'Administrative Assistant','Hearing Impairment','N/A','Public Speaking, Customer Service, Programming','Video Editing, Content Writing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(9,141,'Massage Therapist','Hearing Impairment','1-2 years','Public Speaking, Massage Therapy','Social Media Management, Content Writing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(10,149,'Customer Support Representative','Speech Impairment','N/A','Customer Service, Programming, Technical Support, Data Entry','Content Writing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(11,102,'Massage Therapist','Physical - Upper Limb','1-2 years','Public Speaking, Technical Support, Massage Therapy','Social Media Management','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(12,125,'Administrative Assistant','Physical - Upper Limb','5+ years','Massage Therapy, Technical Support','Content Writing, Social Media Management','approved',4,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(13,144,'Graphic Designer','Physical - Upper Limb','3-5 years','Graphic Design, Customer Service, Computer Literacy','Marketing, Social Media Management','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(14,145,'Call Center Agent','Visual - Full','3-5 years','Technical Support, Graphic Design','Marketing, Social Media Management','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(15,136,'Data Encoder','Speech Impairment','1-2 years','Programming, Massage Therapy, Customer Service','Marketing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(16,112,'Data Encoder','Speech Impairment','3-5 years','Programming, Customer Service, Massage Therapy','Technical Support','approved',2,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(17,150,'Freelance Writer','Physical - Lower Limb','5+ years','Technical Support, Data Entry, Massage Therapy, Public Speaking','Marketing, Video Editing','approved',5,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(18,108,'Virtual Assistant','Visual - Full','3-5 years','Massage Therapy, Graphic Design','Technical Support','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(19,104,'Content Creator','Visual - Full','5+ years','Data Entry, Graphic Design, Customer Service','None','approved',3,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(20,104,'Massage Therapist','Visual - Partial','3-5 years','Graphic Design, Computer Literacy, Public Speaking','Technical Support, Content Writing','approved',5,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(21,130,'Software Developer','Visual - Full','5+ years','Data Entry, Customer Service','None, Marketing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(22,136,'Data Encoder','Speech Impairment','5+ years','Programming, Technical Support, Public Speaking, Massage Therapy','Marketing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(23,123,'Virtual Assistant','Speech Impairment','3-5 years','Graphic Design, Data Entry, Public Speaking','Technical Support','approved',5,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(24,114,'Massage Therapist','Visual - Full','1-2 years','Massage Therapy, Technical Support, Programming, Computer Literacy','Technical Support, Social Media Management','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(25,113,'Customer Support Representative','Physical - Lower Limb','N/A','Technical Support, Graphic Design, Public Speaking, Customer Service','None','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(26,147,'Software Developer','Visual - Full','1-2 years','Massage Therapy, Computer Literacy, Programming, Technical Support','Social Media Management','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(27,129,'Data Encoder','Visual - Partial','N/A','Public Speaking, Data Entry','Video Editing','approved',1,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(28,104,'Software Developer','Hearing Impairment','3-5 years','Public Speaking, Data Entry','None','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(29,133,'Data Encoder','Hearing Impairment','1-2 years','Public Speaking, Customer Service, Computer Literacy, Programming','Content Writing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(30,115,'Administrative Assistant','Hearing Impairment','5+ years','Data Entry, Technical Support, Public Speaking','None','approved',5,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(31,138,'Call Center Agent','Hearing Impairment','3-5 years','Customer Service, Massage Therapy, Computer Literacy','Marketing, Video Editing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(32,119,'Customer Support Representative','Visual - Partial','3-5 years','Public Speaking, Computer Literacy, Data Entry','Technical Support','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(33,145,'Content Creator','Physical - Lower Limb','3-5 years','Data Entry, Public Speaking, Massage Therapy','Social Media Management, Content Writing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(34,136,'Virtual Assistant','Hearing Impairment','1-2 years','Graphic Design, Programming, Technical Support','Social Media Management, Video Editing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(35,150,'Graphic Designer','Visual - Full','N/A','Public Speaking, Graphic Design, Massage Therapy','Marketing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(36,119,'Customer Support Representative','Hearing Impairment','N/A','Technical Support, Massage Therapy, Data Entry, Customer Service','Technical Support, Marketing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(37,123,'Massage Therapist','Physical - Lower Limb','5+ years','Massage Therapy, Programming, Public Speaking, Data Entry','Marketing, None','approved',5,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(38,144,'Software Developer','Visual - Full','1-2 years','Technical Support, Computer Literacy, Graphic Design','Content Writing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(39,127,'Software Developer','Speech Impairment','3-5 years','Technical Support, Programming, Graphic Design, Customer Service','None, Social Media Management','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(40,124,'Graphic Designer','Visual - Full','5+ years','Computer Literacy, Public Speaking','Content Writing, Video Editing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(41,139,'Software Developer','Hearing Impairment','5+ years','Technical Support, Graphic Design','Video Editing, Social Media Management','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(42,112,'Content Creator','Hearing Impairment','3-5 years','Graphic Design, Massage Therapy','None','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(43,124,'Call Center Agent','Visual - Partial','N/A','Massage Therapy, Graphic Design, Public Speaking, Customer Service','Marketing, Video Editing','approved',1,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(44,135,'Virtual Assistant','Hearing Impairment','1-2 years','Programming, Customer Service, Data Entry','Technical Support','approved',5,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(45,128,'Call Center Agent','Visual - Full','1-2 years','Massage Therapy, Graphic Design, Technical Support, Data Entry','Video Editing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(46,107,'Content Creator','Speech Impairment','N/A','Computer Literacy, Programming, Massage Therapy','Technical Support','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(47,107,'Data Encoder','Visual - Full','3-5 years','Massage Therapy, Customer Service, Public Speaking','Video Editing','approved',4,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(48,118,'Administrative Assistant','Hearing Impairment','N/A','Customer Service, Public Speaking, Graphic Design','Marketing, None','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(49,114,'Freelance Writer','Speech Impairment','1-2 years','Computer Literacy, Data Entry, Graphic Design, Customer Service','Video Editing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(50,109,'Content Creator','Physical - Upper Limb','1-2 years','Technical Support, Massage Therapy, Computer Literacy, Programming','Social Media Management','approved',4,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(51,113,'Content Creator','Hearing Impairment','N/A','Massage Therapy, Public Speaking, Programming, Customer Service','Marketing','approved',3,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(52,131,'Customer Support Representative','Visual - Partial','3-5 years','Massage Therapy, Technical Support, Graphic Design','Marketing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(53,149,'Freelance Writer','Speech Impairment','N/A','Technical Support, Data Entry, Public Speaking, Graphic Design','Video Editing','approved',1,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(54,142,'Call Center Agent','Visual - Full','5+ years','Programming, Public Speaking','Technical Support, None','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(55,126,'Administrative Assistant','Visual - Full','1-2 years','Programming, Computer Literacy, Graphic Design, Public Speaking','Technical Support, Video Editing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(56,112,'Administrative Assistant','Visual - Full','3-5 years','Graphic Design, Massage Therapy, Data Entry','Video Editing, None','approved',3,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(57,147,'Graphic Designer','Physical - Upper Limb','3-5 years','Data Entry, Public Speaking, Massage Therapy','Marketing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(58,110,'Freelance Writer','Physical - Lower Limb','5+ years','Customer Service, Programming, Graphic Design','Marketing, None','approved',2,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(59,122,'Content Creator','Physical - Lower Limb','3-5 years','Graphic Design, Programming','Social Media Management','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(60,112,'Call Center Agent','Physical - Lower Limb','N/A','Customer Service, Public Speaking','Video Editing, Social Media Management','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(61,102,'Customer Support Representative','Visual - Full','1-2 years','Public Speaking, Massage Therapy, Data Entry, Computer Literacy','None, Video Editing','approved',2,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(62,117,'Virtual Assistant','Hearing Impairment','N/A','Massage Therapy, Public Speaking, Data Entry, Graphic Design','None, Video Editing','approved',5,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(63,146,'Virtual Assistant','Visual - Partial','3-5 years','Data Entry, Customer Service','None, Marketing','approved',2,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(64,120,'Customer Support Representative','Physical - Upper Limb','N/A','Data Entry, Programming, Technical Support','Video Editing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(65,150,'Customer Support Representative','Hearing Impairment','5+ years','Massage Therapy, Graphic Design, Public Speaking, Customer Service','Content Writing, Technical Support','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(66,108,'Data Encoder','Speech Impairment','3-5 years','Graphic Design, Massage Therapy','Social Media Management, Content Writing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(67,141,'Graphic Designer','Physical - Lower Limb','3-5 years','Data Entry, Public Speaking','Video Editing, Social Media Management','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(68,119,'Virtual Assistant','Physical - Lower Limb','N/A','Graphic Design, Technical Support','Social Media Management','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(69,150,'Graphic Designer','Hearing Impairment','N/A','Data Entry, Public Speaking','Social Media Management','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(70,107,'Data Encoder','Physical - Lower Limb','3-5 years','Computer Literacy, Customer Service, Public Speaking, Data Entry','Content Writing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(71,142,'Freelance Writer','Hearing Impairment','5+ years','Massage Therapy, Graphic Design','Video Editing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(72,147,'Content Creator','Speech Impairment','5+ years','Data Entry, Customer Service, Computer Literacy, Programming','Marketing, Content Writing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(73,131,'Freelance Writer','Physical - Upper Limb','3-5 years','Customer Service, Massage Therapy','Marketing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(74,102,'Administrative Assistant','Visual - Partial','N/A','Customer Service, Public Speaking','Social Media Management','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(75,104,'Customer Support Representative','Speech Impairment','5+ years','Public Speaking, Customer Service, Graphic Design, Technical Support','Social Media Management','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(76,111,'Data Encoder','Visual - Full','3-5 years','Graphic Design, Public Speaking','Social Media Management, Content Writing','approved',2,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(77,144,'Call Center Agent','Hearing Impairment','3-5 years','Graphic Design, Data Entry, Customer Service','Marketing, Technical Support','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(78,127,'Customer Support Representative','Hearing Impairment','5+ years','Programming, Customer Service','Marketing, None','approved',1,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(79,138,'Graphic Designer','Physical - Lower Limb','3-5 years','Customer Service, Computer Literacy, Public Speaking','None, Video Editing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(80,136,'Graphic Designer','Speech Impairment','3-5 years','Graphic Design, Massage Therapy, Customer Service','Technical Support, Marketing','approved',5,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(81,116,'Content Creator','Physical - Upper Limb','1-2 years','Data Entry, Technical Support','Video Editing','approved',1,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(82,120,'Call Center Agent','Hearing Impairment','5+ years','Public Speaking, Computer Literacy','Social Media Management, Video Editing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(83,105,'Software Developer','Visual - Partial','N/A','Computer Literacy, Massage Therapy, Data Entry, Graphic Design','Technical Support, None','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(84,145,'Graphic Designer','Hearing Impairment','5+ years','Customer Service, Massage Therapy','Marketing, None','approved',5,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(85,112,'Administrative Assistant','Hearing Impairment','1-2 years','Data Entry, Technical Support, Computer Literacy, Customer Service','None, Content Writing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(86,146,'Customer Support Representative','Visual - Partial','1-2 years','Customer Service, Public Speaking, Technical Support','Technical Support','approved',3,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(87,112,'Virtual Assistant','Speech Impairment','5+ years','Technical Support, Graphic Design, Public Speaking, Massage Therapy','Video Editing, Content Writing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(88,150,'Massage Therapist','Speech Impairment','1-2 years','Massage Therapy, Public Speaking, Customer Service','None, Content Writing','approved',4,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(89,146,'Massage Therapist','Hearing Impairment','N/A','Data Entry, Computer Literacy, Public Speaking, Customer Service','Technical Support, Video Editing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(90,110,'Graphic Designer','Speech Impairment','N/A','Data Entry, Massage Therapy, Customer Service, Public Speaking','Video Editing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(91,109,'Virtual Assistant','Speech Impairment','3-5 years','Customer Service, Computer Literacy, Programming, Data Entry','Social Media Management, Technical Support','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(92,101,'Data Encoder','Physical - Lower Limb','5+ years','Massage Therapy, Technical Support, Graphic Design','Marketing, None','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(93,135,'Massage Therapist','Visual - Partial','N/A','Massage Therapy, Technical Support','Content Writing, Video Editing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(94,124,'Virtual Assistant','Physical - Upper Limb','3-5 years','Customer Service, Graphic Design','None, Video Editing','approved',3,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(95,134,'Customer Support Representative','Visual - Full','5+ years','Data Entry, Programming, Graphic Design, Technical Support','Technical Support, Content Writing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(96,126,'Data Encoder','Speech Impairment','5+ years','Public Speaking, Programming, Computer Literacy','None, Technical Support','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(97,101,'Graphic Designer','Visual - Partial','5+ years','Data Entry, Customer Service','Marketing, Social Media Management','approved',4,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(98,143,'Massage Therapist','Physical - Lower Limb','5+ years','Data Entry, Programming','Content Writing','approved',2,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(99,136,'Freelance Writer','Visual - Partial','3-5 years','Public Speaking, Customer Service, Computer Literacy, Data Entry','Video Editing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(100,112,'Graphic Designer','Speech Impairment','5+ years','Technical Support, Data Entry','Social Media Management','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(101,129,'Data Encoder','Hearing Impairment','1-2 years','Public Speaking, Computer Literacy, Massage Therapy, Graphic Design','Video Editing, None','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(102,122,'Data Encoder','Visual - Full','3-5 years','Graphic Design, Computer Literacy, Data Entry, Technical Support','None, Social Media Management','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(103,110,'Software Developer','Physical - Upper Limb','5+ years','Computer Literacy, Massage Therapy, Public Speaking, Programming','None, Content Writing','approved',3,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(104,128,'Content Creator','Speech Impairment','1-2 years','Customer Service, Graphic Design, Public Speaking','Video Editing, Social Media Management','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(105,116,'Data Encoder','Visual - Full','N/A','Data Entry, Public Speaking, Customer Service, Computer Literacy','None, Marketing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(106,101,'Software Developer','Hearing Impairment','3-5 years','Computer Literacy, Graphic Design, Massage Therapy','Social Media Management, Content Writing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(107,103,'Massage Therapist','Physical - Lower Limb','1-2 years','Programming, Massage Therapy, Computer Literacy, Graphic Design','Content Writing, None','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(108,141,'Data Encoder','Physical - Upper Limb','N/A','Programming, Public Speaking, Computer Literacy, Data Entry','Video Editing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(109,133,'Graphic Designer','Physical - Lower Limb','N/A','Technical Support, Customer Service, Massage Therapy, Data Entry','Social Media Management','approved',4,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(110,146,'Virtual Assistant','Hearing Impairment','N/A','Computer Literacy, Customer Service, Data Entry','Marketing, Video Editing','approved',3,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(111,128,'Customer Support Representative','Physical - Lower Limb','3-5 years','Computer Literacy, Customer Service','Content Writing, None','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(112,101,'Massage Therapist','Physical - Lower Limb','3-5 years','Technical Support, Computer Literacy, Massage Therapy, Graphic Design','None, Video Editing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(113,115,'Administrative Assistant','Hearing Impairment','1-2 years','Data Entry, Computer Literacy, Customer Service','Video Editing, Technical Support','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(114,148,'Content Creator','Visual - Full','1-2 years','Public Speaking, Programming','None, Video Editing','approved',1,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(115,126,'Freelance Writer','Speech Impairment','N/A','Computer Literacy, Public Speaking, Technical Support, Data Entry','Marketing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(116,112,'Freelance Writer','Physical - Upper Limb','1-2 years','Technical Support, Computer Literacy','Social Media Management, Marketing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(117,104,'Call Center Agent','Hearing Impairment','3-5 years','Public Speaking, Computer Literacy, Massage Therapy','Content Writing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(118,126,'Customer Support Representative','Hearing Impairment','N/A','Massage Therapy, Data Entry','Technical Support, Marketing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(119,122,'Massage Therapist','Speech Impairment','3-5 years','Technical Support, Computer Literacy','Marketing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(120,128,'Data Encoder','Hearing Impairment','3-5 years','Technical Support, Customer Service','Content Writing, Video Editing','approved',4,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(121,138,'Software Developer','Hearing Impairment','5+ years','Massage Therapy, Technical Support, Graphic Design, Data Entry','None','approved',2,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(122,120,'Content Creator','Physical - Upper Limb','1-2 years','Public Speaking, Programming, Customer Service','Content Writing, Technical Support','approved',4,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(123,113,'Content Creator','Physical - Lower Limb','N/A','Data Entry, Public Speaking','None','approved',3,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(124,129,'Freelance Writer','Speech Impairment','3-5 years','Massage Therapy, Computer Literacy, Public Speaking, Technical Support','Content Writing, Technical Support','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(125,103,'Virtual Assistant','Visual - Partial','1-2 years','Customer Service, Technical Support, Programming','Technical Support, None','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(126,118,'Software Developer','Visual - Full','5+ years','Computer Literacy, Data Entry','None, Technical Support','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(127,112,'Data Encoder','Visual - Partial','1-2 years','Programming, Customer Service, Computer Literacy','None','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(128,147,'Call Center Agent','Hearing Impairment','N/A','Customer Service, Massage Therapy, Computer Literacy, Programming','Marketing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(129,119,'Virtual Assistant','Visual - Full','1-2 years','Massage Therapy, Computer Literacy','Video Editing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(130,108,'Administrative Assistant','Physical - Lower Limb','N/A','Graphic Design, Technical Support, Customer Service, Computer Literacy','Content Writing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(131,133,'Content Creator','Hearing Impairment','1-2 years','Computer Literacy, Graphic Design, Massage Therapy','Marketing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(132,134,'Graphic Designer','Visual - Partial','3-5 years','Technical Support, Massage Therapy, Graphic Design','None, Marketing','approved',4,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(133,114,'Software Developer','Visual - Full','1-2 years','Customer Service, Public Speaking, Data Entry','Content Writing','approved',2,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(134,122,'Software Developer','Visual - Partial','5+ years','Public Speaking, Graphic Design, Data Entry, Computer Literacy','Technical Support, Video Editing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(135,105,'Graphic Designer','Visual - Full','1-2 years','Public Speaking, Massage Therapy','None, Video Editing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(136,134,'Customer Support Representative','Physical - Upper Limb','1-2 years','Programming, Data Entry, Customer Service','Social Media Management, None','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(137,107,'Graphic Designer','Speech Impairment','1-2 years','Computer Literacy, Massage Therapy, Customer Service, Graphic Design','Marketing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(138,134,'Content Creator','Visual - Full','3-5 years','Graphic Design, Public Speaking, Data Entry, Massage Therapy','Technical Support','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(139,102,'Administrative Assistant','Hearing Impairment','5+ years','Massage Therapy, Computer Literacy','Content Writing, Technical Support','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(140,134,'Call Center Agent','Hearing Impairment','3-5 years','Massage Therapy, Graphic Design','Video Editing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(141,142,'Content Creator','Visual - Partial','3-5 years','Computer Literacy, Data Entry, Public Speaking, Programming','Marketing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(142,102,'Customer Support Representative','Visual - Partial','5+ years','Computer Literacy, Data Entry, Public Speaking','Video Editing','approved',3,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(143,144,'Software Developer','Hearing Impairment','3-5 years','Public Speaking, Massage Therapy','Technical Support, Video Editing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(144,132,'Virtual Assistant','Physical - Upper Limb','N/A','Graphic Design, Massage Therapy, Data Entry, Programming','Social Media Management','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(145,121,'Data Encoder','Visual - Full','1-2 years','Public Speaking, Customer Service, Technical Support, Programming','Technical Support','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(146,117,'Data Encoder','Hearing Impairment','N/A','Programming, Public Speaking, Graphic Design','None, Marketing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(147,114,'Graphic Designer','Physical - Lower Limb','1-2 years','Massage Therapy, Graphic Design, Computer Literacy','Content Writing, Marketing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(148,129,'Data Encoder','Visual - Partial','1-2 years','Graphic Design, Programming, Data Entry','Marketing, Video Editing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(149,146,'Massage Therapist','Physical - Lower Limb','5+ years','Public Speaking, Data Entry, Technical Support, Programming','Marketing','approved',1,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(150,120,'Customer Support Representative','Visual - Partial','5+ years','Public Speaking, Data Entry, Computer Literacy','Social Media Management, None','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(151,104,'Graphic Designer','Physical - Upper Limb','1-2 years','Programming, Customer Service, Graphic Design, Public Speaking','Video Editing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(152,149,'Graphic Designer','Visual - Full','N/A','Massage Therapy, Customer Service','None, Video Editing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(153,134,'Customer Support Representative','Visual - Partial','3-5 years','Programming, Public Speaking','Social Media Management','approved',5,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(154,120,'Data Encoder','Visual - Partial','3-5 years','Public Speaking, Programming','None','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(155,138,'Customer Support Representative','Hearing Impairment','5+ years','Programming, Computer Literacy, Massage Therapy','Technical Support','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(156,121,'Administrative Assistant','Hearing Impairment','5+ years','Data Entry, Massage Therapy','Social Media Management, Technical Support','approved',3,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(157,112,'Administrative Assistant','Visual - Partial','N/A','Programming, Graphic Design, Computer Literacy','Marketing, None','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(158,128,'Graphic Designer','Physical - Upper Limb','3-5 years','Public Speaking, Graphic Design','Video Editing','approved',4,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(159,143,'Call Center Agent','Physical - Upper Limb','5+ years','Public Speaking, Programming, Computer Literacy, Massage Therapy','Marketing, Content Writing','approved',4,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(160,125,'Content Creator','Visual - Partial','N/A','Massage Therapy, Programming, Graphic Design','Marketing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(161,143,'Freelance Writer','Physical - Lower Limb','N/A','Massage Therapy, Data Entry, Graphic Design','None','approved',2,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(162,117,'Graphic Designer','Hearing Impairment','1-2 years','Customer Service, Massage Therapy, Graphic Design','Technical Support','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(163,118,'Call Center Agent','Physical - Upper Limb','5+ years','Customer Service, Massage Therapy, Graphic Design','Technical Support, Video Editing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(164,137,'Virtual Assistant','Visual - Partial','N/A','Programming, Data Entry, Massage Therapy, Graphic Design','Social Media Management','approved',2,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(165,131,'Massage Therapist','Physical - Lower Limb','3-5 years','Computer Literacy, Public Speaking, Massage Therapy, Data Entry','Content Writing','approved',2,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(166,133,'Freelance Writer','Visual - Full','5+ years','Massage Therapy, Computer Literacy, Public Speaking','None','approved',2,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(167,111,'Massage Therapist','Speech Impairment','3-5 years','Computer Literacy, Technical Support, Customer Service','Marketing, None','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(168,106,'Massage Therapist','Visual - Full','5+ years','Data Entry, Customer Service','Video Editing, Social Media Management','approved',1,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(169,135,'Freelance Writer','Hearing Impairment','5+ years','Customer Service, Graphic Design','Content Writing, None','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(170,108,'Customer Support Representative','Visual - Full','3-5 years','Public Speaking, Massage Therapy, Customer Service','Marketing, Technical Support','approved',5,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(171,137,'Content Creator','Physical - Lower Limb','1-2 years','Customer Service, Technical Support','Content Writing, Technical Support','approved',5,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(172,102,'Freelance Writer','Speech Impairment','1-2 years','Programming, Massage Therapy','Social Media Management','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(173,105,'Virtual Assistant','Visual - Full','5+ years','Programming, Massage Therapy','Marketing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(174,125,'Content Creator','Visual - Full','1-2 years','Customer Service, Computer Literacy, Data Entry','Technical Support','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(175,114,'Customer Support Representative','Physical - Upper Limb','1-2 years','Computer Literacy, Graphic Design, Technical Support','None, Marketing','approved',3,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(176,126,'Content Creator','Physical - Upper Limb','1-2 years','Data Entry, Computer Literacy, Public Speaking','Content Writing, Technical Support','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(177,120,'Content Creator','Visual - Partial','1-2 years','Graphic Design, Data Entry, Computer Literacy, Customer Service','Social Media Management, Content Writing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(178,128,'Virtual Assistant','Speech Impairment','5+ years','Computer Literacy, Public Speaking, Graphic Design, Programming','Marketing, None','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(179,131,'Call Center Agent','Hearing Impairment','3-5 years','Graphic Design, Data Entry, Customer Service','Content Writing, Marketing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(180,147,'Content Creator','Speech Impairment','1-2 years','Customer Service, Technical Support, Public Speaking','None, Technical Support','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(181,124,'Administrative Assistant','Physical - Upper Limb','N/A','Computer Literacy, Data Entry','None','approved',2,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(182,137,'Administrative Assistant','Visual - Full','1-2 years','Technical Support, Data Entry, Programming','None, Content Writing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(183,104,'Massage Therapist','Visual - Full','5+ years','Computer Literacy, Graphic Design, Technical Support, Public Speaking','None, Social Media Management','approve',0,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(184,144,'Virtual Assistant','Physical - Upper Limb','5+ years','Computer Literacy, Data Entry, Customer Service','Social Media Management','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(185,102,'Content Creator','Visual - Full','5+ years','Massage Therapy, Technical Support','Marketing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(186,102,'Data Encoder','Visual - Full','1-2 years','Programming, Graphic Design, Public Speaking','Technical Support, Video Editing','approved',2,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(187,147,'Call Center Agent','Visual - Partial','5+ years','Data Entry, Massage Therapy','None, Technical Support','approved',4,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(188,131,'Content Creator','Visual - Full','3-5 years','Technical Support, Computer Literacy, Graphic Design, Programming','Social Media Management, None','approved',1,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(189,108,'Massage Therapist','Physical - Lower Limb','1-2 years','Data Entry, Technical Support, Public Speaking, Graphic Design','Technical Support','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(190,110,'Graphic Designer','Speech Impairment','N/A','Customer Service, Programming, Massage Therapy','Content Writing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(191,114,'Data Encoder','Hearing Impairment','1-2 years','Massage Therapy, Technical Support, Data Entry, Customer Service','Technical Support','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(192,144,'Content Creator','Visual - Full','1-2 years','Computer Literacy, Graphic Design','Marketing','approved',1,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(193,101,'Data Encoder','Physical - Lower Limb','5+ years','Graphic Design, Technical Support, Customer Service, Programming','Video Editing, Content Writing','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(194,102,'Content Creator','Hearing Impairment','1-2 years','Customer Service, Data Entry','None','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(195,141,'Content Creator','Hearing Impairment','3-5 years','Graphic Design, Technical Support, Data Entry, Programming','Content Writing, Video Editing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(196,123,'Freelance Writer','Visual - Partial','3-5 years','Customer Service, Graphic Design','Marketing','approved',5,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(197,107,'Data Encoder','Physical - Lower Limb','5+ years','Technical Support, Customer Service','Social Media Management, None','pending',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(198,146,'Content Creator','Physical - Lower Limb','5+ years','Public Speaking, Data Entry','Technical Support, Video Editing','approved',2,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(199,127,'Data Encoder','Hearing Impairment','1-2 years','Data Entry, Massage Therapy','Technical Support','approved',4,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(200,145,'Massage Therapist','Hearing Impairment','N/A','Data Entry, Programming, Public Speaking','Content Writing','rejected',NULL,'2025-07-23 22:02:01',NULL,NULL,NULL,'',NULL),
(201,160,'Web Developer','Hearing Impairment','2','PHP, HTML, CSS','JavaScript','pending',NULL,'2025-07-23 23:27:38',NULL,NULL,NULL,'',NULL),
(202,160,'Administrative Assistant','Visual - Partial','1','Organization, Communication','Excel','approved',0,'2025-07-23 23:27:38',NULL,NULL,NULL,'',NULL),
(204,162,'Customer Support Representative','Speech Impairment','1','Communication, Patience','Zendesk','approved',0,'2025-07-23 23:27:38',NULL,NULL,NULL,'',NULL),
(205,163,'Content Writer','Visual - Full','2','Writing, Research','SEO','pending',NULL,'2025-07-23 23:27:38',NULL,NULL,NULL,'',NULL),
(206,164,'Marketing Officer','Physical - Upper Limb','2','Campaign Management, Reporting','Canva','approved',0,'2025-07-23 23:27:38',NULL,NULL,NULL,'',NULL),
(214,161,'Data Entry Specialist','Hearing Impairment','2','Sign Language, Data Management, Visual Communication','Spreadsheet Software, Accuracy','open',NULL,'2025-07-22 16:00:00',NULL,NULL,NULL,'',NULL),
(215,161,'Reception Assistant','Physical - Upper Limb','1','Clerical Skills, Communication','Phone Etiquette, Calendar Scheduling','open',NULL,'2025-07-22 16:00:00',NULL,NULL,NULL,'',NULL),
(216,161,'Braille Music Instructor','Visual - Full','4','Braille Reading, Audio Editing','Instrument Proficiency, Teaching','open',NULL,'2025-07-22 16:00:00',NULL,NULL,NULL,'',NULL),
(217,161,'Junior QA Tester','Speech Impairment','1','Problem Solving, Analytical Thinking, Writing','Bug Reporting, Test Case Design','open',NULL,'2025-07-22 16:00:00',NULL,NULL,NULL,'',NULL),
(218,161,'SEO Content Creator','Physical - Lower Limb','2','Content Writing, Computer Programming','SEO Tools, Research Skills','open',NULL,'2025-07-22 16:00:00',NULL,NULL,NULL,'',NULL),
(219,161,'Telemarketing Agent','Visual - Partial','1','Typing, Screen Reader Usage, Basic Computer Skills','CRM Tools, Verbal Communication','open',NULL,'2025-07-22 16:00:00',NULL,NULL,NULL,'',NULL),
(220,161,'Digital Designer','Hearing Impairment','3','Visual Communication, Data Management, Sign Language','Adobe Suite, Creativity','open',NULL,'2025-07-22 16:00:00',NULL,NULL,NULL,'',NULL),
(221,161,'Technical Writer','Speech Impairment','2','Writing, Problem Solving, Analytical Thinking','API Documentation, Research','open',NULL,'2025-07-22 16:00:00',NULL,NULL,NULL,'',NULL),
(222,161,'Junior Backend Developer','Physical - Lower Limb','2','Computer Programming, Graphic Design','PHP, MySQL, Laravel','open',NULL,'2025-07-22 16:00:00',NULL,NULL,NULL,'',NULL),
(223,161,'Massage Therapist','Visual - Full','5','Braille Reading, Audio Editing','Client Interaction, Relaxation Techniques','open',NULL,'2025-07-22 16:00:00',NULL,NULL,NULL,'',NULL),
(224,161,'Customer Support Specialist','Visual - Partial','2','Typing, Screen Reader Usage, Basic Computer Skills','Customer Handling, Empathy','open',NULL,'2025-07-22 16:00:00',NULL,NULL,NULL,'',NULL),
(225,161,'Administrative Support Assistant','Physical - Upper Limb','1','Communication, Clerical Skills','Data Entry, Filing','open',NULL,'2025-07-22 16:00:00',NULL,NULL,NULL,'',NULL),
(226,161,'Music Teacher','Visual - Full','4','Braille Reading, Audio Editing','Instrument Teaching, Patience','open',NULL,'2025-07-22 16:00:00',NULL,NULL,NULL,'',NULL),
(227,161,'Data Entry Operator','Hearing Impairment','2','Visual Communication, Sign Language, Data Management','Spreadsheet Proficiency, Speed Typing','open',NULL,'2025-07-22 16:00:00',NULL,NULL,NULL,'',NULL),
(228,161,'Software Developer','Speech Impairment','3','Analytical Thinking, Writing, Problem Solving','PHP, Debugging, Collaboration Tools','open',NULL,'2025-07-22 16:00:00',NULL,NULL,NULL,'',NULL),
(229,161,'Data Encoder','','N/A','','N/A','pending',NULL,'2025-07-31 08:29:20',NULL,NULL,NULL,'Flexible',NULL);
/*!40000 ALTER TABLE `jobpost` ENABLE KEYS */;
commit;

--
-- Table structure for table `jobstages`
--

DROP TABLE IF EXISTS `jobstages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobstages` (
  `stage_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('Hired','On Hold') NOT NULL,
  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`stage_id`),
  UNIQUE KEY `unique_job_user` (`job_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobstages`
--

/*!40000 ALTER TABLE `jobstages` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `jobstages` VALUES
(11,3,2,'Hired','2025-05-27 09:02:21'),
(13,4,5,'Hired','2025-05-27 09:02:56'),
(32,19,2,'Hired','2025-05-28 05:51:58'),
(33,203,50,'On Hold','2025-07-23 23:31:58'),
(34,214,16,'Hired','2025-09-11 16:05:24'),
(35,214,55,'On Hold','2025-07-31 09:00:50');
/*!40000 ALTER TABLE `jobstages` ENABLE KEYS */;
commit;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `messages_id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(255) NOT NULL,
  `sender_email` varchar(255) NOT NULL,
  `receiver_email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`messages_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `messages` VALUES
(1,0,'AmielPerez@gmail.com','AndrewBatuigas@gmail.com','Interview','I would like to have an interview'),
(2,0,'AndrewBatuigas@gmail.com','AmielPerez@gmail.com','Interview','Date: 05/30/2025, 9AM at Lacson Street'),
(3,0,'AndrewBatuigas@gmail.com','AmielPerez@gmail.com','Interview','fafwafwaf');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
commit;

--
-- Table structure for table `monthly_activity_summary`
--

DROP TABLE IF EXISTS `monthly_activity_summary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `monthly_activity_summary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `month_name` varchar(20) NOT NULL,
  `total_entries` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_year_month` (`year`,`month`)
) ENGINE=InnoDB AUTO_INCREMENT=173 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monthly_activity_summary`
--

/*!40000 ALTER TABLE `monthly_activity_summary` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `monthly_activity_summary` VALUES
(1,2025,1,'Jan',3),
(2,2025,2,'Feb',3),
(3,2025,3,'Mar',3),
(4,2025,4,'Apr',3),
(5,2025,5,'May',11);
/*!40000 ALTER TABLE `monthly_activity_summary` ENABLE KEYS */;
commit;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`notification_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `notifications` VALUES
(1,2,'Your application is on hold for a job you matched with.',0,'2025-05-25 08:33:19'),
(2,12,'Your application is on hold for a job you matched with.',0,'2025-05-27 08:26:10'),
(3,12,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-27 08:38:08'),
(4,12,'Your application is on hold for a job you matched with.',0,'2025-05-27 08:38:13'),
(5,12,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-27 08:38:18'),
(6,12,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-27 08:53:31'),
(7,12,'Your application is on hold for a job you matched with.',0,'2025-05-27 08:53:32'),
(8,12,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-27 08:53:34'),
(9,12,'Your application is on hold for a job you matched with.',0,'2025-05-27 09:01:33'),
(10,12,'Your application is on hold for a job you matched with.',0,'2025-05-27 09:01:43'),
(11,12,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-27 09:01:49'),
(12,2,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-27 09:02:21'),
(13,2,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-27 09:02:31'),
(14,5,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-27 09:02:56'),
(15,12,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-27 09:03:15'),
(16,12,'Your application is on hold for a job you matched with.',0,'2025-05-27 09:04:29'),
(17,12,'Your application is on hold for a job you matched with.',0,'2025-05-27 09:11:08'),
(18,2,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-27 09:11:17'),
(19,12,'Your application is on hold for a job you matched with.',0,'2025-05-27 09:37:52'),
(20,19,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-27 09:38:04'),
(21,35,'Your application is on hold for a job you matched with.',0,'2025-05-27 09:38:12'),
(22,41,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-27 09:38:23'),
(23,41,'Your application is on hold for a job you matched with.',0,'2025-05-27 09:38:26'),
(24,19,'Your application is on hold for a job you matched with.',0,'2025-05-27 09:38:28'),
(25,19,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-27 09:38:36'),
(26,19,'Your application is on hold for a job you matched with.',0,'2025-05-27 09:39:58'),
(27,12,'Your application is on hold for a job you matched with.',0,'2025-05-27 09:45:54'),
(28,19,'Your application is on hold for a job you matched with.',0,'2025-05-27 09:45:59'),
(29,35,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-27 09:46:06'),
(30,12,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-27 09:47:03'),
(31,19,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-27 09:47:29'),
(32,41,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-27 09:47:37'),
(33,2,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-28 05:22:46'),
(34,2,'Your application is on hold for a job you matched with.',0,'2025-05-28 05:22:56'),
(35,2,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-28 05:23:49'),
(36,2,'Your application is on hold for a job you matched with.',0,'2025-05-28 05:32:33'),
(37,2,'Congratulations! You have been hired for a job you matched with.',0,'2025-05-28 05:51:58'),
(38,50,'Your application is on hold for a job you matched with.',0,'2025-07-23 23:31:58'),
(39,16,'Your application is on hold for a job you matched with.',0,'2025-07-30 03:32:54'),
(40,55,'Your application is on hold for a job you matched with.',0,'2025-07-31 09:00:50'),
(41,16,'Congratulations! You have been hired for a job you matched with.',0,'2025-09-11 16:05:24');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
commit;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
commit;

--
-- Table structure for table `qr_data`
--

DROP TABLE IF EXISTS `qr_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `qr_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qr_raw_content` text DEFAULT NULL,
  `qr_id_number` varchar(50) DEFAULT NULL,
  `qr_valid_until` date DEFAULT NULL,
  `pwd_id_number` varchar(50) DEFAULT NULL,
  `pwd_full_name` varchar(255) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_initial` varchar(5) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `suffix` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email_token` varchar(64) DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `birthday` date DEFAULT NULL,
  `location` text DEFAULT NULL,
  `disability` varchar(100) DEFAULT NULL,
  `disability_subcategory` varchar(100) DEFAULT NULL,
  `pwd_id_image_path` varchar(255) DEFAULT NULL,
  `facial_image_path` varchar(255) DEFAULT NULL,
  `facial_match_score` float DEFAULT NULL,
  `facial_verified` tinyint(1) DEFAULT 0,
  `work_field` varchar(100) DEFAULT NULL,
  `session_token` varchar(100) DEFAULT NULL,
  `is_complete` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `preferred_work` varchar(100) DEFAULT NULL,
  `shift` varchar(100) DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `highest_education` varchar(100) DEFAULT NULL,
  `experience_status` varchar(50) DEFAULT NULL,
  `experience_years` varchar(255) DEFAULT NULL,
  `optional_skills` text DEFAULT NULL,
  `work_status` varchar(50) DEFAULT NULL,
  `expected_amount` varchar(20) DEFAULT NULL,
  `about_me` text DEFAULT NULL,
  `career_goals` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `qr_id_number` (`qr_id_number`),
  UNIQUE KEY `session_token` (`session_token`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qr_data`
--

/*!40000 ALTER TABLE `qr_data` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `qr_data` VALUES
(41,'https://validate.bacolodcity.gov.ph/pwd/?content=9bf595f44eef588841c1ee8975f94f5c','9bf595f44eef588841c1ee8975f94f5c',NULL,'','HECHANOVA, ALEXANDER M JR','ALEXANDER','M','HECHANOVA','JR','AlexanderHechanova@gmail.com','$2y$10$MlKbD/LBP3AnsZPc.8BMduK7kfwCprSvxavHRvBcCvq0kbpmnWZCe','4b88524fce01e6ee4554c9a0e62448e4',0,'1988-12-05','Mansilingan','Visual','Partial','/uploads/pwd_ids/1763295838_converted.jpg','/uploads/facial_images/6919c26827791.jfif',NULL,0,'N/A','24020d90dfca7e30df536b916e9a3cdb',0,'2025-11-16 06:59:53','2025-11-16 12:25:25','Any Desk Job','Day','uploads/resumes/resume_6919c2a90da53.pdf','Graphic Design','College','No','N/A',NULL,'Full-time','10000',NULL,NULL);
/*!40000 ALTER TABLE `qr_data` ENABLE KEYS */;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int(255) NOT NULL AUTO_INCREMENT,
  `user_type` enum('admin','job_seeker','client') NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `disability` varchar(100) DEFAULT NULL,
  `disability_subcategory` varchar(100) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `highest_education` varchar(100) DEFAULT NULL,
  `experience_status` varchar(50) DEFAULT NULL,
  `experience_years` varchar(50) DEFAULT NULL,
  `experience_field` varchar(100) DEFAULT NULL,
  `location` text DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `preferred_work` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `shift` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `qr_id` varchar(255) DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `is_verified` varchar(255) DEFAULT NULL,
  `company` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `with_workshop` varchar(255) NOT NULL,
  `is_hiring_enabled` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `pwd_id_number` varchar(50) DEFAULT NULL,
  `gov_id_type` varchar(100) DEFAULT NULL,
  `gov_id_front` varchar(255) DEFAULT NULL,
  `gov_id_back` varchar(255) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_initial` varchar(5) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `suffix` varchar(50) DEFAULT NULL,
  `facial_image_path` varchar(255) DEFAULT NULL,
  `facial_match_score` float DEFAULT NULL,
  `facial_verified` tinyint(1) DEFAULT 0,
  `work_status` varchar(50) DEFAULT NULL,
  `session_token` varchar(100) DEFAULT NULL,
  `is_complete` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(0,'admin','Aljie Hechanova','Aljiehechanova@gmail.com','$2y$10$gg1wVe5RyWskv24lB9NVUejU4iAwfjCycUuVqxbS0.jTK/zB4954S','',NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,'uploads/resumes/1748327085_resume (8).pdf',NULL,'2025-07-24 02:11:03',NULL,NULL,'0','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(1,'job_seeker','Isla Ramos','isla.ramos1@email.com','$2y$10$YvcdBtP3BBUrx7xYQzUO/.HUnVaWmh3wM5GTk0F4IzKAnKdL0qR7e','Visual - Full',NULL,'2002-05-28',NULL,NULL,NULL,NULL,'Iloilo','09261329382','IT Support','Braille Reading, Audio Editing','resume_1.pdf','Graveyard','2025-07-23 21:47:51','QR100001',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(2,'job_seeker','Maria Morales','maria.morales2@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1990-12-30',NULL,NULL,NULL,NULL,'Laguna','09777696735','IT Support','Audio Editing','resume_2.pdf','Flexible','2025-07-23 21:47:51','QR100002',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(3,'job_seeker','Ana Villanueva','ana.villanueva3@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1989-09-16',NULL,NULL,NULL,NULL,'Iloilo','09289099781','IT Support','Braille Reading','resume_3.pdf','Day Shift','2025-07-23 21:47:51','QR100003',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(4,'job_seeker','Pedro Garcia','pedro.garcia4@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Partial',NULL,'1997-08-10',NULL,NULL,NULL,NULL,'Davao','09600773304','IT Support','Typing, Screen Reader Usage, Basic Computer Skills','resume_4.pdf','Graveyard','2025-07-23 21:47:51','QR100004',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(5,'job_seeker','Lea Villanueva','lea.villanueva5@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Upper Limb',NULL,'1989-07-16',NULL,NULL,NULL,NULL,'Makati','09224343638','IT Support','Communication, Clerical Skills','resume_5.pdf','Day Shift','2025-07-23 21:47:51','QR100005',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(6,'job_seeker','Grace Fernandez','grace.fernandez6@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Upper Limb',NULL,'1995-03-07',NULL,NULL,NULL,NULL,'Laguna','09503772020','IT Support','Clerical Skills','resume_6.pdf','Night Shift','2025-07-23 21:47:51','QR100006',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(7,'job_seeker','Maria Mendoza','maria.mendoza7@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1986-10-04',NULL,NULL,NULL,NULL,'Makati','09565236318','IT Support','Audio Editing','resume_7.pdf','Flexible','2025-07-23 21:47:51','QR100007',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(8,'job_seeker','Julia Mendoza','julia.mendoza8@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Upper Limb',NULL,'1986-02-22',NULL,NULL,NULL,NULL,'Davao','09241596214','IT Support','Communication','resume_8.pdf','Flexible','2025-07-23 21:47:51','QR100008',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(9,'job_seeker','Ana Torres','ana.torres9@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Speech Impairment',NULL,'2001-08-27',NULL,NULL,NULL,NULL,'Metro Manila','09666320504','IT Support','Problem Solving','resume_9.pdf','Graveyard','2025-07-23 21:47:51','QR100009',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(10,'job_seeker','Faith Santos','faith.santos10@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Upper Limb',NULL,'1997-11-15',NULL,NULL,NULL,NULL,'Baguio','09460728553','IT Support','Clerical Skills','resume_10.pdf','Night Shift','2025-07-23 21:47:51','QR100010',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(11,'job_seeker','Maria Lopez','maria.lopez11@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1987-01-04',NULL,NULL,NULL,NULL,'Davao','09262454915','IT Support','Braille Reading, Audio Editing','resume_11.pdf','Day Shift','2025-07-23 21:47:51','QR100011',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(12,'job_seeker','James Castro','james.castro12@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Upper Limb',NULL,'1994-10-05',NULL,NULL,NULL,NULL,'Davao','09606322189','IT Support','Clerical Skills','resume_12.pdf','Day Shift','2025-07-23 21:47:51','QR100012',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(13,'job_seeker','Luis Castro','luis.castro13@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Hearing Impairment',NULL,'1981-07-07',NULL,NULL,NULL,NULL,'Laguna','09698280077','IT Support','Sign Language','resume_13.pdf','Graveyard','2025-07-23 21:47:51','QR100013',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(14,'job_seeker','Luis Alvarez','luis.alvarez14@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1988-11-17',NULL,NULL,NULL,NULL,'Iloilo','09588887748','IT Support','Braille Reading, Audio Editing','resume_14.pdf','Night Shift','2025-07-23 21:47:51','QR100014',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(15,'job_seeker','Grace Ramos','grace.ramos15@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Upper Limb',NULL,'1987-05-25',NULL,NULL,NULL,NULL,'Laguna','09456918528','IT Support','Communication, Clerical Skills','resume_15.pdf','Graveyard','2025-07-23 21:47:51','QR100015',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(16,'job_seeker','James Ramos','james.ramos16@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Hearing Impairment',NULL,'2003-06-04',NULL,NULL,NULL,NULL,'Laguna','09340655913','IT Support','Visual Communication, Sign Language','resume_16.pdf','Flexible','2025-07-23 21:47:51','QR100016',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(17,'job_seeker','Pedro Ramos','pedro.ramos17@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Upper Limb',NULL,'1986-06-19',NULL,NULL,NULL,NULL,'Quezon City','09992830879','IT Support','Communication, Clerical Skills','resume_17.pdf','Flexible','2025-07-23 21:47:51','QR100017',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(18,'job_seeker','Miguel Castro','miguel.castro18@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Partial',NULL,'1990-09-03',NULL,NULL,NULL,NULL,'Cebu','09435123794','IT Support','Typing','resume_18.pdf','Night Shift','2025-07-23 21:47:51','QR100018',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(19,'job_seeker','Ana Fernandez','ana.fernandez19@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Partial',NULL,'1980-07-24',NULL,NULL,NULL,NULL,'Laguna','09563724454','IT Support','Typing, Basic Computer Skills','resume_19.pdf','Flexible','2025-07-23 21:47:51','QR100019',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(20,'job_seeker','Miguel Agustin','miguel.agustin20@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1990-05-27',NULL,NULL,NULL,NULL,'Davao','09564613998','IT Support','Braille Reading','resume_20.pdf','Graveyard','2025-07-23 21:47:51','QR100020',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(21,'job_seeker','Miguel Santos','miguel.santos21@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Upper Limb',NULL,'1991-06-26',NULL,NULL,NULL,NULL,'Quezon City','09874941463','IT Support','Clerical Skills','resume_21.pdf','Day Shift','2025-07-23 21:47:51','QR100021',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(22,'job_seeker','Miguel Mendoza','miguel.mendoza22@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Lower Limb',NULL,'1990-06-15',NULL,NULL,NULL,NULL,'Metro Manila','09246374266','IT Support','Computer Programming','resume_22.pdf','Graveyard','2025-07-23 21:47:51','QR100022',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(23,'job_seeker','Pedro Alvarez','pedro.alvarez23@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1983-05-24',NULL,NULL,NULL,NULL,'Quezon City','09395464163','IT Support','Audio Editing','resume_23.pdf','Graveyard','2025-07-23 21:47:51','QR100023',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(24,'job_seeker','Andre Agustin','andre.agustin24@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Partial',NULL,'1999-06-07',NULL,NULL,NULL,NULL,'Metro Manila','09680579007','IT Support','Typing','resume_24.pdf','Day Shift','2025-07-23 21:47:51','QR100024',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(25,'job_seeker','Mark Castro','mark.castro25@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1983-04-15',NULL,NULL,NULL,NULL,'Cebu','09451328275','IT Support','Braille Reading','resume_25.pdf','Graveyard','2025-07-23 21:47:51','QR100025',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(26,'job_seeker','Elena Castro','elena.castro26@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1998-02-17',NULL,NULL,NULL,NULL,'Iloilo','09222329413','IT Support','Braille Reading, Audio Editing','resume_26.pdf','Night Shift','2025-07-23 21:47:51','QR100026',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(27,'job_seeker','Isla Reyes','isla.reyes27@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Speech Impairment',NULL,'1980-05-21',NULL,NULL,NULL,NULL,'Baguio','09571941410','IT Support','Analytical Thinking','resume_27.pdf','Flexible','2025-07-23 21:47:51','QR100027',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(28,'job_seeker','Carlos Morales','carlos.morales28@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Upper Limb',NULL,'1986-01-11',NULL,NULL,NULL,NULL,'Cebu','09368722293','IT Support','Communication, Clerical Skills','resume_28.pdf','Night Shift','2025-07-23 21:47:51','QR100028',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(29,'job_seeker','James Cruz','james.cruz29@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1994-11-17',NULL,NULL,NULL,NULL,'Cebu','09925943926','IT Support','Audio Editing','resume_29.pdf','Graveyard','2025-07-23 21:47:51','QR100029',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(30,'job_seeker','Luna Alvarez','luna.alvarez30@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Partial',NULL,'1982-06-11',NULL,NULL,NULL,NULL,'Davao','09250090385','IT Support','Typing, Basic Computer Skills','resume_30.pdf','Flexible','2025-07-23 21:47:51','QR100030',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(31,'job_seeker','Pedro Castro','pedro.castro31@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Speech Impairment',NULL,'1988-05-29',NULL,NULL,NULL,NULL,'Cebu','09889159727','IT Support','Analytical Thinking, Writing','resume_31.pdf','Graveyard','2025-07-23 21:47:51','QR100031',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(32,'job_seeker','Andre Lopez','andre.lopez32@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Upper Limb',NULL,'2000-03-13',NULL,NULL,NULL,NULL,'Davao','09801299403','IT Support','Clerical Skills','resume_32.pdf','Night Shift','2025-07-23 21:47:51','QR100032',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(33,'job_seeker','Lea Agustin','lea.agustin33@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Partial',NULL,'1999-05-12',NULL,NULL,NULL,NULL,'Cebu','09822412231','IT Support','Typing, Basic Computer Skills','resume_33.pdf','Graveyard','2025-07-23 21:47:51','QR100033',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(34,'job_seeker','James Torres','james.torres34@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Speech Impairment',NULL,'1985-02-17',NULL,NULL,NULL,NULL,'Davao','09273504605','IT Support','Analytical Thinking','resume_34.pdf','Graveyard','2025-07-23 21:47:51','QR100034',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(35,'job_seeker','Pedro Morales','pedro.morales35@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Speech Impairment',NULL,'2000-03-23',NULL,NULL,NULL,NULL,'Quezon City','09772245870','IT Support','Analytical Thinking','resume_35.pdf','Day Shift','2025-07-23 21:47:51','QR100035',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(36,'job_seeker','Maria Agustin','maria.agustin36@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Partial',NULL,'1992-03-22',NULL,NULL,NULL,NULL,'Laguna','09876791614','IT Support','Typing, Screen Reader Usage','resume_36.pdf','Graveyard','2025-07-23 21:47:51','QR100036',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(37,'job_seeker','Julia Castro','julia.castro37@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1980-12-28',NULL,NULL,NULL,NULL,'Baguio','09195230007','IT Support','Braille Reading, Audio Editing','resume_37.pdf','Graveyard','2025-07-23 21:47:51','QR100037',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(38,'job_seeker','Miguel Gonzales','miguel.gonzales38@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'2000-06-26',NULL,NULL,NULL,NULL,'Laguna','09768321760','IT Support','Braille Reading','resume_38.pdf','Night Shift','2025-07-23 21:47:51','QR100038',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(39,'job_seeker','Maria Fernandez','maria.fernandez39@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Hearing Impairment',NULL,'2001-05-27',NULL,NULL,NULL,NULL,'Makati','09429699706','IT Support','Data Management','resume_39.pdf','Graveyard','2025-07-23 21:47:51','QR100039',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(40,'job_seeker','Lea Reyes','lea.reyes40@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Lower Limb',NULL,'1993-12-28',NULL,NULL,NULL,NULL,'Davao','09617829936','IT Support','Graphic Design','resume_40.pdf','Day Shift','2025-07-23 21:47:51','QR100040',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(41,'job_seeker','Andre Ramos','andre.ramos41@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Hearing Impairment',NULL,'1994-11-08',NULL,NULL,NULL,NULL,'Makati','09955035041','IT Support','Visual Communication, Sign Language','resume_41.pdf','Graveyard','2025-07-23 21:47:51','QR100041',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(42,'job_seeker','Noel Villanueva','noel.villanueva42@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1983-08-05',NULL,NULL,NULL,NULL,'Metro Manila','09953475743','IT Support','Audio Editing','resume_42.pdf','Graveyard','2025-07-23 21:47:51','QR100042',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(43,'job_seeker','Mark Lopez','mark.lopez43@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Upper Limb',NULL,'1982-07-31',NULL,NULL,NULL,NULL,'Metro Manila','09567870842','IT Support','Communication, Clerical Skills','resume_43.pdf','Day Shift','2025-07-23 21:47:51','QR100043',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(44,'job_seeker','Julia Garcia','julia.garcia44@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1987-08-17',NULL,NULL,NULL,NULL,'Baguio','09717159549','IT Support','Braille Reading','resume_44.pdf','Flexible','2025-07-23 21:47:51','QR100044',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(45,'job_seeker','Elena Gonzales','elena.gonzales45@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Speech Impairment',NULL,'1989-04-30',NULL,NULL,NULL,NULL,'Metro Manila','09385866312','IT Support','Problem Solving','resume_45.pdf','Night Shift','2025-07-23 21:47:51','QR100045',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(46,'job_seeker','Isla Mendoza','isla.mendoza46@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Hearing Impairment',NULL,'1998-09-11',NULL,NULL,NULL,NULL,'Makati','09370571517','IT Support','Sign Language','resume_46.pdf','Night Shift','2025-07-23 21:47:51','QR100046',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(47,'job_seeker','Luis Lopez','luis.lopez47@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Speech Impairment',NULL,'1987-07-24',NULL,NULL,NULL,NULL,'Metro Manila','09277909714','IT Support','Writing, Problem Solving','resume_47.pdf','Flexible','2025-07-23 21:47:51','QR100047',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(48,'job_seeker','Andre Fernandez','andre.fernandez48@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Hearing Impairment',NULL,'1995-09-13',NULL,NULL,NULL,NULL,'Iloilo','09292736955','IT Support','Visual Communication','resume_48.pdf','Graveyard','2025-07-23 21:47:51','QR100048',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(49,'job_seeker','Miguel Cruz','miguel.cruz49@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Lower Limb',NULL,'1992-10-28',NULL,NULL,NULL,NULL,'Davao','09405524553','IT Support','Graphic Design','resume_49.pdf','Night Shift','2025-07-23 21:47:51','QR100049',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(50,'job_seeker','Luna Morales','luna.morales50@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Lower Limb',NULL,'1986-01-18',NULL,NULL,NULL,NULL,'Iloilo','09539077280','IT Support','Computer Programming','resume_50.pdf','Day Shift','2025-07-23 21:47:51','QR100050',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(51,'job_seeker','Elena Lopez','elena.lopez51@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Speech Impairment',NULL,'1985-04-21',NULL,NULL,NULL,NULL,'Cebu','09989902557','IT Support','Writing','resume_51.pdf','Flexible','2025-07-23 21:47:51','QR100051',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(52,'job_seeker','Miguel Fernandez','miguel.fernandez52@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Speech Impairment',NULL,'1983-02-14',NULL,NULL,NULL,NULL,'Cebu','09785042243','IT Support','Analytical Thinking','resume_52.pdf','Night Shift','2025-07-23 21:47:51','QR100052',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(53,'job_seeker','Elena Morales','elena.morales53@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Speech Impairment',NULL,'1982-12-14',NULL,NULL,NULL,NULL,'Baguio','09914280014','IT Support','Analytical Thinking, Writing','resume_53.pdf','Flexible','2025-07-23 21:47:51','QR100053',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(54,'job_seeker','Juan Dela Cruz','juan.dela cruz54@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Upper Limb',NULL,'1993-03-02',NULL,NULL,NULL,NULL,'Baguio','09327337312','IT Support','Clerical Skills','resume_54.pdf','Day Shift','2025-07-23 21:47:51','QR100054',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(55,'job_seeker','Noel Ramos','noel.ramos55@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Hearing Impairment',NULL,'1984-07-11',NULL,NULL,NULL,NULL,'Makati','09838905838','IT Support','Sign Language, Data Management','resume_55.pdf','Flexible','2025-07-23 21:47:51','QR100055',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(56,'job_seeker','Pedro Mendoza','pedro.mendoza56@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Partial',NULL,'1983-07-14',NULL,NULL,NULL,NULL,'Baguio','09849038594','IT Support','Typing, Basic Computer Skills','resume_56.pdf','Graveyard','2025-07-23 21:47:51','QR100056',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(57,'job_seeker','Juan Fernandez','juan.fernandez57@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1984-09-04',NULL,NULL,NULL,NULL,'Metro Manila','09372272351','IT Support','Braille Reading','resume_57.pdf','Flexible','2025-07-23 21:47:51','QR100057',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(58,'job_seeker','Miguel Ramos','miguel.ramos58@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Upper Limb',NULL,'1995-04-07',NULL,NULL,NULL,NULL,'Quezon City','09609744159','IT Support','Communication','resume_58.pdf','Graveyard','2025-07-23 21:47:51','QR100058',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(59,'job_seeker','Noel Mendoza','noel.mendoza59@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Speech Impairment',NULL,'2000-06-28',NULL,NULL,NULL,NULL,'Davao','09534583534','IT Support','Problem Solving','resume_59.pdf','Flexible','2025-07-23 21:47:51','QR100059',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(60,'job_seeker','Carlos Ramos','carlos.ramos60@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1995-11-15',NULL,NULL,NULL,NULL,'Metro Manila','09127681707','IT Support','Braille Reading, Audio Editing','resume_60.pdf','Day Shift','2025-07-23 21:47:51','QR100060',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(61,'job_seeker','Carmen Fernandez','carmen.fernandez61@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Partial',NULL,'2002-04-11',NULL,NULL,NULL,NULL,'Baguio','09847232032','IT Support','Screen Reader Usage, Basic Computer Skills','resume_61.pdf','Night Shift','2025-07-23 21:47:51','QR100061',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(62,'job_seeker','Luna Morales','luna.morales62@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Upper Limb',NULL,'1990-05-19',NULL,NULL,NULL,NULL,'Makati','09138998261','IT Support','Communication, Clerical Skills','resume_62.pdf','Flexible','2025-07-23 21:47:51','QR100062',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(63,'job_seeker','Juan Garcia','juan.garcia63@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Partial',NULL,'1992-10-20',NULL,NULL,NULL,NULL,'Quezon City','09618664125','IT Support','Typing, Basic Computer Skills','resume_63.pdf','Day Shift','2025-07-23 21:47:51','QR100063',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(64,'job_seeker','Isla Gonzales','isla.gonzales64@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1989-06-07',NULL,NULL,NULL,NULL,'Laguna','09571747034','IT Support','Braille Reading','resume_64.pdf','Night Shift','2025-07-23 21:47:51','QR100064',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(65,'job_seeker','Ana Agustin','ana.agustin65@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Hearing Impairment',NULL,'2001-09-02',NULL,NULL,NULL,NULL,'Baguio','09811392625','IT Support','Sign Language','resume_65.pdf','Day Shift','2025-07-23 21:47:51','QR100065',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(66,'job_seeker','Elena Garcia','elena.garcia66@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Lower Limb',NULL,'1995-11-26',NULL,NULL,NULL,NULL,'Quezon City','09691170166','IT Support','Computer Programming','resume_66.pdf','Graveyard','2025-07-23 21:47:51','QR100066',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(67,'job_seeker','Isla Mendoza','isla.mendoza67@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Lower Limb',NULL,'1988-07-25',NULL,NULL,NULL,NULL,'Cebu','09321518813','IT Support','Computer Programming, Graphic Design','resume_67.pdf','Graveyard','2025-07-23 21:47:51','QR100067',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(68,'job_seeker','Grace Villanueva','grace.villanueva68@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Partial',NULL,'1982-12-12',NULL,NULL,NULL,NULL,'Laguna','09210997898','IT Support','Typing, Screen Reader Usage, Basic Computer Skills','resume_68.pdf','Graveyard','2025-07-23 21:47:51','QR100068',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(69,'job_seeker','James Reyes','james.reyes69@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Partial',NULL,'1983-11-09',NULL,NULL,NULL,NULL,'Cebu','09185430708','IT Support','Typing, Screen Reader Usage','resume_69.pdf','Graveyard','2025-07-23 21:47:51','QR100069',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(70,'job_seeker','Isla Morales','isla.morales70@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Lower Limb',NULL,'1999-11-03',NULL,NULL,NULL,NULL,'Metro Manila','09261604851','IT Support','Computer Programming, Graphic Design','resume_70.pdf','Graveyard','2025-07-23 21:47:51','QR100070',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(71,'job_seeker','Carlos Santos','carlos.santos71@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Hearing Impairment',NULL,'1996-07-02',NULL,NULL,NULL,NULL,'Iloilo','09416924227','IT Support','Sign Language, Data Management','resume_71.pdf','Graveyard','2025-07-23 21:47:51','QR100071',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(72,'job_seeker','Grace Ramos','grace.ramos72@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Hearing Impairment',NULL,'1993-08-30',NULL,NULL,NULL,NULL,'Davao','09221335105','IT Support','Sign Language, Data Management','resume_72.pdf','Night Shift','2025-07-23 21:47:51','QR100072',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(73,'job_seeker','Julia Dela Cruz','julia.dela cruz73@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Hearing Impairment',NULL,'2001-01-04',NULL,NULL,NULL,NULL,'Laguna','09664104755','IT Support','Data Management','resume_73.pdf','Day Shift','2025-07-23 21:47:51','QR100073',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(74,'job_seeker','Pedro Castro','pedro.castro74@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Upper Limb',NULL,'2001-05-12',NULL,NULL,NULL,NULL,'Laguna','09990848898','IT Support','Communication, Clerical Skills','resume_74.pdf','Graveyard','2025-07-23 21:47:51','QR100074',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(75,'job_seeker','Maria Ramos','maria.ramos75@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Hearing Impairment',NULL,'1980-04-24',NULL,NULL,NULL,NULL,'Davao','09309577725','IT Support','Visual Communication, Sign Language','resume_75.pdf','Flexible','2025-07-23 21:47:51','QR100075',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(76,'job_seeker','James Torres','james.torres76@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Upper Limb',NULL,'1986-01-12',NULL,NULL,NULL,NULL,'Makati','09712595706','IT Support','Clerical Skills','resume_76.pdf','Night Shift','2025-07-23 21:47:51','QR100076',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(77,'job_seeker','Elena Ramos','elena.ramos77@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Speech Impairment',NULL,'1983-12-07',NULL,NULL,NULL,NULL,'Baguio','09960270204','IT Support','Writing, Problem Solving','resume_77.pdf','Graveyard','2025-07-23 21:47:51','QR100077',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(78,'job_seeker','Juan Reyes','juan.reyes78@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Speech Impairment',NULL,'1984-09-20',NULL,NULL,NULL,NULL,'Iloilo','09639698437','IT Support','Analytical Thinking, Writing','resume_78.pdf','Flexible','2025-07-23 21:47:51','QR100078',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(79,'job_seeker','Luna Gonzales','luna.gonzales79@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Hearing Impairment',NULL,'1987-09-26',NULL,NULL,NULL,NULL,'Baguio','09667333652','IT Support','Sign Language, Data Management','resume_79.pdf','Night Shift','2025-07-23 21:47:51','QR100079',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(80,'job_seeker','Carlos Santos','carlos.santos80@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Hearing Impairment',NULL,'2000-09-27',NULL,NULL,NULL,NULL,'Iloilo','09643667473','IT Support','Visual Communication, Sign Language','resume_80.pdf','Night Shift','2025-07-23 21:47:51','QR100080',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(81,'job_seeker','Pedro Ramos','pedro.ramos81@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Hearing Impairment',NULL,'1997-08-28',NULL,NULL,NULL,NULL,'Cebu','09868414688','IT Support','Sign Language','resume_81.pdf','Graveyard','2025-07-23 21:47:51','QR100081',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(82,'job_seeker','Pedro Ramos','pedro.ramos82@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Partial',NULL,'1996-01-06',NULL,NULL,NULL,NULL,'Baguio','09651716236','IT Support','Typing, Screen Reader Usage','resume_82.pdf','Flexible','2025-07-23 21:47:51','QR100082',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(83,'job_seeker','Lea Lopez','lea.lopez83@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Partial',NULL,'1993-11-01',NULL,NULL,NULL,NULL,'Baguio','09203921033','IT Support','Screen Reader Usage, Basic Computer Skills','resume_83.pdf','Graveyard','2025-07-23 21:47:51','QR100083',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(84,'job_seeker','Elena Gonzales','elena.gonzales84@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Hearing Impairment',NULL,'1982-03-21',NULL,NULL,NULL,NULL,'Quezon City','09435979904','IT Support','Sign Language, Data Management','resume_84.pdf','Day Shift','2025-07-23 21:47:51','QR100084',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(85,'job_seeker','Mark Fernandez','mark.fernandez85@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Lower Limb',NULL,'2001-10-10',NULL,NULL,NULL,NULL,'Makati','09863446422','IT Support','Computer Programming, Graphic Design','resume_85.pdf','Flexible','2025-07-23 21:47:51','QR100085',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(86,'job_seeker','James Villanueva','james.villanueva86@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Partial',NULL,'1994-07-19',NULL,NULL,NULL,NULL,'Laguna','09179462150','IT Support','Screen Reader Usage, Basic Computer Skills','resume_86.pdf','Flexible','2025-07-23 21:47:51','QR100086',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(87,'job_seeker','Grace Agustin','grace.agustin87@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Lower Limb',NULL,'1983-07-14',NULL,NULL,NULL,NULL,'Makati','09364921289','IT Support','Graphic Design','resume_87.pdf','Flexible','2025-07-23 21:47:51','QR100087',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(88,'job_seeker','Mark Lopez','mark.lopez88@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1990-12-22',NULL,NULL,NULL,NULL,'Iloilo','09531449145','IT Support','Audio Editing','resume_88.pdf','Night Shift','2025-07-23 21:47:51','QR100088',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(89,'job_seeker','Faith Garcia','faith.garcia89@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Speech Impairment',NULL,'2000-05-23',NULL,NULL,NULL,NULL,'Metro Manila','09790291003','IT Support','Problem Solving','resume_89.pdf','Night Shift','2025-07-23 21:47:51','QR100089',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(90,'job_seeker','Isla Dela Cruz','isla.dela cruz90@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Partial',NULL,'1994-06-09',NULL,NULL,NULL,NULL,'Laguna','09356696833','IT Support','Typing, Screen Reader Usage','resume_90.pdf','Flexible','2025-07-23 21:47:51','QR100090',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(91,'job_seeker','Maria Castro','maria.castro91@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1980-10-15',NULL,NULL,NULL,NULL,'Metro Manila','09339212060','IT Support','Braille Reading, Audio Editing','resume_91.pdf','Flexible','2025-07-23 21:47:51','QR100091',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(92,'job_seeker','Jose Garcia','jose.garcia92@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1991-10-13',NULL,NULL,NULL,NULL,'Makati','09257800581','IT Support','Audio Editing','resume_92.pdf','Graveyard','2025-07-23 21:47:51','QR100092',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(93,'job_seeker','Lea Ramos','lea.ramos93@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Lower Limb',NULL,'1995-06-19',NULL,NULL,NULL,NULL,'Quezon City','09430849449','IT Support','Graphic Design','resume_93.pdf','Flexible','2025-07-23 21:47:51','QR100093',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(94,'job_seeker','Maria Santos','maria.santos94@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Lower Limb',NULL,'1996-03-11',NULL,NULL,NULL,NULL,'Laguna','09552494557','IT Support','Computer Programming','resume_94.pdf','Flexible','2025-07-23 21:47:51','QR100094',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(95,'job_seeker','Carmen Morales','carmen.morales95@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Full',NULL,'1981-07-12',NULL,NULL,NULL,NULL,'Laguna','09234309669','IT Support','Braille Reading','resume_95.pdf','Night Shift','2025-07-23 21:47:51','QR100095',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(96,'job_seeker','Mark Torres','mark.torres96@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Lower Limb',NULL,'1993-04-24',NULL,NULL,NULL,NULL,'Makati','09836713679','IT Support','Graphic Design','resume_96.pdf','Graveyard','2025-07-23 21:47:51','QR100096',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(97,'job_seeker','Carmen Alvarez','carmen.alvarez97@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Hearing Impairment',NULL,'1999-11-13',NULL,NULL,NULL,NULL,'Cebu','09829969277','IT Support','Data Management','resume_97.pdf','Graveyard','2025-07-23 21:47:51','QR100097',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(98,'job_seeker','Pedro Ramos','pedro.ramos98@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Physical - Upper Limb',NULL,'2000-02-26',NULL,NULL,NULL,NULL,'Cebu','09559951569','IT Support','Communication, Clerical Skills','resume_98.pdf','Graveyard','2025-07-23 21:47:51','QR100098',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(99,'job_seeker','James Dela Cruz','james.dela cruz99@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Speech Impairment',NULL,'1999-01-04',NULL,NULL,NULL,NULL,'Baguio','09906763933','IT Support','Writing','resume_99.pdf','Night Shift','2025-07-23 21:47:51','QR100099',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(100,'job_seeker','Luis Reyes','luis.reyes100@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO','Visual - Partial',NULL,'1992-01-18',NULL,NULL,NULL,NULL,'Davao','09294064884','IT Support','Typing','resume_100.pdf','Day Shift','2025-07-23 21:47:51','QR100100',NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(101,'client','Maria Alvarez','maria.alvarez1@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1997-05-30',NULL,NULL,NULL,NULL,'Laguna','09388430946',NULL,NULL,NULL,'Day Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(102,'client','James Alvarez','james.alvarez2@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'2002-11-17',NULL,NULL,NULL,NULL,'Laguna','09583315603',NULL,NULL,NULL,'Night Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(103,'client','James Fernandez','james.fernandez3@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1997-12-26',NULL,NULL,NULL,NULL,'Makati','09949680859',NULL,NULL,NULL,'Graveyard','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(104,'client','Ana Morales','ana.morales4@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1986-02-07',NULL,NULL,NULL,NULL,'Baguio','09184999029',NULL,NULL,NULL,'Day Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(105,'client','Julia Villanueva','julia.villanueva5@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'2003-04-16',NULL,NULL,NULL,NULL,'Makati','09783832195',NULL,NULL,NULL,'Flexible','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(106,'client','Grace Villanueva','grace.villanueva6@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'2002-11-04',NULL,NULL,NULL,NULL,'Makati','09513106628',NULL,NULL,NULL,'Night Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(107,'client','Carlos Torres','carlos.torres7@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1991-11-05',NULL,NULL,NULL,NULL,'Metro Manila','09544020711',NULL,NULL,NULL,'Flexible','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(108,'client','Lea Gonzales','lea.gonzales8@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1983-05-13',NULL,NULL,NULL,NULL,'Cebu','09326058346',NULL,NULL,NULL,'Day Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(109,'client','Elena Gonzales','elena.gonzales9@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1996-08-09',NULL,NULL,NULL,NULL,'Laguna','09162545144',NULL,NULL,NULL,'Flexible','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(110,'client','Mark Castro','mark.castro10@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1996-11-10',NULL,NULL,NULL,NULL,'Laguna','09174143378',NULL,NULL,NULL,'Graveyard','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(111,'client','Noel Alvarez','noel.alvarez11@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'2003-08-06',NULL,NULL,NULL,NULL,'Davao','09860050801',NULL,NULL,NULL,'Day Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(112,'client','Isla Cruz','isla.cruz12@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1981-12-07',NULL,NULL,NULL,NULL,'Makati','09168433878',NULL,NULL,NULL,'Night Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(113,'client','Juan Castro','juan.castro13@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1996-04-26',NULL,NULL,NULL,NULL,'Metro Manila','09892406805',NULL,NULL,NULL,'Flexible','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(114,'client','Elena Alvarez','elena.alvarez14@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1989-11-21',NULL,NULL,NULL,NULL,'Davao','09959247411',NULL,NULL,NULL,'Night Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(115,'client','James Garcia','james.garcia15@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1993-04-29',NULL,NULL,NULL,NULL,'Iloilo','09528077942',NULL,NULL,NULL,'Flexible','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(116,'client','Jose Garcia','jose.garcia16@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1982-10-26',NULL,NULL,NULL,NULL,'Laguna','09956191537',NULL,NULL,NULL,'Graveyard','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(117,'client','Miguel Mendoza','miguel.mendoza17@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1987-05-21',NULL,NULL,NULL,NULL,'Laguna','09148006512',NULL,NULL,NULL,'Night Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(118,'client','Carmen Villanueva','carmen.villanueva18@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1982-10-09',NULL,NULL,NULL,NULL,'Laguna','09352389337',NULL,NULL,NULL,'Night Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(119,'client','Lea Mendoza','lea.mendoza19@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1982-08-31',NULL,NULL,NULL,NULL,'Quezon City','09898451097',NULL,NULL,NULL,'Graveyard','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(120,'client','Ana Agustin','ana.agustin20@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1990-01-12',NULL,NULL,NULL,NULL,'Baguio','09788124399',NULL,NULL,NULL,'Night Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(121,'client','Maria Agustin','maria.agustin21@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1999-07-04',NULL,NULL,NULL,NULL,'Metro Manila','09908538071',NULL,NULL,NULL,'Graveyard','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(122,'client','Elena Cruz','elena.cruz22@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1999-10-04',NULL,NULL,NULL,NULL,'Metro Manila','09439698958',NULL,NULL,NULL,'Flexible','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(123,'client','Miguel Mendoza','miguel.mendoza23@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'2003-05-26',NULL,NULL,NULL,NULL,'Iloilo','09498848100',NULL,NULL,NULL,'Flexible','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(124,'client','Grace Castro','grace.castro24@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1984-01-09',NULL,NULL,NULL,NULL,'Cebu','09195320265',NULL,NULL,NULL,'Day Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(125,'client','Grace Alvarez','grace.alvarez25@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1997-01-31',NULL,NULL,NULL,NULL,'Quezon City','09203633485',NULL,NULL,NULL,'Graveyard','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(126,'client','Isla Garcia','isla.garcia26@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1993-11-15',NULL,NULL,NULL,NULL,'Davao','09605352517',NULL,NULL,NULL,'Graveyard','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(127,'client','Ana Fernandez','ana.fernandez27@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'2000-10-20',NULL,NULL,NULL,NULL,'Davao','09323915143',NULL,NULL,NULL,'Day Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(128,'client','Isla Reyes','isla.reyes28@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1998-04-13',NULL,NULL,NULL,NULL,'Iloilo','09773346315',NULL,NULL,NULL,'Flexible','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(129,'client','Faith Mendoza','faith.mendoza29@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1992-11-19',NULL,NULL,NULL,NULL,'Laguna','09544393446',NULL,NULL,NULL,'Graveyard','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(130,'client','Luna Fernandez','luna.fernandez30@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1992-11-02',NULL,NULL,NULL,NULL,'Quezon City','09499548590',NULL,NULL,NULL,'Graveyard','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(131,'client','Noel Garcia','noel.garcia31@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1981-12-06',NULL,NULL,NULL,NULL,'Iloilo','09438529803',NULL,NULL,NULL,'Night Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(132,'client','Carlos Torres','carlos.torres32@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1988-09-03',NULL,NULL,NULL,NULL,'Metro Manila','09616088032',NULL,NULL,NULL,'Graveyard','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(133,'client','Carlos Santos','carlos.santos33@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1995-02-28',NULL,NULL,NULL,NULL,'Iloilo','09850129276',NULL,NULL,NULL,'Graveyard','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(134,'client','Miguel Castro','miguel.castro34@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1999-12-01',NULL,NULL,NULL,NULL,'Laguna','09639403730',NULL,NULL,NULL,'Night Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(135,'client','Juan Fernandez','juan.fernandez35@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1984-09-21',NULL,NULL,NULL,NULL,'Baguio','09275913139',NULL,NULL,NULL,'Day Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(136,'client','Ana Castro','ana.castro36@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1989-11-16',NULL,NULL,NULL,NULL,'Makati','09953435165',NULL,NULL,NULL,'Day Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(137,'client','Jose Ramos','jose.ramos37@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1992-06-17',NULL,NULL,NULL,NULL,'Quezon City','09730563509',NULL,NULL,NULL,'Flexible','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(138,'client','James Villanueva','james.villanueva38@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'2001-04-30',NULL,NULL,NULL,NULL,'Iloilo','09496128260',NULL,NULL,NULL,'Flexible','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(139,'client','Carlos Cruz','carlos.cruz39@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1989-10-13',NULL,NULL,NULL,NULL,'Cebu','09362411488',NULL,NULL,NULL,'Night Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(140,'client','Andre Garcia','andre.garcia40@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1986-08-20',NULL,NULL,NULL,NULL,'Laguna','09754024509',NULL,NULL,NULL,'Night Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(141,'client','Luna Agustin','luna.agustin41@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1983-04-21',NULL,NULL,NULL,NULL,'Laguna','09939906505',NULL,NULL,NULL,'Graveyard','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(142,'client','Jose Gonzales','jose.gonzales42@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1985-08-27',NULL,NULL,NULL,NULL,'Baguio','09736790503',NULL,NULL,NULL,'Flexible','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(143,'client','Juan Reyes','juan.reyes43@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1983-07-27',NULL,NULL,NULL,NULL,'Laguna','09369694222',NULL,NULL,NULL,'Flexible','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(144,'client','Elena Ramos','elena.ramos44@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1988-12-09',NULL,NULL,NULL,NULL,'Laguna','09481783038',NULL,NULL,NULL,'Night Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(145,'client','Faith Garcia','faith.garcia45@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'2003-02-21',NULL,NULL,NULL,NULL,'Laguna','09474700433',NULL,NULL,NULL,'Graveyard','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(146,'client','Jose Dela Cruz','jose.dela cruz46@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1986-12-27',NULL,NULL,NULL,NULL,'Quezon City','09450514085',NULL,NULL,NULL,'Graveyard','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(147,'client','James Dela Cruz','james.dela cruz47@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'2000-10-24',NULL,NULL,NULL,NULL,'Iloilo','09595462497',NULL,NULL,NULL,'Flexible','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(148,'client','Ana Villanueva','ana.villanueva48@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1991-01-06',NULL,NULL,NULL,NULL,'Laguna','09195532991',NULL,NULL,NULL,'Night Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(149,'client','Andre Castro','andre.castro49@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1995-06-27',NULL,NULL,NULL,NULL,'Laguna','09667572244',NULL,NULL,NULL,'Day Shift','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(150,'client','Carlos Torres','carlos.torres50@email.com','$2y$10$TbCWyHxGojFfBQ7J/BlX0.H/hWOf.ZNZ4YFZKDhNCx/U0aFaM8MsO',NULL,NULL,'1981-05-28',NULL,NULL,NULL,NULL,'Metro Manila','09716648921',NULL,NULL,NULL,'Graveyard','2025-07-23 21:47:51',NULL,NULL,'1','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(160,'client','Denro Maban','Denromaban@gmail.com','$2y$10$RMLLvJhWGruPoaihubLHWOTdi38kTHIlC3THRx0KVg2pKuOXXcR22',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-07-23 23:23:20',NULL,NULL,'0','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(161,'client','Andrew Batuigas','AndrewBatuigas@gmail.com','$2y$10$w209g0WbxOmQ2ddEdqHMledSKwl/Iv4/6RE/8EzXL6dyZ1jKOSgEm',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-07-23 23:23:20',NULL,NULL,'0','','','',0,'../assets/uploads/upload_688174c103afd_494821825_1392253245418218_5791246877300621837_n.jpg',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(162,'client','John Vincent Santiago','JayveeSantiago@gmail.com','$2y$10$70Q.X2m8t/cOEmXSjkyyUePOJ5BtO4Un4E1dv/ZR2B7./Yp/DEC/u',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-07-23 23:23:20',NULL,NULL,'0','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(163,'client','Gino Hilado','GinoHilado@gmail.com','$2y$10$0MjuTGQdFxXWTlcEinY35.qxfSaebEEsy.m0.f5KEMTtTscLgWYY6',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-07-23 23:23:20',NULL,NULL,'0','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(164,'client','Jason Moraña','JasonMorana@gmail.com','$2y$10$pBeJhGIZH3ikqiszec9RCew/R7CKjGkAHKfiEkT/BZAlGEfDPSWum',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-07-23 23:23:20',NULL,NULL,'0','','','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0),
(168,'job_seeker','ALEXANDER M HECHANOVA JR','Aljiehechanova23@gmail.com','$2y$10$AjotZfBnDeyP3qTj6MvzT.DvTvR4iKb5VCuKWbI.39AazP8zUOZOG','Physical - Upper Limb',NULL,'1988-12-05',NULL,NULL,NULL,NULL,'brgy taculing','0930',NULL,NULL,'uploads/resumes/resume_688b039b41b01.pdf',NULL,'2025-07-28 01:12:22','9bf595f44eef588841c1ee8975f94f5c',NULL,'1','','jknkjnbkj','',0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
commit;

--
-- Table structure for table `workshop`
--

DROP TABLE IF EXISTS `workshop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `workshop` (
  `workshop_id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `work_title` varchar(255) NOT NULL,
  `entry_date` date NOT NULL,
  `end_date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `target_skills` varchar(255) NOT NULL,
  PRIMARY KEY (`workshop_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workshop`
--

/*!40000 ALTER TABLE `workshop` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `workshop` VALUES
(1,4,'Accessible Web Design Workshop','2025-06-01','2025-06-05','Lacson Street, Bacolod City','Denro Maban','Training in designing accessible websites.','HTML, CSS, WCAG'),
(2,6,'Customer Support Training for PWDs','2025-06-10','2025-06-15','Barangay Villamonte, Bacolod City','Andrew Batuidas','Improve communication skills for support roles.','Customer Service, Communication'),
(3,7,'Massage Therapy Basics','2025-06-20','2025-06-25','Bacolod City Government Center','John Vincent Santiago','Hands-on training for massage therapy.','Massage Techniques'),
(4,8,'Basic Data Encoding Workshop','2025-06-05','2025-06-07','Barangay 7, Bacolod City','Gino Hilado','Intro to data entry tasks using assistive tools.','Typing, Accuracy'),
(5,9,'Graphic Design for Beginners','2025-06-12','2025-06-17','Capitol Shopping Center, Bacolod City','Jason Moraña','Basics of graphic design using Canva and Photoshop.','Design, Creativity'),
(6,20,'Social Media Marketing Workshop','2025-06-18','2025-06-21','Barangay Taculing, Bacolod City','Sophia Turner','Using social media tools for marketing.','Marketing, SMM'),
(7,21,'Public Speaking for the Hearing-Impaired','2025-06-25','2025-06-30','Barangay Alijis, Bacolod City','Liam Hayes','Confidence building and non-verbal cues in speaking.','Public Speaking, Confidence'),
(8,22,'Content Writing Bootcamp','2025-07-01','2025-07-05','Barangay Mandalagan, Bacolod City','Isabella Grant','Developing strong writing skills for blogs.','Writing, Editing'),
(9,23,'Technical Support Certification','2025-07-08','2025-07-12','Barangay Estefania, Bacolod City','Ethan Price','Learn to troubleshoot technical issues.','Tech Support, Problem Solving'),
(10,24,'Workshop on Adaptive Technologies','2025-07-14','2025-07-17','Barangay Tangub, Bacolod City','Ava Scott','Training on using assistive technology tools.','Assistive Tools, Adaptability'),
(11,25,'Job Readiness for Visually Impaired','2025-07-20','2025-07-24','Barangay Singcang-Airport, Bacolod City','Noah Adams','Focus on tools and skills for job preparation.','Computer Literacy, Interview Skills'),
(12,26,'Introduction to Freelance Writing','2025-07-26','2025-07-30','Barangay Granada, Bacolod City','Mia Brooks','Starting a freelance writing career.','Writing, Grammar'),
(13,27,'Programming 101 for PWDs','2025-08-01','2025-08-07','Barangay Sum-ag, Bacolod City','Oliver Reed','Learning the basics of programming.','Python, Logic'),
(14,28,'Video Editing with Accessibility Tools','2025-08-10','2025-08-14','Barangay Banago, Bacolod City','Amelia Ross','Training on video editing software with accessibility.','Editing, Creativity'),
(15,29,'Marketing Strategies for Entrepreneurs','2025-08-18','2025-08-22','Barangay Pahanocoy, Bacolod City','Elijah Bennett','Learn how to market small businesses.','Marketing, Strategy');
/*!40000 ALTER TABLE `workshop` ENABLE KEYS */;
commit;

--
-- Table structure for table `workshop_volunteers`
--

DROP TABLE IF EXISTS `workshop_volunteers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `workshop_volunteers` (
  `volunteer_id` int(11) NOT NULL AUTO_INCREMENT,
  `workshop_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `volunteered_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`volunteer_id`),
  KEY `workshop_id` (`workshop_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workshop_volunteers`
--

/*!40000 ALTER TABLE `workshop_volunteers` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `workshop_volunteers` VALUES
(3,2,2,'2025-05-26 01:39:33'),
(4,3,4,'2025-01-12 02:15:00'),
(5,4,5,'2025-01-25 06:30:00'),
(6,5,6,'2025-02-03 01:45:00'),
(7,6,7,'2025-02-17 03:20:00'),
(8,7,8,'2025-02-28 05:10:00'),
(9,8,9,'2025-03-05 00:55:00'),
(10,9,10,'2025-03-19 08:40:00'),
(11,10,11,'2025-03-27 04:00:00'),
(12,11,12,'2025-04-06 02:00:00'),
(13,12,13,'2025-04-22 07:35:00'),
(14,5,33,'2025-01-14 01:45:00'),
(15,12,45,'2025-01-29 06:10:00'),
(16,3,38,'2025-02-03 02:30:00'),
(17,7,31,'2025-02-12 00:55:00'),
(18,1,42,'2025-02-22 03:00:00'),
(19,11,36,'2025-03-05 05:15:00'),
(20,6,47,'2025-03-13 01:30:00'),
(21,8,50,'2025-03-22 07:45:00'),
(22,15,39,'2025-03-30 04:25:00'),
(23,4,34,'2025-04-01 03:40:00'),
(24,2,44,'2025-04-09 06:50:00'),
(25,9,37,'2025-04-15 02:00:00'),
(26,10,35,'2025-04-21 08:10:00'),
(27,13,49,'2025-04-26 01:20:00'),
(28,14,32,'2025-04-30 05:35:00'),
(29,1,46,'2025-05-04 02:45:00'),
(30,6,48,'2025-05-10 06:55:00'),
(31,3,40,'2025-05-15 04:05:00'),
(32,7,43,'2025-05-20 01:15:00'),
(33,2,41,'2025-05-25 03:30:00'),
(34,11,92,'2024-08-21 19:05:21'),
(35,12,43,'2025-12-11 21:28:14'),
(36,6,12,'2024-02-05 09:33:30'),
(37,8,42,'2024-04-02 05:43:25'),
(38,2,70,'2023-02-26 02:43:31'),
(39,14,3,'2024-05-11 19:21:19'),
(40,14,2,'2024-12-17 02:37:09'),
(41,12,45,'2025-07-18 15:41:35'),
(42,6,85,'2023-01-02 23:31:19'),
(43,12,90,'2023-02-02 23:04:25'),
(44,10,33,'2025-08-25 14:26:13'),
(45,6,95,'2025-08-03 12:09:16'),
(46,5,92,'2025-10-21 08:59:46'),
(47,11,22,'2023-09-10 13:42:39'),
(48,13,46,'2025-07-11 04:16:52'),
(49,13,52,'2023-03-12 13:06:23'),
(50,8,88,'2025-08-20 12:44:07'),
(51,9,22,'2024-05-20 08:32:23'),
(52,11,30,'2024-04-07 05:38:06'),
(53,8,70,'2023-01-05 15:35:41'),
(54,12,93,'2023-10-27 08:28:13'),
(55,15,75,'2025-12-05 00:00:07'),
(56,8,63,'2024-11-22 05:59:15'),
(57,14,75,'2023-11-11 04:03:00'),
(58,12,22,'2025-08-17 01:49:08'),
(59,9,74,'2025-04-22 22:15:00'),
(60,6,99,'2025-05-13 00:39:56'),
(61,12,78,'2024-07-27 04:02:34'),
(62,15,39,'2025-09-23 18:26:12'),
(63,7,87,'2023-07-16 04:28:10'),
(64,5,82,'2023-10-07 20:03:23'),
(65,7,70,'2023-09-26 11:37:27'),
(66,11,18,'2025-11-12 10:10:56'),
(67,13,3,'2024-08-19 06:47:28'),
(68,7,46,'2025-01-14 10:08:55'),
(69,10,81,'2023-06-27 04:32:34'),
(70,3,13,'2023-09-23 22:26:39'),
(71,5,58,'2023-07-19 16:02:09'),
(72,5,55,'2025-04-08 09:35:10'),
(73,3,61,'2024-11-25 16:18:12'),
(74,12,80,'2025-12-27 17:46:20'),
(75,14,12,'2025-08-28 02:02:13'),
(76,8,34,'2023-07-08 19:06:44'),
(77,3,51,'2025-05-28 18:43:25'),
(78,15,37,'2025-11-11 07:37:10'),
(79,7,18,'2024-07-20 23:43:55'),
(80,13,25,'2025-11-02 17:52:27'),
(81,9,58,'2023-03-18 09:30:59'),
(82,2,37,'2024-05-09 09:17:15'),
(83,2,20,'2025-03-25 17:16:05'),
(84,15,55,'2025-12-27 20:45:46'),
(85,8,1,'2024-05-09 13:22:44'),
(86,13,46,'2025-08-22 22:18:44'),
(87,6,97,'2025-01-19 08:59:14'),
(88,13,8,'2025-02-01 15:25:17'),
(89,4,10,'2025-01-16 16:59:47'),
(90,13,19,'2024-03-25 18:25:26'),
(91,4,96,'2023-02-22 08:15:30'),
(92,7,17,'2024-01-18 01:34:42'),
(93,7,100,'2024-03-07 11:49:21'),
(94,12,28,'2023-09-08 03:12:48'),
(95,14,12,'2025-12-09 13:12:19'),
(96,13,66,'2025-06-03 03:32:12'),
(97,8,1,'2024-10-09 01:31:14'),
(98,7,5,'2025-03-13 15:21:23'),
(99,13,26,'2025-02-10 07:16:30'),
(100,8,52,'2023-02-03 04:52:09'),
(101,11,98,'2025-09-10 12:58:58'),
(102,11,98,'2025-07-21 23:16:23'),
(103,10,36,'2025-01-20 10:53:14'),
(104,3,31,'2023-10-13 21:39:06'),
(105,3,19,'2024-06-05 09:50:41'),
(106,9,48,'2025-05-15 11:00:38'),
(107,12,53,'2023-10-05 09:20:41'),
(108,8,26,'2025-04-24 12:06:18'),
(109,15,34,'2025-09-12 22:18:43'),
(110,6,8,'2023-02-25 00:43:48'),
(111,8,28,'2025-12-02 08:28:53'),
(112,9,79,'2023-10-05 11:43:19'),
(113,15,38,'2025-10-02 21:52:44'),
(114,6,7,'2023-12-07 03:21:02'),
(115,1,80,'2025-10-17 10:04:03'),
(116,6,60,'2025-08-12 05:40:04'),
(117,15,98,'2023-06-02 21:12:24'),
(118,4,68,'2024-11-17 06:46:26'),
(119,11,62,'2023-03-26 23:43:29'),
(120,4,11,'2025-01-21 11:40:45'),
(121,4,58,'2023-07-23 13:04:33'),
(122,5,76,'2025-06-12 17:46:11'),
(123,7,3,'2025-10-16 11:23:36'),
(124,11,7,'2023-12-05 21:31:07'),
(125,15,28,'2024-03-26 19:21:41'),
(126,6,29,'2024-02-13 21:09:53'),
(127,1,61,'2025-07-27 04:57:07'),
(128,3,37,'2023-12-09 12:13:02'),
(129,10,80,'2025-06-08 01:57:44'),
(130,9,68,'2024-01-11 18:30:51'),
(131,11,27,'2023-02-02 21:43:51'),
(132,6,12,'2024-12-15 16:09:14'),
(133,12,94,'2023-10-27 08:06:07');
/*!40000 ALTER TABLE `workshop_volunteers` ENABLE KEYS */;
commit;

--
-- Dumping routines for database 'u227479843_TrabahoPWede'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-11-20  5:26:08
