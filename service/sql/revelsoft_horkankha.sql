-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 15, 2018 at 05:35 AM
-- Server version: 5.7.17-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `revelsoft_horkankha`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_event`
--

CREATE TABLE `tb_event` (
  `event_id` int(11) NOT NULL,
  `event_title` varchar(500) DEFAULT NULL,
  `event_description` varchar(500) DEFAULT NULL,
  `event_detail` varchar(500) DEFAULT NULL,
  `event_date` datetime DEFAULT NULL,
  `event_location` varchar(200) DEFAULT NULL,
  `event_show` int(11) DEFAULT NULL,
  `event_img_1` varchar(100) DEFAULT NULL,
  `event_img_2` varchar(100) DEFAULT NULL,
  `event_img_3` varchar(100) DEFAULT NULL,
  `event_img_4` varchar(100) DEFAULT NULL,
  `add_by` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `edit_by` int(11) DEFAULT NULL,
  `date_edit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_exprie`
--

CREATE TABLE `tb_exprie` (
  `exprie_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `date_begin` datetime DEFAULT NULL,
  `date_exprie` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_exprie`
--

INSERT INTO `tb_exprie` (`exprie_id`, `member_id`, `date_begin`, `date_exprie`) VALUES
(9, 12, '2017-02-15 10:14:57', '2018-02-15 10:14:57'),
(10, 13, '2017-02-15 10:14:57', '2018-02-15 10:14:57'),
(11, 14, '2018-02-15 10:14:57', '2019-02-15 10:14:57'),
(12, 15, '2017-02-15 10:14:57', '2018-02-15 10:14:57'),
(13, 16, '2018-02-15 10:14:57', '2019-02-15 10:14:57'),
(14, 13, '2018-02-15 10:14:57', '2019-02-15 10:14:57'),
(15, 15, '2018-02-15 10:14:57', '2019-02-15 10:14:57'),
(16, 12, '2018-02-15 10:14:57', '2019-02-15 10:14:57');

-- --------------------------------------------------------

--
-- Table structure for table `tb_member`
--

CREATE TABLE `tb_member` (
  `member_id` int(11) NOT NULL,
  `member_type_id` int(11) NOT NULL,
  `member_code` varchar(45) DEFAULT NULL,
  `member_name` varchar(45) DEFAULT NULL,
  `member_firstname` varchar(100) NOT NULL,
  `member_lastname` varchar(100) NOT NULL,
  `member_image` varchar(100) NOT NULL,
  `member_phone` varchar(45) NOT NULL,
  `member_email` varchar(200) NOT NULL,
  `member_facebook` varchar(200) DEFAULT NULL,
  `member_line_id` varchar(200) DEFAULT NULL,
  `member_address` varchar(500) DEFAULT NULL,
  `DISTRICT_ID` int(11) DEFAULT NULL,
  `member_username` varchar(100) DEFAULT NULL,
  `member_password` varchar(100) DEFAULT NULL,
  `member_description` varchar(300) DEFAULT NULL,
  `exprie_id` int(11) NOT NULL,
  `add_by` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `edit_by` int(11) DEFAULT NULL,
  `date_edit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_member`
--

INSERT INTO `tb_member` (`member_id`, `member_type_id`, `member_code`, `member_name`, `member_firstname`, `member_lastname`, `member_image`, `member_phone`, `member_email`, `member_facebook`, `member_line_id`, `member_address`, `DISTRICT_ID`, `member_username`, `member_password`, `member_description`, `exprie_id`, `add_by`, `date_add`, `edit_by`, `date_edit`) VALUES
(12, 1, '0001', 'mick', 'micky', 'noue', '', '0812354678', '', NULL, NULL, NULL, NULL, 'mick', '123456', NULL, 16, NULL, NULL, NULL, NULL),
(13, 1, '0002', 'rick', 'ricky', 'noue', '', '0812874678', '', NULL, NULL, NULL, NULL, 'rick', '123456', NULL, 14, NULL, NULL, NULL, NULL),
(14, 1, '0003', 'micle', 'micle', 'swift', '', '0812874678', '', NULL, NULL, NULL, NULL, 'micle', '123456', NULL, 11, NULL, NULL, NULL, NULL),
(15, 1, '0004', 'micael', 'micael', 'blood', '', '0887974678', '', NULL, NULL, NULL, NULL, 'micael', '123456', NULL, 15, NULL, NULL, NULL, NULL),
(16, 2, '0005', 'cloud', 'cloud', 'nine', '', '0812833578', '', NULL, NULL, NULL, NULL, 'cloud', '123456', NULL, 13, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_member_type`
--

