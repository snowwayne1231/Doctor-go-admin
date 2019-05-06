-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- 主機: localhost
-- 產生時間： 2019 年 05 月 06 日 20:56
-- 伺服器版本: 10.1.32-MariaDB
-- PHP 版本： 7.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `psbeauty_app_mobile`
--

--
-- 資料表的匯出資料 `admin_menu`
--

INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`) VALUES
(1, 2, 2, '首頁', 'fa-bar-chart', '/', NULL, NULL, '2019-04-08 05:19:57'),
(2, 0, 1, 'Admin', 'fa-tasks', NULL, NULL, NULL, '2019-04-08 05:19:57'),
(3, 2, 3, 'Users', 'fa-users', 'auth/users', NULL, NULL, NULL),
(4, 2, 4, 'Roles', 'fa-user', 'auth/roles', NULL, NULL, NULL),
(5, 2, 5, 'Permission', 'fa-ban', 'auth/permissions', NULL, NULL, NULL),
(6, 2, 6, 'Menu', 'fa-bars', 'auth/menu', NULL, NULL, NULL),
(7, 2, 7, 'Operation log', 'fa-history', 'auth/logs', NULL, NULL, NULL),
(8, 0, 14, '會員', 'fa-user', '/maintainer', 'maintainer', '2019-03-11 18:20:21', '2019-04-09 22:51:13'),
(9, 8, 15, '會員資料', 'fa-users', '/maintainer/customers', NULL, '2019-03-11 19:54:13', '2019-04-09 22:51:13'),
(10, 11, 8, '簡訊王', 'fa-bookmark-o', '/sms', NULL, '2019-03-11 21:47:28', '2019-04-18 17:00:16'),
(11, 0, 25, '各項設置', 'fa-edit', '/maintainer', 'maintainer', '2019-03-16 00:08:21', '2019-04-13 18:03:48'),
(12, 11, 26, '註冊國家選單', 'fa-flag', '/maintainer/countries', NULL, '2019-03-16 00:09:46', '2019-04-13 18:04:01'),
(13, 0, 18, '商品', 'fa-shopping-cart', NULL, NULL, '2019-03-18 22:35:08', '2019-04-09 22:51:13'),
(14, 13, 19, '商品資料', 'fa-cart-plus', '/maintainer/products', NULL, '2019-03-18 22:36:59', '2019-04-09 22:51:13'),
(15, 13, 20, '商品分類', 'fa-object-group', '/maintainer/products_category', NULL, '2019-03-20 15:01:20', '2019-04-09 22:51:13'),
(16, 13, 21, '商品品牌', 'fa-briefcase', '/maintainer/products_brand', NULL, '2019-03-24 04:06:53', '2019-04-09 22:51:13'),
(19, 0, 9, '訂單', 'fa-ambulance', '/maintainer/payment', NULL, '2019-03-31 21:12:36', '2019-04-09 22:52:14'),
(20, 19, 10, '所有訂單', 'fa-align-left', '/maintainer/payment_order', NULL, '2019-03-31 21:15:15', '2019-04-09 22:51:13'),
(21, 0, 11, '廣告', 'fa-audio-description', NULL, NULL, '2019-04-08 05:09:29', '2019-04-09 22:51:13'),
(22, 21, 12, '首頁輪播', 'fa-cube', '/maintainer/ad_banner', NULL, '2019-04-08 05:12:28', '2019-04-09 22:51:13'),
(23, 21, 13, '新聞', 'fa-newspaper-o', '/maintainer/ad_news', NULL, '2019-04-08 18:46:20', '2019-04-09 22:51:13'),
(24, 0, 22, '美醫指南', 'fa-book', NULL, NULL, '2019-04-08 21:01:28', '2019-04-09 22:51:13'),
(25, 24, 23, '期刊', 'fa-black-tie', '/maintainer/article_magazine', NULL, '2019-04-08 21:06:02', '2019-04-09 22:51:13'),
(26, 24, 24, '文章', 'fa-bookmark-o', '/maintainer/article_chapter', NULL, '2019-04-09 00:15:12', '2019-04-09 22:51:13'),
(27, 21, 0, '點數中心輪播', 'fa-file-powerpoint-o', '/maintainer/ad_point_banner', NULL, '2019-04-10 04:58:43', '2019-04-10 04:58:43'),
(28, 21, 0, '活動', 'fa-calendar-check-o', '/maintainer/ad_event', NULL, '2019-04-11 19:12:24', '2019-04-11 19:12:24'),
(29, 11, 0, '贈送點數設定', 'fa-diamond', '/maintainer/setting_point_give/1/edit', NULL, '2019-04-13 18:05:20', '2019-04-16 03:36:31'),
(30, 11, 0, '結單點數抵扣設定', 'fa-shopping-basket', '/maintainer/setting_promotion', NULL, '2019-05-05 18:54:39', '2019-05-05 18:54:39');

--
-- 資料表的匯出資料 `admin_permissions`
--

INSERT INTO `admin_permissions` (`id`, `name`, `slug`, `http_method`, `http_path`, `created_at`, `updated_at`) VALUES
(1, 'All permission', '*', '', '*', NULL, NULL),
(2, 'Dashboard', 'dashboard', 'GET', '/', NULL, NULL),
(3, 'Login', 'auth.login', '', '/auth/login\r\n/auth/logout', NULL, NULL),
(4, 'User setting', 'auth.setting', 'GET,PUT', '/auth/setting', NULL, NULL),
(5, 'Auth management', 'auth.management', '', '/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs', NULL, NULL),
(6, '編輯', 'maintainer', '', '/maintainer*', '2019-03-11 20:28:49', '2019-03-11 20:29:41'),
(7, '上傳', 'upload', '', '/upload*', '2019-03-31 21:09:39', '2019-03-31 21:10:10');

--
-- 資料表的匯出資料 `admin_roles`
--

INSERT INTO `admin_roles` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'administrator', '2019-01-05 01:25:05', '2019-01-05 01:25:05'),
(2, '編輯者', 'maintainer', '2019-03-11 17:41:53', '2019-03-11 20:31:13');

--
-- 資料表的匯出資料 `admin_role_menu`
--

INSERT INTO `admin_role_menu` (`role_id`, `menu_id`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL),
(1, 1, NULL, NULL),
(2, 11, NULL, NULL);

--
-- 資料表的匯出資料 `admin_role_permissions`
--

INSERT INTO `admin_role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL),
(2, 6, NULL, NULL),
(2, 7, NULL, NULL),
(2, 2, NULL, NULL),
(2, 3, NULL, NULL),
(2, 5, NULL, NULL),
(2, 1, NULL, NULL);

