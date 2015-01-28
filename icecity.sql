-- phpMyAdmin SQL Dump
-- version 4.2.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-01-28 17:29:22
-- 服务器版本： 5.6.21
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `icecity`
--

-- --------------------------------------------------------

--
-- 表的结构 `apms_course`
--

CREATE TABLE IF NOT EXISTS `apms_course` (
`id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `starttime` varchar(6) NOT NULL COMMENT '上课时间，例如13:00',
  `scorerule` tinyint(3) NOT NULL DEFAULT '1' COMMENT '每迟到几分钟算一分，如果5分钟算一分则为5',
  `teacher` varchar(32) NOT NULL COMMENT '老师名称'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `apms_course`
--

INSERT INTO `apms_course` (`id`, `name`, `starttime`, `scorerule`, `teacher`) VALUES
(1, '柔性制造', '10:50', 1, '李中振'),
(2, '刚性制造', '10:00', 1, '李中振');

-- --------------------------------------------------------

--
-- 表的结构 `apms_group`
--

CREATE TABLE IF NOT EXISTS `apms_group` (
`id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `parentid` int(11) NOT NULL,
  `classid` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- 转存表中的数据 `apms_group`
--

INSERT INTO `apms_group` (`id`, `name`, `parentid`, `classid`) VALUES
(1, '第一大组1-1', 0, 1),
(2, '第二大组1-2', 0, 1),
(3, '第一小组-1-1-1', 1, 1),
(4, '第二小组-1-1-2', 1, 1),
(5, '第三小组-1-1-3', 1, 1),
(6, '第一小组1-2-1', 2, 1),
(7, '第二小组1-2-2', 2, 1),
(8, '第三小组1-2-3', 2, 1),
(9, '第一大组2-1', 0, 2),
(10, '第二大组2-2', 0, 2),
(11, '第一小组2-1-1', 9, 2),
(12, '第二小组2-1-2', 9, 2),
(13, '第三小组2-1-3', 9, 2),
(14, '第一小组2-2-1', 10, 2),
(15, '第二小组2-2-2', 10, 2),
(16, '第三小组2-2-3', 10, 2);

-- --------------------------------------------------------

--
-- 表的结构 `apms_login`
--

CREATE TABLE IF NOT EXISTS `apms_login` (
`id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `studentid` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `lastlogin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `apms_qiandao`
--

CREATE TABLE IF NOT EXISTS `apms_qiandao` (
`id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `studentid` varchar(32) NOT NULL,
  `courseid` int(11) NOT NULL,
  `pgid` int(11) NOT NULL,
  `sgid` int(11) NOT NULL,
  `date` varchar(16) NOT NULL COMMENT '签到日期，和下面的time重复，但是为了统计快',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mac` varchar(32) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `score` tinyint(3) NOT NULL DEFAULT '100'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apms_course`
--
ALTER TABLE `apms_course`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `apms_group`
--
ALTER TABLE `apms_group`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `apms_login`
--
ALTER TABLE `apms_login`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `apms_qiandao`
--
ALTER TABLE `apms_qiandao`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apms_course`
--
ALTER TABLE `apms_course`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `apms_group`
--
ALTER TABLE `apms_group`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `apms_login`
--
ALTER TABLE `apms_login`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `apms_qiandao`
--
ALTER TABLE `apms_qiandao`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
