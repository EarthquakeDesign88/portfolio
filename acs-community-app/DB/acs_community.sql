-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 15, 2024 at 08:12 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `acs_community`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `imagePath` varchar(255) NOT NULL,
  `title` varchar(100) NOT NULL,
  `subtitle` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `totalThank` int(11) NOT NULL,
  `totalView` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `imagePath`, `title`, `subtitle`, `type`, `totalThank`, `totalView`, `date`) VALUES
(1, 'https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80', 'ประกาศสำคัญ 1', 'รายละเอียด 1', 'important', 1, 86, '2023-07-18 14:21:36'),
(2, 'https://images.unsplash.com/photo-1546074177-ffdda98d214f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80', 'ประกาศสำคัญ 2', 'รายละเอียด 2', 'important', 24, 86, '2023-07-18 14:21:36'),
(3, 'https://images.unsplash.com/photo-1559526324-c1f275fbfa32?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', 'ประกาศสำคัญ 3', 'รายละเอียด 3', 'important', 26, 46, '2023-07-18 17:21:36'),
(4, 'https://images.unsplash.com/photo-1601893211509-81b6d03e46a0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', 'ข่าวสารทั่วไป 1', 'รายละเอียด 1', 'general', 6, 74, '2023-07-19 17:21:36'),
(5, 'https://images.unsplash.com/photo-1634577004337-1df1fd52f914?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', 'ข่าวสารทั่วไป 2', 'รายละเอียด 2', 'general', 60, 14, '2023-07-22 17:21:36'),
(6, 'https://images.unsplash.com/photo-1628349407899-46565857ab55?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', 'ข่าวสารทั่วไป 3', 'รายละเอียด 3', 'general', 40, 24, '2023-07-23 17:21:36'),
(7, 'https://images.unsplash.com/photo-1559526324-c1f275fbfa32?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=870&q=80', 'ประกาศสำคัญ 4', 'รายละเอียด 4', 'important', 20, 14, '2023-07-23 18:21:36'),
(8, 'https://images.unsplash.com/photo-1563430862227-8fe668221256?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', 'ข่าวสารทั่วไป 4', 'รายละเอียด 4', 'general', 22, 10, '2023-07-23 19:21:36'),
(9, 'https://images.unsplash.com/photo-1563430862227-8fe668221256?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', 'ข่าวสารทั่วไป 5', 'รายละเอียด 5', 'general', 5, 5, '2023-07-23 19:21:36'),
(10, 'https://images.unsplash.com/photo-1563430862227-8fe668221256?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', 'ข่าวสารทั่วไป 6', 'รายละเอียด 6', 'general', 5, 5, '2023-07-23 19:21:36');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int(11) NOT NULL,
  `imagePath` varchar(255) NOT NULL,
  `title` varchar(100) NOT NULL,
  `subtitle` varchar(100) NOT NULL,
  `rule` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `imagePath`, `title`, `subtitle`, `rule`) VALUES