--
-- 資料表的匯出資料 `admin_role_users`
--

INSERT INTO `admin_role_users` (`role_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL),
(2, 2, NULL, NULL),
(2, 3, NULL, NULL);

--
-- 資料表的匯出資料 `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `name`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$2m//5D.FkBkwwre9DdIRJunQGS6oGxFU9aEXmRuG0WU8fXpYdQwMK', 'Administrator', NULL, 'OV1McYUQp9ajxmopkTGx6UmZz2DUm5UkCkQUmxtS6brOnuUg7TImwto1b9Cu', '2019-01-05 01:25:05', '2019-01-05 01:25:05'),
(2, 'snow', '$2y$10$eXWJd6u5VP.azDdJ3fxHNOp.lQztU.7IYHwAZLXWD3Q3egkGQyXgq', 'snow', 'images/checkBox_yes.png', 't6RSMib9tIM9z6cv6QJnOF3praziW2tK3p4rtGm9kpWPWicjjsu8huJwE8w7', '2019-01-05 01:26:20', '2019-03-11 18:07:28'),
(3, 'psbeauty', '$2y$10$/g7.J/Y3iFXNqJb5milRledmuCOJbeae377jIOW4PbrHC4tZuxW1C', 'psbeauty', 'images/icon_myGoods@3x.png', NULL, '2019-03-11 20:22:29', '2019-03-11 20:22:29');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
