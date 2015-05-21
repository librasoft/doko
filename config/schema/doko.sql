-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mag 21, 2015 alle 17:45
-- Versione del server: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `newdoko`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `blocks`
--

DROP TABLE IF EXISTS `blocks`;
CREATE TABLE IF NOT EXISTS `blocks` (
  `id` int(10) unsigned NOT NULL,
  `status` tinyint(2) unsigned NOT NULL,
  `region_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `element` varchar(100) NOT NULL,
  `element_options` text NOT NULL,
  `css_class` varchar(255) NOT NULL,
  `show_title` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `visibility_roles` text NOT NULL,
  `visibility_paths` text NOT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rght` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `blocks_regions`
--

DROP TABLE IF EXISTS `blocks_regions`;
CREATE TABLE IF NOT EXISTS `blocks_regions` (
  `id` int(10) unsigned NOT NULL,
  `status` varchar(2) NOT NULL,
  `language` varchar(5) NOT NULL,
  `alias` varchar(20) NOT NULL,
  `visibility_roles` text NOT NULL,
  `visibility_paths` text NOT NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(10) unsigned NOT NULL,
  `status` tinyint(2) unsigned NOT NULL,
  `language` varchar(10) NOT NULL,
  `alias` varchar(20) NOT NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `menus_links`
--

DROP TABLE IF EXISTS `menus_links`;
CREATE TABLE IF NOT EXISTS `menus_links` (
  `id` int(10) unsigned NOT NULL,
  `status` tinyint(2) unsigned NOT NULL,
  `menu_id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `css_class` varchar(255) NOT NULL,
  `rel` varchar(50) NOT NULL,
  `target_blank` tinyint(1) NOT NULL,
  `element` varchar(255) NOT NULL,
  `element_options` text NOT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rght` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL,
  `status` tinyint(2) unsigned NOT NULL,
  `role` int(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `language` varchar(10) NOT NULL,
  `timezone` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `security_token` varchar(255) NOT NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `users_saved_logins`
--

DROP TABLE IF EXISTS `users_saved_logins`;
CREATE TABLE IF NOT EXISTS `users_saved_logins` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `token` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blocks`
--
ALTER TABLE `blocks`
  ADD PRIMARY KEY (`id`), ADD KEY `region_status_lft` (`region_id`,`status`,`lft`);

--
-- Indexes for table `blocks_regions`
--
ALTER TABLE `blocks_regions`
  ADD PRIMARY KEY (`id`), ADD KEY `lang_status` (`language`,`status`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`), ADD KEY `lang_status` (`language`,`status`);

--
-- Indexes for table `menus_links`
--
ALTER TABLE `menus_links`
  ADD PRIMARY KEY (`id`), ADD KEY `menu_status_lft` (`menu_id`,`status`,`lft`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_saved_logins`
--
ALTER TABLE `users_saved_logins`
  ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `modified` (`modified`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blocks`
--
ALTER TABLE `blocks`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `blocks_regions`
--
ALTER TABLE `blocks_regions`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `menus_links`
--
ALTER TABLE `menus_links`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users_saved_logins`
--
ALTER TABLE `users_saved_logins`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
