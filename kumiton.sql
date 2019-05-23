SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `kumiton` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `kumiton`;

DROP TABLE IF EXISTS `t_match_history`;
CREATE TABLE `t_match_history` (
  `id` int(11) NOT NULL,
  `server1` varchar(32) NOT NULL COMMENT 'サーバー一人目',
  `server2` varchar(32) NOT NULL COMMENT 'サーバー二人目',
  `receiver1` varchar(32) NOT NULL COMMENT 'レシーバー一人目',
  `receiver2` varchar(32) NOT NULL COMMENT 'レシーバー二人目',
  `play_flg` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:未プレイ、2:プレイ済'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='試合の組み合わせを格納';

DROP TABLE IF EXISTS `t_match_history`;
CREATE TABLE `t_match_history` (
  `id` int(11) NOT NULL,
  `server1` varchar(32) NOT NULL COMMENT 'サーバー一人目',
  `server2` varchar(32) NOT NULL COMMENT 'サーバー二人目',
  `receiver1` varchar(32) NOT NULL COMMENT 'レシーバー一人目',
  `receiver2` varchar(32) NOT NULL COMMENT 'レシーバー二人目'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='試合の組み合わせを格納';

INSERT INTO `t_match_history` (`id`, `server1`, `server2`, `receiver1`, `receiver2`) VALUES
(1, '雨林綾花', '西田真弓', '中下敬識', '中川剛志'),
(2, '田頭あゆ', 'タケさん', '藤井義博', '山下もとき'),
(3, 'はるき', '上田さん', '吉崎広太', '仲田秀平'),
(4, '北岡誠起', '加藤大', '平山智子', '大田先生'),
(5, '雨林綾花', '中川剛志', '中下敬識', '上田さん'),
(6, '吉岡みかこ', '大田先生', '平山智子', '山下もとき'),
(7, 'タケさん', 'はるき', '藤井義博', '仲田秀平'),
(8, '明日香', '田頭あゆ', '北岡誠起', '西田真弓'),
(9, '藤井義博', '明日香', '平山智子', '中下敬識'),
(10, '山本真稀', '大田先生', '田頭あゆ', '加藤大'),
(11, '吉崎広太', 'はるき', '北岡誠起', '山下もとき'),
(12, '雨林綾花', '中川剛志', 'タケさん', '西田真弓'),
(13, '雨林綾花', '中川剛志', '田頭あゆ', '大田先生'),
(14, '明日香', '山下もとき', '山本真稀', '仲田秀平'),
(15, '吉崎広太', '中下敬識', '吉岡みかこ', '上田さん'),
(16, '北岡誠起', '西田真弓', '藤井義博', '加藤大');

DROP TABLE IF EXISTS `t_member`;
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
(0, 21, '中川剛志', NULL, NULL, 1, '8.0', 0, NULL),
(0, 22, '西田真弓', NULL, NULL, 2, '7.0', 0, NULL),
(0, 23, '西田真弓', NULL, NULL, 2, '7.0', 0, NULL),
(0, 24, '西田真弓', NULL, NULL, 2, '7.0', 0, NULL);


ALTER TABLE `t_match_history`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `t_match_history`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `t_member`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`(191));


ALTER TABLE `t_match_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `t_match_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

ALTER TABLE `t_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