(1, 'https://images.unsplash.com/photo-1600431521340-491eca880813?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1169&q=80', 'ห้องสมุด', 'ชั้น 26 สวีท', '1.ห้ามนำเครื่องดื่ม, อาหาร เข้ามารับประทานในห้องสมุด 2.งดใช้เสียง'),
(2, 'https://images.unsplash.com/photo-1575429198097-0414ec08e8cd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', 'สระว่ายน้ำ', 'อาคาร สวีท', ''),
(3, 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', 'สระว่ายน้ำ', 'ชั้น 7 อาคาร ซี', ''),
(4, 'https://images.unsplash.com/photo-1542766788-a2f588f447ee?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1176&q=80', 'ห้องฟิตเนส', 'ชั้น 7 อาคาร ซี', ''),
(5, 'https://plus.unsplash.com/premium_photo-1661928260943-4aa36c5e1acc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1288&q=80', 'ห้องรับรอง', 'ชั้น 7 อาคาร ซี', ''),
(6, 'https://images.unsplash.com/photo-1631889993959-41b4e9c6e3c5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80', 'อ่างจากุชชี่', 'อาคาร สวีท', ''),
(7, 'https://images.unsplash.com/photo-1514914197726-5a7c4ab2d6ea?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1169&q=80', 'ห้องสนุกเกอร์', 'ชั้น 26 สวีท', ''),
(8, 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', 'ห้องสควอช', 'ชั้น 28', ''),
(9, 'https://images.unsplash.com/photo-1630703178161-1e2f9beddbf8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', 'ห้องฟิตเนส', 'ชั้น 7 M', ''),
(10, 'https://plus.unsplash.com/premium_photo-1675615667993-1ad49a0a1720?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', 'ห้องดูหนัง', 'ชั้น 7 M อาคาร ซี', ''),
(11, 'https://plus.unsplash.com/premium_photo-1661903136240-a97367001a64?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', 'ห้องรับรอง', 'ชั้น 7 M', ''),
(12, 'https://images.unsplash.com/photo-1583416750470-965b2707b355?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80', 'ห้องซาวน่า', 'ชั้นดาดฟ้า', '');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`) VALUES
(1, 'พัสดุหายหรือมีปัญหา ต้องทำอย่างไร?', 'ACS Community เป็นเครื่องมือช่วยแจ้งเตือนพัสดุแก่ท่าน ดังนั้นหากมีปัญหาใด ๆ เกี่ยวกับพัสดุ กรุณาติดต่อเจ้าหน้าที่นิติบุคคลของท่าน'),
(2, 'หากอยู่ผิดบ้านเลขที่/ห้อง ต้องทำอย่างไร?', ''),
(3, 'เพิ่ม/ลด สมาชิกในห้อง อย่างไร?', ''),
(4, 'เป็นเจ้าของหลายห้อง ต้องทำอย่างไร?', ''),
(5, 'เป็นเจ้าของคอนโดหลายแห่ง ต้องทำอย่างไร?', ''),
(6, 'หน้าสมุดโทรศัพท์ข้อมูลไม่ครบถ้วน จะเพิ่มข้อมูลเบอร์โทรศัพท์ได้อย่างไร?', ''),
(7, 'มีฟีเจอร์พิเศษสำหรับกรรมการหรือไม่ และจะใช้งานฟีเจอร์นั้นได้อย่างไร?', ''),
(8, 'รหัสเชิญหาย/หมดอายุ ขอใหม่จากที่ไหน?', ''),
(9, 'How to change language? เปลี่ยนภาษาอย่างไร?', '');

-- --------------------------------------------------------

--
-- Table structure for table `juristic_info`
--

CREATE TABLE `juristic_info` (
  `id` int(11) NOT NULL,
  `contactLine` varchar(100) NOT NULL,
  `contactNumber` varchar(100) NOT NULL,
  `contactEmail` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `juristic_info`
--

INSERT INTO `juristic_info` (`id`, `contactLine`, `contactNumber`, `contactEmail`) VALUES
(1, '@elephanttower', '081111111', 'elephant_tower@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `phonebooks`
--

CREATE TABLE `phonebooks` (
  `contact_id` int(11) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `contact_cate` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `phonebooks`
--

INSERT INTO `phonebooks` (`contact_id`, `contact_name`, `contact_number`, `contact_cate`) VALUES
(1, 'ชั้น 7 C สระว่ายน้ำ', '6006', '1'),
(2, 'รปภ ลานจอด P5B', '6004', '1'),
(3, 'รปภ ลานจอด P7A', '6005', '1'),
(4, 'ห้องช่าง', '5005', '1'),
(5, 'ห้องช่าง', '029374960', '1'),
(6, 'เคาน์เตอร์ชั้น 1 A', '6000', '1'),
(7, 'เคาน์เตอร์ชั้น 1 B', '6001', '1'),
(8, 'เคาน์เตอร์ชั้น 1 C', '6002', '1'),
(9, 'Operator โทรจากสายภายในให้โชว์เบอร์', '9', '2'),
(10, 'กองปราบปราม', '1195', '2'),
(11, 'ศูนย์ดับเพลิงศรีอยุธยา', '119', '2'),
(12, 'ศูนย์นเรนทร (รับแจ้งเจ็บป่วยฉุกเฉิน)', '1669', '2'),
(13, 'สถานีดับเพลิงสุทธิสาร', '022773688.9', '2'),
(14, 'สถานีตำรวจนครบาลพหลโยธิน', '025122447.9', '2'),
(15, 'เหตุด่วนเหตุร้าย', '191', '2'),
(16, 'เคาน์เตอร์โรงแรม', '029374111', '3'),
(17, 'เคาน์เตอร์โรงแรม', '7777', '3');

-- --------------------------------------------------------

--
-- Table structure for table `qr_scanner`
--

CREATE TABLE `qr_scanner` (
  `qr_id` int(11) NOT NULL,
  `qr_data` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL,
  `generate_time` datetime NOT NULL,
  `expiration_time` datetime NOT NULL,
  `auth_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `qr_scanner`
--

INSERT INTO `qr_scanner` (`qr_id`, `qr_data`, `status`, `generate_time`, `expiration_time`, `auth_time`) VALUES
(1, 'eu6qt34bs3siwg8a370ujuepi0nn1hek4mbbq6mewlftadjdj1', 'Authenticated successfully', '2023-10-19 15:21:59', '2023-10-19 15:24:59', '2023-10-19 15:22:07'),
(2, '1em69gxgwbwm6lxmkcy84kt166igaj0sjud02riu7jxhkcodb8', 'Authenticated successfully', '2023-10-19 15:22:12', '2023-10-19 15:25:12', '2023-10-19 15:22:19'),
(3, 'jtmcjlq6eofc2kj0tl46op0fjenurkxrjwgolv0ikhawjv2qpe', 'Identity Authentication', '2023-10-19 15:29:06', '2023-10-19 15:32:06', NULL),
(4, 'sh93eth27rmx9ahtwfw1sgvbhswqwqhtf85vqw469sk5ts3q4f', 'Identity Authentication', '2023-10-19 15:32:47', '2023-10-19 15:35:47', NULL),
(5, 'qhxu6vasmghxy2jx2qg0uptsaxtjiz3x10ox5eevfjisitpqti', 'Authenticated successfully', '2023-10-19 15:33:39', '2023-10-19 15:36:39', '2023-10-19 15:34:51'),
(6, 'wp5nb7o8umga2kzlqhdmt7o7l96zpg7m612z671q91h2v8yv30', 'Authenticated successfully', '2023-10-19 15:34:57', '2023-10-19 15:37:57', '2023-10-19 15:35:05'),
(7, 'wuvuq1jrx63uy637pnja15wm9loxcxsklo97ly60j6p6lu2ubu', 'Authenticated successfully', '2023-10-19 15:35:08', '2023-10-19 15:38:08', '2023-10-19 15:35:17'),
(8, 'dm1ohz0en7xn863tw7wijnoomfnbpirqe4gc5ecata8yat4iq1', 'Authenticated successfully', '2023-10-19 15:38:00', '2023-10-19 15:41:00', '2023-10-19 15:38:08'),
(9, 'ff55giqxbqienwjd4mq2khn6saa19vomnmiprhyyeota2pe3r8', 'Identity Authentication', '2023-10-19 15:38:19', '2023-10-19 15:41:19', NULL),
(10, 'd7asrttfvkqm3zg1gtiz1f4ri6ety98sdex2xa0xw7566xn9x7', 'Authenticated successfully', '2023-10-19 15:38:25', '2023-10-19 15:41:25', '2023-10-19 15:38:33'),
(11, '4gd7pdbgo0aambpgszc0m50wsnmvkls3d123av8v3j1zk09fry', 'Authenticated successfully', '2023-10-19 15:42:13', '2023-10-19 15:45:13', '2023-10-19 15:42:43'),
(12, '2g2e8t8tt7iww0n778fr9jdn71xg9ijblhdor081t474hzrbxv', 'Authenticated successfully', '2023-10-19 15:42:47', '2023-10-19 15:45:47', '2023-10-19 15:42:55'),
(13, 'hvs4soqyd3e9ihof3il6261426854trtyn5xp9wljfcq6gi8hb', 'Identity Authentication', '2023-10-19 15:42:57', '2023-10-19 15:45:57', NULL),
(14, '1oqjjftkhm26p4zyby35lemfcvn79ognflkn1jro2cwjzrlbrg', 'Authenticated successfully', '2023-10-19 15:46:09', '2023-10-19 15:49:09', '2023-10-19 15:46:15'),
(15, 'x23c3ybffmo8kxkou52s4b021lbm0zv1t0g3i774t43co3lldv', 'Authenticated successfully', '2023-10-19 15:51:42', '2023-10-19 15:54:42', '2023-10-19 15:51:49'),
(16, 'aimyhkvmqvq7h0zneoy73rukop2ytak9a4hz7v9yorswc49kyh', 'Authenticated successfully', '2023-10-19 15:51:52', '2023-10-19 15:54:52', '2023-10-19 15:51:58'),
(17, 'bbolbk19nnzs0kf9bc7ck6l8w6ml08fmkfrslt0hjs4dkxsdo9', 'Authenticated successfully', '2023-10-19 15:52:00', '2023-10-19 15:55:00', '2023-10-19 15:52:08'),
(18, 'j0uafxuc5vw4d0h07owm7q29xkopt7icixn2qt4x0mb19xwl24', 'Authenticated successfully', '2023-10-19 15:52:10', '2023-10-19 15:55:10', '2023-10-19 15:52:19'),
(19, 'lpy5se5zlqoffl996yk7unaknqufz8qgz2hv35jz9vck1p4mui', 'Authenticated successfully', '2023-10-20 08:29:55', '2023-10-20 08:32:55', '2023-10-20 08:30:05'),
(20, 'a7am7v8zm32pptmuic63phlb284c1uxgngkmrs11hi4vg2gp41', 'Authenticated successfully', '2023-10-20 08:31:26', '2023-10-20 08:34:26', '2023-10-20 08:31:42'),
(21, '0chnml7s1r8cgmhnji0f847lgorvs34wfxb8p951nryf4dt8d6', 'Identity Authentication', '2023-10-20 12:33:17', '2023-10-20 12:36:17', NULL),
(22, '69zb65qw4t2yc68paam0skeg4r5blumgw9g08lh9fnxkup2j45', 'Identity Authentication', '2023-10-20 13:04:51', '2023-10-20 13:07:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `suggestions`
--

CREATE TABLE `suggestions` (
  `id` int(11) NOT NULL,
  `topic` varchar(255) DEFAULT NULL,
  `detail` varchar(255) NOT NULL,
  `phoneNumber` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `image1` varchar(255) NOT NULL,
  `image2` varchar(255) NOT NULL,
  `image3` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `user_created` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `suggestions`
--

INSERT INTO `suggestions` (`id`, `topic`, `detail`, `phoneNumber`, `email`, `image1`, `image2`, `image3`, `created_at`, `user_created`) VALUES
(1, 'Topic test', 'details', '0123456789', 'test@gmail.com', 'base64_encoded_image_1', 'base64_encoded_image_2', 'base64_encoded_image_3', NULL, NULL),
(2, 'Topic test', 'details', '0123456789', 'test@gmail.com', 'base64_encoded_image_1', 'base64_encoded_image_2', 'base64_encoded_image_3', NULL, NULL),
(3, 'Topic test', 'details', '0123456789', 'test@gmail.com', 'base64_encoded_image_1', 'base64_encoded_image_2', 'base64_encoded_image_3', NULL, NULL),
(4, 'tyy', 'dd\n', '5555', 'dessf', '/9j/4QFwRXhpZgAATU0AKgAAAAgABwEAAAQAAAABAAAFoAEQAAIAAAAUAAAAYgEBAAQAAAABAAAHgAEPAAIAAAAHAAAAdodpAAQAAAABAAAAkQESAAMAAAABAAEAAAEyAAIAAAAUAAAAfQAAAABzZGtfZ3Bob25lNjRfeDg2XzY0AEdvb2dsZQAyMDIzOjA4OjA0IDExOjI2OjEzAAAHpAMAAwAAAAEAAAAAkgoABQAAAAEAAADrgpoABQAAAAE', '/9j/4QFwRXhpZgAATU0AKgAAAAgABwEAAAQAAAABAAAFoAEQAAIAAAAUAAAAYgEBAAQAAAABAAAHgAEPAAIAAAAHAAAAdodpAAQAAAABAAAAkQESAAMAAAABAAEAAAEyAAIAAAAUAAAAfQAAAABzZGtfZ3Bob25lNjRfeDg2XzY0AEdvb2dsZQAyMDIzOjA4OjA0IDExOjI2OjEzAAAHpAMAAwAAAAEAAAAAkgoABQAAAAEAAADrgpoABQAAAAE', '/9j/4QFwRXhpZgAATU0AKgAAAAgABwEAAAQAAAABAAAFoAEQAAIAAAAUAAAAYgEBAAQAAAABAAAHgAEPAAIAAAAHAAAAdodpAAQAAAABAAAAkQESAAMAAAABAAEAAAEyAAIAAAAUAAAAfQAAAABzZGtfZ3Bob25lNjRfeDg2XzY0AEdvb2dsZQAyMDIzOjA4OjA0IDExOjI2OjEzAAAHpAMAAwAAAAEAAAAAkgoABQAAAAEAAADrgpoABQAAAAE', NULL, NULL),
(5, 'test', 'desc', '012334', '555', '/9j/4QFwRXhpZgAATU0AKgAAAAgABwEAAAQAAAABAAAFoAEQAAIAAAAUAAAAYgEBAAQAAAABAAAHgAEPAAIAAAAHAAAAdodpAAQAAAABAAAAkQESAAMAAAABAAEAAAEyAAIAAAAUAAAAfQAAAABzZGtfZ3Bob25lNjRfeDg2XzY0AEdvb2dsZQAyMDIzOjA4OjA0IDExOjI2OjEzAAAHpAMAAwAAAAEAAAAAkgoABQAAAAEAAADrgpoABQAAAAE', '/9j/4QFwRXhpZgAATU0AKgAAAAgABwEAAAQAAAABAAAFoAEQAAIAAAAUAAAAYgEBAAQAAAABAAAHgAEPAAIAAAAHAAAAdodpAAQAAAABAAAAkQESAAMAAAABAAEAAAEyAAIAAAAUAAAAfQAAAABzZGtfZ3Bob25lNjRfeDg2XzY0AEdvb2dsZQAyMDIzOjA4OjA0IDExOjI2OjEzAAAHpAMAAwAAAAEAAAAAkgoABQAAAAEAAADrgpoABQAAAAE', '/9j/4QFwRXhpZgAATU0AKgAAAAgABwEAAAQAAAABAAAFoAEQAAIAAAAUAAAAYgEBAAQAAAABAAAHgAEPAAIAAAAHAAAAdodpAAQAAAABAAAAkQESAAMAAAABAAEAAAEyAAIAAAAUAAAAfQAAAABzZGtfZ3Bob25lNjRfeDg2XzY0AEdvb2dsZQAyMDIzOjA4OjA0IDExOjI2OjEzAAAHpAMAAwAAAAEAAAAAkgoABQAAAAEAAADrgpoABQAAAAE', NULL, NULL),
(6, 'tt', 'dd', '2222', '5555', '1691132008039-image0.jpg', '1691132008045-image1.jpg', '1691132008053-image2.jpg', NULL, NULL),
(7, 'dadad', 'adadad', '1441414141', '14141@dadad.com', '1691133324494-image0.jpg', '1691133324501-image1.jpg', '1691133324505-image2.jpg', NULL, NULL),
(8, 'tadad', 'wqqwqw', '123456789', 'desc@gmail.com', '1691133497216-image0.jpg', '1691133497224-image1.jpg', '1691133497231-image2.jpg', NULL, NULL),
(9, 'test', 'desc', '123456789', 'test@gmail.com', '1691134008054-image0.jpg', '1691134008058-image1.jpg', '1691134008083-image2.jpg', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `juristic_info`
--
ALTER TABLE `juristic_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phonebooks`
--
ALTER TABLE `phonebooks`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `qr_scanner`
--
ALTER TABLE `qr_scanner`
  ADD PRIMARY KEY (`qr_id`);

--
-- Indexes for table `suggestions`
--
ALTER TABLE `suggestions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `juristic_info`
--
ALTER TABLE `juristic_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `phonebooks`
--
ALTER TABLE `phonebooks`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `qr_scanner`
--
ALTER TABLE `qr_scanner`
  MODIFY `qr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `suggestions`
--
ALTER TABLE `suggestions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