CREATE TABLE `tb_member_type` (
  `member_type_id` int(11) NOT NULL,
  `member_type_name` varchar(45) DEFAULT NULL,
  `add_by` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `edit_by` int(11) DEFAULT NULL,
  `date_edit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_member_type`
--

INSERT INTO `tb_member_type` (`member_type_id`, `member_type_name`, `add_by`, `date_add`, `edit_by`, `date_edit`) VALUES
(1, 'สมาชิกทั่วไป', NULL, NULL, NULL, NULL),
(2, 'ผู้ประกอบการ', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_news`
--

CREATE TABLE `tb_news` (
  `news_id` int(11) NOT NULL,
  `news_cat_id` int(11) NOT NULL,
  `news_title` varchar(500) DEFAULT NULL,
  `news_description` varchar(500) DEFAULT NULL,
  `news_detail` varchar(500) DEFAULT NULL,
  `news_show` int(11) DEFAULT NULL,
  `news_img_1` varchar(100) DEFAULT NULL,
  `news_img_2` varchar(100) DEFAULT NULL,
  `news_img_3` varchar(100) DEFAULT NULL,
  `news_img_4` varchar(100) DEFAULT NULL,
  `add_by` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `edit_by` int(11) DEFAULT NULL,
  `date_edit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_news_category`
--

CREATE TABLE `tb_news_category` (
  `news_cat_id` int(11) NOT NULL,
  `news_cat_name` varchar(100) DEFAULT NULL,
  `news_cat_show` int(11) DEFAULT NULL,
  `add_by` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `edit_by` int(11) DEFAULT NULL,
  `date_edit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_news_category`
--

INSERT INTO `tb_news_category` (`news_cat_id`, `news_cat_name`, `news_cat_show`, `add_by`, `date_add`, `edit_by`, `date_edit`) VALUES
(1, 'ข่าว', 1, NULL, NULL, NULL, NULL),
(2, 'กิจกรรม', 1, NULL, NULL, NULL, NULL),
(3, 'สินค้าใหม่', 1, NULL, NULL, NULL, NULL),
(4, 'อัพเดตซอฟต์เเวร์', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_promotion`
--

CREATE TABLE `tb_promotion` (
  `promotion_id` int(11) NOT NULL,
  `promotion_name` varchar(100) DEFAULT NULL,
  `promotion_detail` varchar(500) DEFAULT NULL,
  `promotion_limit` int(11) DEFAULT NULL,
  `promotion_begin` datetime DEFAULT NULL,
  `promotion_end` datetime DEFAULT NULL,
  `promotion_qr_code` varchar(100) DEFAULT NULL,
  `promotion_show` int(11) DEFAULT NULL,
  `promotion_img` varchar(100) DEFAULT NULL,
  `add_by` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `edit_by` int(11) DEFAULT NULL,
  `date_edit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_shop`
--

CREATE TABLE `tb_shop` (
  `shop_id` int(11) NOT NULL,
  `shop_cat_id` int(11) NOT NULL,
  `shop_name` varchar(100) DEFAULT NULL,
  `shop_detail` varchar(200) DEFAULT NULL,
  `shop_show` int(11) DEFAULT NULL,
  `shop_facebook` varchar(100) DEFAULT NULL,
  `shop_line` varchar(100) DEFAULT NULL,
  `shop_email` varchar(100) DEFAULT NULL,
  `shop_phone` varchar(45) DEFAULT NULL,
  `shop_img` varchar(100) DEFAULT NULL,
  `shop_adress` varchar(300) DEFAULT NULL,
  `DISTRICT_ID` int(11) DEFAULT NULL,
  `shop_location_lat` varchar(100) DEFAULT NULL,
  `shop_location_long` varchar(100) DEFAULT NULL,
  `add_by` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `edit_by` int(11) DEFAULT NULL,
  `date_edit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_shop_category`
--

CREATE TABLE `tb_shop_category` (
  `shop_cat_id` int(11) NOT NULL,
  `shop_cat_name` varchar(100) DEFAULT NULL,
  `shop_cat_show` int(11) DEFAULT NULL,
  `add by` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `edit_by` int(11) DEFAULT NULL,
  `date_edit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_shop_category`
--

INSERT INTO `tb_shop_category` (`shop_cat_id`, `shop_cat_name`, `shop_cat_show`, `add by`, `date_add`, `edit_by`, `date_edit`) VALUES
(1, 'Restaurants', 1, NULL, NULL, NULL, NULL),
(2, 'Hotel', 1, NULL, NULL, NULL, NULL),
(3, 'Nightlife', 1, NULL, NULL, NULL, NULL),
(4, 'Shopping', 1, NULL, NULL, NULL, NULL),
(5, 'Culture', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_shop_promotion`
--

CREATE TABLE `tb_shop_promotion` (
  `shop_promotion_shop_id` int(11) NOT NULL,
  `shop_promotion_promotion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_suggestion`
--

CREATE TABLE `tb_suggestion` (
  `suggestion_id` int(11) NOT NULL,
  `suggestion_type_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `suggestion_title` varchar(200) DEFAULT NULL,
  `suggestion_detail` varchar(600) DEFAULT NULL,
  `suggestion_read` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_suggestion_type`
--

CREATE TABLE `tb_suggestion_type` (
  `suggestion_type_id` int(11) NOT NULL,
  `suggestion_type_name` varchar(100) DEFAULT NULL,
  `suggestion_type_show` int(11) DEFAULT NULL,
  `add_by` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `edit_by` int(11) DEFAULT NULL,
  `date_edit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_suggestion_type`
--

INSERT INTO `tb_suggestion_type` (`suggestion_type_id`, `suggestion_type_name`, `suggestion_type_show`, `add_by`, `date_add`, `edit_by`, `date_edit`) VALUES
(1, 'ความคิดเห็น', 1, NULL, NULL, NULL, NULL),
(2, 'ข้อเสนอเเนะ', 1, NULL, NULL, NULL, NULL),
(3, 'คำถาม', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `user_id` int(11) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `user_code` varchar(45) NOT NULL,
  `user_name` varchar(45) NOT NULL,
  `user_firstname` varchar(100) NOT NULL,
  `user_lastname` varchar(100) NOT NULL,
  `user_image` varchar(100) DEFAULT NULL,
  `user_phone` varchar(45) NOT NULL,
  `user_email` varchar(200) NOT NULL,
  `user_facebook` varchar(200) DEFAULT NULL,
  `user_line_id` varchar(200) DEFAULT NULL,
  `user_address` varchar(500) DEFAULT NULL,
  `DISTRICT_ID` int(11) DEFAULT NULL,
  `user_username` varchar(100) DEFAULT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `user_description` varchar(300) DEFAULT NULL,
  `add_by` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `edit_by` int(11) DEFAULT NULL,
  `date_edit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`user_id`, `user_type_id`, `user_code`, `user_name`, `user_firstname`, `user_lastname`, `user_image`, `user_phone`, `user_email`, `user_facebook`, `user_line_id`, `user_address`, `DISTRICT_ID`, `user_username`, `user_password`, `user_description`, `add_by`, `date_add`, `edit_by`, `date_edit`) VALUES
(1, 1, 'A0001', 'admin', 'admin', '', NULL, '0887654321', '', NULL, NULL, NULL, NULL, 'admin', '123456', NULL, NULL, NULL, NULL, '2018-02-15 11:46:59');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user_type`
--

CREATE TABLE `tb_user_type` (
  `user_type_id` int(11) NOT NULL,
  `user_type_name` varchar(45) DEFAULT NULL,
  `add_by` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `edit_by` int(11) DEFAULT NULL,
  `date_edit` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_user_type`
--

INSERT INTO `tb_user_type` (`user_type_id`, `user_type_name`, `add_by`, `date_add`, `edit_by`, `date_edit`) VALUES
(1, 'Admin', NULL, NULL, NULL, NULL),
(2, 'User', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_use_promotion`
--

CREATE TABLE `tb_use_promotion` (
  `use_promotion_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `promotion_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `date_use` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_event`
--
ALTER TABLE `tb_event`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `tb_exprie`
--
ALTER TABLE `tb_exprie`
  ADD PRIMARY KEY (`exprie_id`,`member_id`),
  ADD KEY `exprie_idx` (`member_id`);

--
-- Indexes for table `tb_member`
--
ALTER TABLE `tb_member`
  ADD PRIMARY KEY (`member_id`,`member_type_id`),
  ADD KEY `type_idx` (`member_type_id`),
  ADD KEY `exprie` (`exprie_id`);

--
-- Indexes for table `tb_member_type`
--
ALTER TABLE `tb_member_type`
  ADD PRIMARY KEY (`member_type_id`);

--
-- Indexes for table `tb_news`
--
ALTER TABLE `tb_news`
  ADD PRIMARY KEY (`news_id`,`news_cat_id`),
  ADD KEY `news_category_idx` (`news_cat_id`);

--
-- Indexes for table `tb_news_category`
--
ALTER TABLE `tb_news_category`
  ADD PRIMARY KEY (`news_cat_id`);

--
-- Indexes for table `tb_promotion`
--
ALTER TABLE `tb_promotion`
  ADD PRIMARY KEY (`promotion_id`);

--
-- Indexes for table `tb_shop`
--
ALTER TABLE `tb_shop`
  ADD PRIMARY KEY (`shop_id`,`shop_cat_id`),
  ADD KEY `shop_category_idx` (`shop_cat_id`);

--
-- Indexes for table `tb_shop_category`
--
ALTER TABLE `tb_shop_category`
  ADD PRIMARY KEY (`shop_cat_id`);

--
-- Indexes for table `tb_shop_promotion`
--
ALTER TABLE `tb_shop_promotion`
  ADD PRIMARY KEY (`shop_promotion_shop_id`,`shop_promotion_promotion_id`),
  ADD KEY `shop_promotion_promotion_idx` (`shop_promotion_promotion_id`);

--
-- Indexes for table `tb_suggestion`
--
ALTER TABLE `tb_suggestion`
  ADD PRIMARY KEY (`suggestion_id`,`suggestion_type_id`,`member_id`),
  ADD KEY `suggestion_type_idx` (`suggestion_type_id`),
  ADD KEY `member_id_idx` (`member_id`);

--
-- Indexes for table `tb_suggestion_type`
--
ALTER TABLE `tb_suggestion_type`
  ADD PRIMARY KEY (`suggestion_type_id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`user_id`,`user_type_id`),
  ADD KEY `user_type_idx` (`user_type_id`);

--
-- Indexes for table `tb_user_type`
--
ALTER TABLE `tb_user_type`
  ADD PRIMARY KEY (`user_type_id`);

--
-- Indexes for table `tb_use_promotion`
--
ALTER TABLE `tb_use_promotion`
  ADD PRIMARY KEY (`use_promotion_id`,`shop_id`,`promotion_id`,`member_id`),
  ADD KEY `promotion_idx` (`promotion_id`),
  ADD KEY `shop_idx` (`shop_id`),
  ADD KEY `member_idx` (`member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_event`
--
ALTER TABLE `tb_event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_exprie`
--
ALTER TABLE `tb_exprie`
  MODIFY `exprie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `tb_member`
--
ALTER TABLE `tb_member`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `tb_member_type`
--
ALTER TABLE `tb_member_type`
  MODIFY `member_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tb_news`
--
ALTER TABLE `tb_news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_news_category`
--
ALTER TABLE `tb_news_category`
  MODIFY `news_cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tb_promotion`
--
ALTER TABLE `tb_promotion`
  MODIFY `promotion_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_shop`
--
ALTER TABLE `tb_shop`
  MODIFY `shop_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_shop_category`
--
ALTER TABLE `tb_shop_category`
  MODIFY `shop_cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tb_suggestion`
--
ALTER TABLE `tb_suggestion`
  MODIFY `suggestion_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_suggestion_type`
--
ALTER TABLE `tb_suggestion_type`
  MODIFY `suggestion_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tb_user_type`
--
ALTER TABLE `tb_user_type`
  MODIFY `user_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tb_use_promotion`
--
ALTER TABLE `tb_use_promotion`
  MODIFY `use_promotion_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_exprie`
--
ALTER TABLE `tb_exprie`
  ADD CONSTRAINT `exprie_date` FOREIGN KEY (`member_id`) REFERENCES `tb_member` (`member_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_member`
--
ALTER TABLE `tb_member`
  ADD CONSTRAINT `exprie` FOREIGN KEY (`exprie_id`) REFERENCES `tb_exprie` (`exprie_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `member_type` FOREIGN KEY (`member_type_id`) REFERENCES `tb_member_type` (`member_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_news`
--
ALTER TABLE `tb_news`
  ADD CONSTRAINT `news_category` FOREIGN KEY (`news_cat_id`) REFERENCES `tb_news_category` (`news_cat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_shop`
--
ALTER TABLE `tb_shop`
  ADD CONSTRAINT `shop_category` FOREIGN KEY (`shop_cat_id`) REFERENCES `tb_shop_category` (`shop_cat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_shop_promotion`
--
ALTER TABLE `tb_shop_promotion`
  ADD CONSTRAINT `shop_promotion_promotion` FOREIGN KEY (`shop_promotion_promotion_id`) REFERENCES `tb_promotion` (`promotion_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `shop_promotion_shop` FOREIGN KEY (`shop_promotion_shop_id`) REFERENCES `tb_shop` (`shop_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_suggestion`
--
ALTER TABLE `tb_suggestion`
  ADD CONSTRAINT `member_id` FOREIGN KEY (`member_id`) REFERENCES `tb_member` (`member_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `suggestion_type` FOREIGN KEY (`suggestion_type_id`) REFERENCES `tb_suggestion_type` (`suggestion_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD CONSTRAINT `user_type` FOREIGN KEY (`user_type_id`) REFERENCES `tb_user_type` (`user_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tb_use_promotion`
--
ALTER TABLE `tb_use_promotion`
  ADD CONSTRAINT `member` FOREIGN KEY (`member_id`) REFERENCES `tb_member` (`member_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `promotion` FOREIGN KEY (`promotion_id`) REFERENCES `tb_promotion` (`promotion_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `shop` FOREIGN KEY (`shop_id`) REFERENCES `tb_shop` (`shop_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
