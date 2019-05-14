-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 
-- サーバのバージョン： 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kumiton`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `t_member`
--

CREATE TABLE `t_member` (
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `name_kana` varchar(64) DEFAULT NULL,
  `nickname` varchar(64) DEFAULT NULL,
  `sex` smallint(6) NOT NULL,
  `level` decimal(3,1) NOT NULL,
  `join_cnt` int(11) NOT NULL DEFAULT '0',
  `last_join_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `t_member`
--

INSERT INTO `t_member` (`deleted`, `id`, `name`, `name_kana`, `nickname`, `sex`, `level`, `join_cnt`, `last_join_date`) VALUES
(0, 1, '北岡誠起', NULL, NULL, 1, '2.0', 0, NULL),
(0, 2, '吉岡みかこ', NULL, NULL, 2, '3.0', 0, NULL),
(0, 3, '平山智子', NULL, NULL, 2, '3.0', 0, NULL),
(0, 4, '藤井義博', NULL, NULL, 1, '3.0', 0, NULL),
(0, 5, '山本真稀', NULL, NULL, 2, '4.0', 0, NULL),
(0, 6, '明日香', NULL, NULL, 2, '4.0', 0, NULL),
(0, 7, '田頭あゆ', NULL, NULL, 2, '4.0', 0, NULL),
(0, 8, '吉崎広太', NULL, NULL, 1, '4.0', 0, NULL),
(0, 9, 'タケさん', NULL, NULL, 1, '4.0', 0, NULL),
(0, 10, '中下敬識', NULL, NULL, 1, '5.0', 0, NULL),
(0, 11, '雨林綾花', NULL, NULL, 2, '5.0', 0, NULL),
(0, 12, 'はるき', NULL, NULL, 2, '6.0', 0, NULL),
(0, 13, '加藤大', NULL, NULL, 2, '7.0', 0, NULL),
(0, 14, '仲田秀平', NULL, NULL, 2, '7.0', 0, NULL),
(0, 15, '大田先生', NULL, NULL, 2, '8.0', 0, NULL),
(0, 16, '山下もとき', NULL, NULL, 2, '8.0', 0, NULL),
(0, 17, '加藤裕喜', NULL, NULL, 2, '10.0', 0, NULL),
(0, 18, '上田さん', NULL, NULL, 2, '7.0', 0, NULL),
(0, 19, '中川剛志', NULL, NULL, 1, '8.0', 0, NULL),
(0, 20, '上田さん', NULL, NULL, 2, '7.0', 0, NULL),
(0, 21, '中川剛志', NULL, NULL, 1, '8.0', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_member`
--
ALTER TABLE `t_member`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_member`
--
ALTER TABLE `t_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
