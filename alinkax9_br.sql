-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июн 16 2025 г., 21:24
-- Версия сервера: 8.0.34-26-beget-1-1
-- Версия PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `alinkax9_br`
--

-- --------------------------------------------------------

--
-- Структура таблицы `CartItems`
--
-- Создание: Май 20 2025 г., 18:09
-- Последнее обновление: Июн 13 2025 г., 15:31
--

DROP TABLE IF EXISTS `CartItems`;
CREATE TABLE `CartItems` (
  `cart_item_id` int UNSIGNED NOT NULL,
  `cart_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `CartItems`
--

INSERT INTO `CartItems` (`cart_item_id`, `cart_id`, `product_id`, `quantity`) VALUES
(54, 34, 65, 2),
(55, 35, 38, 1),
(56, 35, 22, 1),
(58, 36, 2, 4),
(59, 36, 1, 4),
(60, 36, 3, 4),
(61, 36, 6, 2),
(62, 36, 24, 1),
(63, 36, 5, 1),
(64, 37, 5, 1),
(65, 37, 6, 1),
(66, 37, 4, 1),
(67, 37, 1, 1),
(68, 37, 2, 1),
(70, 39, 1, 1),
(72, 41, 2, 1),
(73, 41, 24, 1),
(74, 42, 25, 1),
(75, 43, 1, 7),
(76, 43, 2, 10),
(77, 43, 25, 1),
(78, 43, 5, 2),
(79, 43, 3, 5),
(80, 43, 24, 2),
(81, 44, 9, 1),
(82, 44, 24, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `Carts`
--
-- Создание: Апр 24 2025 г., 13:25
-- Последнее обновление: Июн 13 2025 г., 15:31
--

DROP TABLE IF EXISTS `Carts`;
CREATE TABLE `Carts` (
  `cart_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `Carts`
--

INSERT INTO `Carts` (`cart_id`, `user_id`, `created_at`, `is_active`) VALUES
(34, 791, '2025-05-20 19:42:20', 0),
(35, 791, '2025-05-21 20:14:20', 0),
(36, 791, '2025-05-22 16:17:12', 0),
(37, 791, '2025-05-24 10:11:25', 0),
(38, 791, '2025-05-24 10:11:43', 0),
(39, 791, '2025-05-25 09:56:05', 0),
(40, 791, '2025-06-07 16:00:01', 0),
(41, 791, '2025-06-07 16:00:17', 0),
(42, 791, '2025-06-07 17:28:09', 0),
(43, 791, '2025-06-12 12:16:27', 0),
(44, 791, '2025-06-13 15:30:52', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `Categories`
--
-- Создание: Апр 24 2025 г., 13:25
--

DROP TABLE IF EXISTS `Categories`;
CREATE TABLE `Categories` (
  `category_id` int UNSIGNED NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `parent_category_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `Categories`
--

INSERT INTO `Categories` (`category_id`, `category_name`, `description`, `parent_category_id`) VALUES
(11, 'Вагонка', '', NULL),
(12, 'Блок Хаус', '', NULL),
(13, 'Имитация бруса', '', NULL),
(14, 'Планкен', '', NULL),
(15, 'Брус', '', NULL),
(16, 'Брусок', '', NULL),
(17, 'Строганная доска', '', NULL),
(18, 'Половая доска', '', NULL),
(19, 'ДВП', '', NULL),
(20, 'ОСБ', '', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `Galleries`
--
-- Создание: Апр 24 2025 г., 13:25
-- Последнее обновление: Июн 12 2025 г., 12:43
--

DROP TABLE IF EXISTS `Galleries`;
CREATE TABLE `Galleries` (
  `gallery_id` int UNSIGNED NOT NULL,
  `image_1` varchar(255) NOT NULL,
  `image_2` varchar(255) DEFAULT NULL,
  `image_3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `Galleries`
--

INSERT INTO `Galleries` (`gallery_id`, `image_1`, `image_2`, `image_3`) VALUES
(1, 'product/vagonka_sosna_1.jpg', 'product/vagonka_sosna_2.jpg', 'product/vagonka_sosna_3.jpg'),
(2, 'product/681b93512d81c.webp', 'product/blokhaus_larch_2.jpg', NULL),
(3, 'product/imitation_pine_1.jpg', NULL, NULL),
(4, 'product/planken_oak_1.jpg', 'product/planken_oak_2.jpg', NULL),
(5, 'product/brus_pine_1.jpg', NULL, NULL),
(6, 'product/osb_1.jpg', 'product/osb_2.jpg', NULL),
(7, 'product/681b94218e6bb.jpg', 'product/vagonka_sosna_5.jpg', NULL),
(8, 'product/681b94276abd9.jpg', 'product/vagonka_lipa_2.jpg', NULL),
(9, 'product/681b942d1b735.jpg', NULL, NULL),
(10, 'product/681b94314f2ce.jpg', 'product/vagonka_kedr_2.jpg', 'product/vagonka_kedr_3.jpg'),
(11, 'product/681b9437ec04d.jpg', NULL, NULL),
(12, 'product/681b943d84de5.jpg', NULL, NULL),
(13, 'product/681b944211c3b.jpg', 'product/vagonka_el_3.jpg', NULL),
(14, 'product/681b944772d84.jpg', NULL, NULL),
(15, 'product/681b944b80167.jpg', 'product/vagonka_sosna_8.jpg', NULL),
(16, 'product/681b94522a3fa.jpg', NULL, NULL),
(17, 'product/681b94562e0bb.jpg', NULL, NULL),
(18, 'product/681b945d95508.jpg', 'product/vagonka_kedr_6.jpg', NULL),
(19, 'product/681b94621d90b.jpg', NULL, NULL),
(20, 'product/681b94696e5c4.jpg', NULL, NULL),
(21, 'product/vagonka_el_5.jpg', 'product/vagonka_el_6.jpg', NULL),
(22, 'product/681b939788f63.webp', 'product/blokhaus_sosna_2.jpg', NULL),
(24, 'product/681b8c6831438.jpg', 'product/681b8c6831549.jpg', NULL),
(25, '', NULL, NULL),
(26, 'product/681b938e8f4f8.jpg', 'product/blokhaus_sosna_4.jpg', NULL),
(27, 'product/681b94d4c507d.jpg', NULL, NULL),
(28, 'product/681b94da0afa2.jpg', 'product/imitation_el_2.jpg', NULL),
(29, 'product/681b94de75817.jpg', NULL, NULL),
(30, 'product/681b94e5954e6.jpg', 'product/imitation_lipa_2.jpg', NULL),
(31, 'product/681b94f17844c.jpg', NULL, NULL),
(32, 'product/681b95355a859.jpg', 'product/planken_sosna_2.jpg', NULL),
(33, 'product/681b953ad6f5d.jpg', NULL, NULL),
(34, 'product/681b953f221c6.jpg', 'product/planken_kedr_2.jpg', NULL),
(35, 'product/681b9543b98c5.jpg', NULL, NULL),
(36, 'product/681b954822c70.jpg', NULL, NULL),
(37, 'product/681b95613b95f.jpg', NULL, NULL),
(38, 'product/681b93a43b9ef.webp', 'product/brus_el_2.jpg', NULL),
(39, 'product/681b93a84baf7.webp', NULL, NULL),
(40, 'product/brus_lipa_1.jpg', NULL, NULL),
(41, 'product/681b939fe6c57.webp', 'product/brus_dub_2.jpg', NULL),
(42, 'product/681b957aeb5f4.jpg', NULL, NULL),
(43, 'product/brusok_el_1.jpg', NULL, NULL),
(44, 'product/681b957f90ba3.jpg', 'product/brusok_kedr_2.jpg', NULL),
(45, 'product/681b95854ca99.jpg', NULL, NULL),
(47, 'product/681b95a00c58e.png', 'product/stroganka_sosna_2.jpg', NULL),
(48, 'product/681b95a475548.png', NULL, NULL),
(49, 'product/681b95a8b7903.png', NULL, NULL),
(50, 'product/681b95ad02389.png', 'product/stroganka_lipa_2.jpg', NULL),
(51, 'product/681b95b1a4628.png', NULL, NULL),
(52, 'product/681b95c7ae943.png', NULL, NULL),
(53, 'product/681b95cd340bf.png', 'product/polovaya_el_2.jpg', NULL),
(54, 'product/681b95d29ed00.png', NULL, NULL),
(55, 'product/681b95d6b9cdf.png', NULL, NULL),
(56, 'product/681b95ddd1070.png', 'product/polovaya_dub_2.jpg', NULL),
(57, 'product/681b95f9cb9b5.webp', NULL, NULL),
(58, 'product/681b95ffa2867.webp', NULL, NULL),
(59, 'product/681b9604234be.webp', NULL, NULL),
(60, 'product/681b8f1fa268b.png', NULL, NULL),
(61, 'product/dvp_5.jpg', NULL, NULL),
(62, 'product/681b8e939f701.webp', NULL, NULL),
(63, 'product/681b8e83cb5da.webp', NULL, NULL),
(64, 'product/681b8e6a17fb5.webp', NULL, NULL),
(65, 'product/681b8e5939ab5.webp', NULL, NULL),
(68, 'product/default.jpg', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `Inventory`
--
-- Создание: Май 20 2025 г., 18:09
-- Последнее обновление: Июн 12 2025 г., 12:43
--

DROP TABLE IF EXISTS `Inventory`;
CREATE TABLE `Inventory` (
  `inventory_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `minimum_stock` int NOT NULL DEFAULT '5',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `Inventory`
--

INSERT INTO `Inventory` (`inventory_id`, `product_id`, `quantity`, `minimum_stock`) VALUES
(1, 1, 120, 10),
(2, 2, 85, 5),
(3, 3, 95, 5),
(4, 4, 40, 3),
(5, 5, 110, 8),
(6, 6, 200, 15),
(7, 7, 150, 12),
(8, 8, 85, 7),
(9, 9, 120, 10),
(10, 10, 45, 4),
(11, 11, 90, 8),
(12, 12, 60, 5),
(13, 13, 110, 9),
(14, 14, 35, 3),
(15, 15, 75, 6),
(16, 16, 50, 5),
(17, 17, 95, 8),
(18, 18, 40, 4),
(19, 19, 105, 9),
(20, 20, 55, 5),
(21, 21, 80, 7),
(22, 22, 75, 8),
(24, 24, 35, 4),
(25, 25, 45, 5),
(26, 26, 90, 9),
(27, 27, 110, 11),
(28, 28, 95, 10),
(29, 29, 50, 6),
(30, 30, 65, 7),
(31, 31, 85, 9),
(32, 32, 70, 8),
(33, 33, 60, 7),
(34, 34, 30, 4),
(35, 35, 45, 5),
(36, 36, 25, 3),
(37, 37, 100, 10),
(38, 38, 90, 9),
(39, 39, 40, 5),
(40, 40, 55, 6),
(41, 41, 30, 4),
(42, 42, 150, 15),
(43, 43, 140, 14),
(44, 44, 80, 9),
(45, 45, 100, 11),
(47, 47, 120, 12),
(48, 48, 110, 11),
(49, 49, 50, 6),
(50, 50, 70, 8),
(51, 51, 40, 5),
(52, 52, 90, 10),
(53, 53, 80, 9),
(54, 54, 45, 6),
(55, 55, 60, 7),
(56, 56, 35, 5),
(57, 57, 200, 20),
(58, 58, 180, 18),
(59, 59, 150, 15),
(60, 60, 220, 22),
(61, 61, 100, 11),
(62, 62, 150, 15),
(63, 63, 120, 12),
(64, 64, 90, 10),
(65, 65, 110, 12);

-- --------------------------------------------------------

--
-- Структура таблицы `Manufacturers`
--
-- Создание: Апр 24 2025 г., 13:25
--

DROP TABLE IF EXISTS `Manufacturers`;
CREATE TABLE `Manufacturers` (
  `manufacturer_id` int UNSIGNED NOT NULL,
  `manufacturer_name` varchar(255) NOT NULL,
  `country` varchar(100) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `Manufacturers`
--

INSERT INTO `Manufacturers` (`manufacturer_id`, `manufacturer_name`, `country`, `description`) VALUES
(11, 'ЛесПром', 'Россия', 'Крупный производитель пиломатериалов'),
(12, 'WoodArt', 'Беларусь', 'Производитель вагонки и фанеры'),
(13, 'NordTimber', 'Россия', 'Производит доску пола, планкен и ОСБ'),
(14, 'WoodMaster', 'Россия', 'Производитель высококачественных пиломатериалов'),
(15, 'ForestGoods', 'Беларусь', 'Экологически чистые материалы'),
(16, 'TimberLine', 'Россия', 'Специализируется на хвойных породах'),
(17, 'OakCraft', 'Россия', 'Производитель изделий из дуба'),
(18, 'PineWorks', 'Беларусь', 'Сосновые пиломатериалы');

-- --------------------------------------------------------

--
-- Структура таблицы `OrderItems`
--
-- Создание: Май 21 2025 г., 20:18
-- Последнее обновление: Июн 13 2025 г., 15:33
--

DROP TABLE IF EXISTS `OrderItems`;
CREATE TABLE `OrderItems` (
  `order_item_id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `quantity` int NOT NULL,
  `price_per_unit` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `OrderItems`
--

INSERT INTO `OrderItems` (`order_item_id`, `order_id`, `product_id`, `product_name`, `product_price`, `quantity`, `price_per_unit`) VALUES
(1, 29, 65, NULL, NULL, 2, '700.00'),
(5, 33, 2, NULL, NULL, 4, '1200.00'),
(6, 33, 1, NULL, NULL, 4, '450.00'),
(7, 33, 3, NULL, NULL, 4, '780.00'),
(8, 33, 6, NULL, NULL, 2, '650.00'),
(9, 33, 24, NULL, NULL, 1, '1800.00'),
(10, 33, 5, NULL, NULL, 1, '950.00'),
(11, 34, 5, NULL, NULL, 1, '950.00'),
(12, 34, 6, NULL, NULL, 1, '650.00'),
(13, 34, 4, NULL, NULL, 1, '2500.00'),
(14, 34, 1, NULL, NULL, 1, '450.00'),
(15, 34, 2, NULL, NULL, 1, '1200.00'),
(17, 36, 1, NULL, NULL, 1, '450.00'),
(19, 38, 2, NULL, NULL, 1, '1200.00'),
(20, 38, 24, NULL, NULL, 1, '1800.00'),
(21, 40, 25, NULL, NULL, 1, '1300.00'),
(22, 41, 1, NULL, NULL, 7, '450.00'),
(23, 41, 2, NULL, NULL, 10, '1200.00'),
(24, 41, 25, NULL, NULL, 1, '1300.00'),
(25, 41, 5, NULL, NULL, 2, '950.00'),
(26, 41, 3, NULL, NULL, 5, '780.00'),
(27, 41, 24, NULL, NULL, 2, '1800.00'),
(28, 42, 9, NULL, NULL, 1, '520.00'),
(29, 42, 24, NULL, NULL, 1, '1800.00');

-- --------------------------------------------------------

--
-- Структура таблицы `Orders`
--
-- Создание: Июн 13 2025 г., 14:41
-- Последнее обновление: Июн 13 2025 г., 15:33
--

DROP TABLE IF EXISTS `Orders`;
CREATE TABLE `Orders` (
  `order_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `order_date` datetime NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `delivery_method` enum('pickup','delivery') NOT NULL,
  `delivery_address` varchar(255) DEFAULT NULL,
  `payment_method` enum('cash','card') NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `comment` text,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Orders`
--

INSERT INTO `Orders` (`order_id`, `user_id`, `order_date`, `total_amount`, `delivery_method`, `delivery_address`, `payment_method`, `status`, `comment`) VALUES
(29, 791, '2025-05-20 22:42:54', '1400.00', 'pickup', 'Самовывоз', 'cash', 'pending', ''),
(33, 791, '2025-05-24 13:11:22', '15670.00', 'pickup', 'Самовывоз', 'cash', 'pending', ''),
(34, 791, '2025-05-24 13:11:38', '5750.00', 'pickup', 'Самовывоз', 'cash', 'delivered', ''),
(36, 791, '2025-06-07 18:54:26', '450.00', 'pickup', 'Самовывоз', 'cash', 'pending', ''),
(37, 791, '2025-06-07 19:00:09', '950.00', 'delivery', 'Калининград, ул. Леонова, 8', 'cash', 'pending', ''),
(38, 791, '2025-06-07 19:00:46', '3000.00', 'pickup', 'Самовывоз', 'cash', 'pending', ''),
(39, 791, '2025-06-07 19:01:07', '0.00', 'delivery', 'Калининград, ул. Дзержинского, 14', 'cash', 'pending', ''),
(40, 791, '2025-06-07 20:29:30', '1300.00', 'pickup', 'Самовывоз', 'cash', 'pending', ''),
(41, 791, '2025-06-13 14:52:07', '25850.00', 'pickup', 'Самовывоз', 'card', 'pending', ''),
(42, 791, '2025-06-13 18:31:35', '2320.00', 'pickup', 'Самовывоз', 'cash', 'delivered', '');

-- --------------------------------------------------------

--
-- Структура таблицы `Products`
--
-- Создание: Май 06 2025 г., 06:18
-- Последнее обновление: Июн 12 2025 г., 12:43
--

DROP TABLE IF EXISTS `Products`;
CREATE TABLE `Products` (
  `product_id` int UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `manufacturer_id` int UNSIGNED NOT NULL,
  `supplier_id` int UNSIGNED NOT NULL,
  `specification_id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `gallery_id` int UNSIGNED NOT NULL,
  `sku` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(5,2) DEFAULT '0.00',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `average_rating` decimal(3,2) DEFAULT '0.00',
  `stock_quantity` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `Products`
--

INSERT INTO `Products` (`product_id`, `product_name`, `manufacturer_id`, `supplier_id`, `specification_id`, `category_id`, `gallery_id`, `sku`, `description`, `price`, `discount`, `date_added`, `is_active`, `average_rating`, `stock_quantity`) VALUES
(1, 'Вагонка из сосны', 11, 11, 51, 11, 1, 'VAG-SOS-001', 'Качественная вагонка из сосны, идеальна для внутренней отделки', '450.00', '0.00', '2025-04-24 13:37:31', 1, '4.50', 120),
(2, 'Блок Хаус из лиственницы', 11, 12, 52, 12, 2, 'BLK-LAR-001', 'Фасадный блок хаус из лиственницы, устойчив к влаге', '1200.00', '5.00', '2025-04-24 13:37:31', 1, '5.00', 85),
(3, 'Имитация бруса из сосны', 12, 13, 51, 13, 3, 'IMT-SOS-001', 'Имитация бруса для создания эффекта деревянного дома', '780.00', '0.00', '2025-04-24 13:37:31', 1, '3.00', 95),
(4, 'Планкен из дуба', 13, 14, 54, 14, 4, 'PLK-DUB-001', 'Элитный планкен из дуба для фасадных работ', '2500.00', '10.00', '2025-04-24 13:37:31', 1, '5.00', 40),
(5, 'Брус строганный', 11, 15, 54, 15, 5, 'BRS-SOS-001', 'Строганный брус для строительных работ', '950.00', '0.00', '2025-04-24 13:37:31', 1, '5.00', 110),
(6, 'ОСБ плита', 13, 11, 53, 20, 6, 'OSB-001', 'Ориентированно-стружечная плита для строительства', '650.00', '0.00', '2025-04-24 13:37:31', 1, '5.00', 200),
(7, 'Вагонка из сосны 3м', 11, 11, 55, 11, 7, 'VAG-SOS-002', 'Вагонка для внутренней отделки, длина 3м', '480.00', '0.00', '2025-05-06 06:21:14', 1, '5.00', 150),
(8, 'Вагонка из липы', 12, 12, 56, 11, 8, 'VAG-LIP-001', 'Вагонка для бани из липы', '750.00', '5.00', '2025-05-06 06:21:14', 1, '4.00', 85),
(9, 'Вагонка из ели', 13, 13, 57, 11, 9, 'VAG-EL-001', 'Вагонка из ели для внутренних работ', '520.00', '0.00', '2025-05-06 06:21:14', 1, '5.00', 120),
(10, 'Вагонка из кедра', 14, 14, 58, 11, 10, 'VAG-KED-001', 'Элитная вагонка из кедра', '1200.00', '10.00', '2025-05-06 06:21:14', 1, '0.00', 45),
(11, 'Вагонка из сосны 4м', 11, 11, 59, 11, 11, 'VAG-SOS-003', 'Вагонка для наружных работ, длина 4м', '550.00', '0.00', '2025-05-06 06:21:14', 1, '0.00', 90),
(12, 'Вагонка из липы 3м', 12, 12, 60, 11, 12, 'VAG-LIP-002', 'Липовая вагонка для влажных помещений', '780.00', '0.00', '2025-05-06 06:21:14', 1, '0.00', 60),
(13, 'Вагонка из ели 2м', 13, 13, 61, 11, 13, 'VAG-EL-002', 'Еловая вагонка для лёгкой обработки', '450.00', '0.00', '2025-05-06 06:21:14', 1, '0.00', 110),
(14, 'Вагонка из кедра 2.5м', 14, 14, 62, 11, 14, 'VAG-KED-002', 'Кедровая вагонка премиум класса', '1100.00', '5.00', '2025-05-06 06:21:14', 1, '0.00', 35),
(15, 'Вагонка из сосны 3м премиум', 11, 11, 63, 11, 15, 'VAG-SOS-004', 'Вагонка высшего сорта из сосны', '600.00', '0.00', '2025-05-06 06:21:14', 1, '0.00', 75),
(16, 'Вагонка из липы 4м', 12, 12, 64, 11, 16, 'VAG-LIP-003', 'Липовая вагонка для саун и бань', '850.00', '0.00', '2025-05-06 06:21:14', 1, '0.00', 50),
(17, 'Вагонка из ели 3м', 13, 13, 65, 11, 17, 'VAG-EL-003', 'Экологичная еловая вагонка', '500.00', '0.00', '2025-05-06 06:21:14', 1, '0.00', 95),
(18, 'Вагонка из кедра 2м', 14, 14, 66, 11, 18, 'VAG-KED-003', 'Ароматная кедровая вагонка', '1000.00', '0.00', '2025-05-06 06:21:14', 1, '0.00', 40),
(19, 'Вагонка из сосны 2.5м', 11, 11, 67, 11, 19, 'VAG-SOS-005', 'Универсальная сосновая вагонка', '520.00', '0.00', '2025-05-06 06:21:14', 1, '0.00', 105),
(20, 'Вагонка из липы 3м гипоаллергенная', 12, 12, 68, 11, 20, 'VAG-LIP-004', 'Гипоаллергенная липовая вагонка', '800.00', '0.00', '2025-05-06 06:21:14', 1, '0.00', 55),
(21, 'Вагонка из ели 4м', 13, 13, 69, 11, 21, 'VAG-EL-004', 'Прочная еловая вагонка для любых работ', '580.00', '0.00', '2025-05-06 06:21:14', 1, '0.00', 80),
(22, 'Блок Хаус из сосны 6м', 11, 11, 70, 12, 22, 'BLK-SOS-002', 'Фасадный блок хаус из сосны, длина 6м', '1100.00', '0.00', '2025-05-06 16:35:00', 1, '5.00', 75),
(24, 'Блок Хаус из кедра', 13, 13, 72, 12, 24, 'BLK-KED-001', 'Элитный блок хаус из кедра', '1800.00', '10.00', '2025-05-06 16:35:00', 1, '0.00', 35),
(25, 'Блок Хаус из липы', 14, 14, 73, 12, 25, 'BLK-LIP-001', 'Блок хаус из липы для бань', '1300.00', '0.00', '2025-05-06 16:35:00', 1, '0.00', 45),
(26, 'Блок Хаус из сосны 4м', 11, 11, 70, 12, 26, 'BLK-SOS-003', 'Блок хаус из сосны, длина 4м', '850.00', '0.00', '2025-05-06 16:35:00', 1, '0.00', 90),
(27, 'Имитация бруса из сосны', 11, 11, 74, 13, 27, 'IMT-SOS-002', 'Имитация бруса для внутренней отделки', '800.00', '0.00', '2025-05-06 16:35:35', 1, '0.00', 110),
(28, 'Имитация бруса из ели', 12, 12, 75, 13, 28, 'IMT-EL-001', 'Имитация бруса из ели для стен', '750.00', '0.00', '2025-05-06 16:35:35', 1, '0.00', 95),
(29, 'Имитация бруса из кедра', 13, 13, 76, 13, 29, 'IMT-KED-001', 'Премиум имитация бруса из кедра', '1500.00', '5.00', '2025-05-06 16:35:35', 1, '0.00', 50),
(30, 'Имитация бруса из липы', 14, 14, 77, 13, 30, 'IMT-LIP-001', 'Имитация бруса для влажных помещений', '1000.00', '0.00', '2025-05-06 16:35:35', 1, '0.00', 65),
(31, 'Имитация бруса из сосны 4м', 11, 11, 74, 13, 31, 'IMT-SOS-003', 'Имитация бруса, длина 4м', '850.00', '0.00', '2025-05-06 16:35:35', 1, '0.00', 85),
(32, 'Планкен из сосны', 11, 11, 78, 14, 32, 'PLK-SOS-001', 'Планкен для фасадов из сосны', '1200.00', '0.00', '2025-05-06 16:36:06', 1, '0.00', 70),
(33, 'Планкен из ели', 12, 12, 79, 14, 33, 'PLK-EL-001', 'Планкен из ели для вентилируемых фасадов', '1100.00', '0.00', '2025-05-06 16:36:06', 1, '0.00', 60),
(34, 'Планкен из кедра', 13, 13, 80, 14, 34, 'PLK-KED-001', 'Элитный планкен из кедра', '2200.00', '10.00', '2025-05-06 16:36:06', 1, '0.00', 30),
(35, 'Планкен из липы', 14, 14, 81, 14, 35, 'PLK-LIP-001', 'Планкен для террас из липы', '1500.00', '0.00', '2025-05-06 16:36:06', 1, '0.00', 45),
(36, 'Планкен из дуба 3м', 15, 15, 54, 14, 36, 'PLK-DUB-002', 'Элитный планкен из дуба, длина 3м', '2800.00', '0.00', '2025-05-06 16:36:06', 1, '0.00', 25),
(37, 'Брус из сосны 6м', 11, 11, 82, 15, 37, 'BRS-SOS-002', 'Строительный брус камерной сушки', '1200.00', '0.00', '2025-05-06 16:36:06', 1, '0.00', 100),
(38, 'Брус из ели', 12, 12, 83, 15, 38, 'BRS-EL-001', 'Строганный брус из ели', '1100.00', '0.00', '2025-05-06 16:36:06', 1, '5.00', 90),
(39, 'Брус из кедра', 13, 13, 84, 15, 39, 'BRS-KED-001', 'Элитный брус из кедра', '2000.00', '5.00', '2025-05-06 16:36:06', 1, '0.00', 40),
(40, 'Брус из липы', 14, 14, 85, 15, 40, 'BRS-LIP-001', 'Брус из липы для внутренних работ', '1300.00', '0.00', '2025-05-06 16:36:06', 1, '0.00', 55),
(41, 'Брус из дуба', 15, 15, 54, 15, 41, 'BRS-DUB-001', 'Элитный брус из дуба', '2500.00', '0.00', '2025-05-06 16:36:06', 1, '0.00', 30),
(42, 'Брусок из сосны', 11, 11, 86, 16, 42, 'BRK-SOS-001', 'Брусок для обрешетки из сосны', '300.00', '0.00', '2025-05-06 16:36:37', 1, '0.00', 150),
(43, 'Брусок из ели', 12, 12, 87, 16, 43, 'BRK-EL-001', 'Брусок для каркасов из ели', '280.00', '0.00', '2025-05-06 16:36:37', 1, '0.00', 140),
(44, 'Брусок из кедра', 13, 13, 88, 16, 44, 'BRK-KED-001', 'Декоративный брусок из кедра', '500.00', '0.00', '2025-05-06 16:36:37', 1, '0.00', 80),
(45, 'Брусок из липы', 14, 14, 89, 16, 45, 'BRK-LIP-001', 'Брусок для поделок из липы', '350.00', '0.00', '2025-05-06 16:36:37', 1, '0.00', 100),
(47, 'Доска строганная из сосны', 11, 11, 90, 17, 47, 'STR-SOS-001', 'Строганная доска для мебели', '700.00', '0.00', '2025-05-06 16:37:01', 1, '0.00', 120),
(48, 'Доска строганная из ели', 12, 12, 91, 17, 48, 'STR-EL-001', 'Строганная доска из ели', '650.00', '0.00', '2025-05-06 16:37:01', 1, '0.00', 110),
(49, 'Доска строганная из кедра', 13, 13, 92, 17, 49, 'STR-KED-001', 'Ароматная строганная доска', '1200.00', '5.00', '2025-05-06 16:37:01', 1, '0.00', 50),
(50, 'Доска строганная из липы', 14, 14, 93, 17, 50, 'STR-LIP-001', 'Доска для резьбы из липы', '800.00', '0.00', '2025-05-06 16:37:01', 1, '0.00', 70),
(51, 'Доска строганная из дуба', 15, 15, 54, 17, 51, 'STR-DUB-001', 'Элитная строганная доска', '1500.00', '0.00', '2025-05-06 16:37:01', 1, '0.00', 40),
(52, 'Доска пола из сосны', 11, 11, 94, 18, 52, 'POL-SOS-001', 'Шпунтованная половая доска', '900.00', '0.00', '2025-05-06 16:37:42', 1, '0.00', 90),
(53, 'Доска пола из ели', 12, 12, 95, 18, 53, 'POL-EL-001', 'Половая доска из ели', '850.00', '0.00', '2025-05-06 16:37:42', 1, '0.00', 80),
(54, 'Доска пола из кедра', 13, 13, 96, 18, 54, 'POL-KED-001', 'Элитная половая доска', '1600.00', '5.00', '2025-05-06 16:37:42', 1, '0.00', 45),
(55, 'Доска пола из липы', 14, 14, 97, 18, 55, 'POL-LIP-001', 'Половая доска для бань', '1000.00', '0.00', '2025-05-06 16:37:42', 1, '0.00', 60),
(56, 'Доска пола из дуба', 15, 15, 54, 18, 56, 'POL-DUB-001', 'Элитная половая доска из дуба', '2000.00', '0.00', '2025-05-06 16:37:42', 1, '0.00', 35),
(57, 'ДВП влагостойкая 3.2мм', 16, 16, 98, 19, 57, 'DVP-001', 'Влагостойкая древесноволокнистая плита', '350.00', '0.00', '2025-05-06 16:41:37', 1, '0.00', 200),
(58, 'ДВП твердая 4.8мм', 16, 16, 99, 19, 58, 'DVP-002', 'Твердая ДВП для мебели', '450.00', '0.00', '2025-05-06 16:41:37', 1, '0.00', 180),
(59, 'ДВП супертвердая 6мм', 16, 16, 100, 19, 59, 'DVP-003', 'Супертвердая ДВП для полов', '550.00', '0.00', '2025-05-06 16:41:37', 1, '0.00', 150),
(60, 'ДВП оргалит 3.2мм', 16, 16, 98, 19, 60, 'DVP-004', 'Оргалит для задних стенок мебели', '300.00', '0.00', '2025-05-06 16:41:37', 1, '0.00', 220),
(61, 'ДВП перфорированная', 16, 16, 98, 19, 61, 'DVP-005', 'Перфорированная ДВП для акустики', '400.00', '0.00', '2025-05-06 16:41:37', 1, '0.00', 100),
(62, 'ОСБ-3 9мм', 17, 17, 101, 20, 62, 'OSB-002', 'ОСБ плита для внутренних работ', '600.00', '0.00', '2025-05-06 16:41:37', 1, '0.00', 150),
(63, 'ОСБ-3 12мм влагостойкая', 17, 17, 102, 20, 63, 'OSB-003', 'Влагостойкая ОСБ для наружных работ', '800.00', '0.00', '2025-05-06 16:41:37', 1, '0.00', 120),
(64, 'ОСБ-4 15мм усиленная', 17, 17, 103, 20, 64, 'OSB-004', 'Усиленная ОСБ для несущих конструкций', '1000.00', '5.00', '2025-05-06 16:41:37', 1, '0.00', 90),
(65, 'ОСБ-3 9мм шпунтованная', 17, 17, 101, 20, 65, 'OSB-005', 'Шпунтованная ОСБ для полов', '700.00', '0.00', '2025-05-06 16:41:37', 1, '5.00', 110),
(68, 'Блок Хаус из лиственниц', 16, 16, 105, 12, 68, '1234568', 'купшрурокп', '1589.00', '0.00', '2025-06-10 08:08:55', 1, '0.00', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `ProductSpecifications`
--
-- Создание: Апр 24 2025 г., 13:25
-- Последнее обновление: Июн 10 2025 г., 08:09
--

DROP TABLE IF EXISTS `ProductSpecifications`;
CREATE TABLE `ProductSpecifications` (
  `specification_id` int UNSIGNED NOT NULL,
  `material` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `features` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `ProductSpecifications`
--

INSERT INTO `ProductSpecifications` (`specification_id`, `material`, `size`, `color`, `weight`, `features`) VALUES
(51, 'Сосна', '2 м x 90 мм x 12 мм', 'Натуральный', '1.50', 'Шпунтованная, Сухая, Строганная'),
(52, 'Лиственница', '4 м x 120 мм x 20 мм', 'Красноватый', '3.20', 'Фасадная, Прямая'),
(53, 'Липа', '3 м x 96 мм x 14 мм', '', '2.10', 'Для бани и сауны'),
(54, 'Ель', '6 м x 100 мм x 25 мм', '', '4.00', 'С черновой обработкой'),
(55, 'Сосна', '3 м x 90 мм x 12 мм', 'Натуральный', '2.25', 'Для внутренней отделки'),
(56, 'Липа', '2 м x 80 мм x 10 мм', 'Светлый', '1.20', 'Для бани и сауны'),
(57, 'Ель', '2.5 м x 95 мм x 14 мм', 'Белёсый', '1.80', 'Устойчива к деформации'),
(58, 'Кедр', '3 м x 100 мм x 15 мм', 'Золотистый', '2.50', 'Ароматная, с природными антисептиками'),
(59, 'Сосна', '4 м x 120 мм x 18 мм', 'Натуральный', '3.60', 'Для наружных работ'),
(60, 'Липа', '3 м x 85 мм x 12 мм', 'Светлый', '1.80', 'Низкая теплопроводность'),
(61, 'Ель', '2 м x 90 мм x 12 мм', 'Белёсый', '1.20', 'Лёгкая обработка'),
(62, 'Кедр', '2.5 м x 95 мм x 14 мм', 'Золотистый', '1.90', 'Долговечная'),
(63, 'Сосна', '3 м x 100 мм x 15 мм', 'Натуральный', '2.50', 'Высокого качества'),
(64, 'Липа', '4 м x 120 мм x 18 мм', 'Светлый', '3.60', 'Для влажных помещений'),
(65, 'Ель', '3 м x 85 мм x 12 мм', 'Белёсый', '1.80', 'Экологически чистая'),
(66, 'Кедр', '2 м x 90 мм x 12 мм', 'Золотистый', '1.20', 'Природный аромат'),
(67, 'Сосна', '2.5 м x 95 мм x 14 мм', 'Натуральный', '1.90', 'Для любых помещений'),
(68, 'Липа', '3 м x 100 мм x 15 мм', 'Светлый', '2.50', 'Гипоаллергенная'),
(69, 'Ель', '4 м x 120 мм x 18 мм', 'Белёсый', '3.60', 'Прочная'),
(70, 'Сосна', '6 м x 190 мм x 20 мм', 'Натуральный', '4.50', 'Для фасадных работ'),
(72, 'Кедр', '5 м x 200 мм x 22 мм', 'Золотистый', '5.00', 'Долговечный, ароматный'),
(73, 'Липа', '3 м x 170 мм x 17 мм', 'Светлый', '2.50', 'Для внутренней отделки'),
(74, 'Сосна', '3 м x 140 мм x 16 мм', 'Натуральный', '2.80', 'Шпунтованная'),
(75, 'Ель', '4 м x 150 мм x 18 мм', 'Белёсый', '3.50', 'Для стен и потолков'),
(76, 'Кедр', '5 м x 160 мм x 20 мм', 'Золотистый', '4.50', 'Премиум качество'),
(77, 'Липа', '2 м x 130 мм x 15 мм', 'Светлый', '1.80', 'Для бань и саун'),
(78, 'Сосна', '3 м x 120 мм x 18 мм', 'Натуральный', '2.50', 'Скошенные края'),
(79, 'Ель', '4 м x 130 мм x 20 мм', 'Белёсый', '3.20', 'Для вентилируемых фасадов'),
(80, 'Кедр', '5 м x 140 мм x 22 мм', 'Золотистый', '4.00', 'Элитная отделка'),
(81, 'Липа', '2 м x 110 мм x 16 мм', 'Светлый', '1.60', 'Для террас и беседок'),
(82, 'Сосна', '6 м x 150 мм x 150 мм', 'Натуральный', '25.00', 'Камерной сушки'),
(83, 'Ель', '6 м x 140 мм x 140 мм', 'Белёсый', '22.00', 'Строганный'),
(84, 'Кедр', '6 м x 160 мм x 160 мм', 'Золотистый', '28.00', 'Премиум качество'),
(85, 'Липа', '4 м x 130 мм x 130 мм', 'Светлый', '15.00', 'Для внутренних работ'),
(86, 'Сосна', '3 м x 50 мм x 50 мм', 'Натуральный', '3.00', 'Для обрешетки'),
(87, 'Ель', '3 м x 40 мм x 40 мм', 'Белёсый', '2.20', 'Для каркасов'),
(88, 'Кедр', '3 м x 60 мм x 60 мм', 'Золотистый', '4.00', 'Декоративный'),
(89, 'Липа', '2 м x 45 мм x 45 мм', 'Светлый', '1.50', 'Для поделок'),
(90, 'Сосна', '4 м x 100 мм x 25 мм', 'Натуральный', '6.00', 'Шлифованная'),
(91, 'Ель', '4 м x 90 мм x 22 мм', 'Белёсый', '5.00', 'Для мебели'),
(92, 'Кедр', '3 м x 120 мм x 30 мм', 'Золотистый', '7.00', 'Ароматная'),
(93, 'Липа', '2 м x 80 мм x 20 мм', 'Светлый', '2.50', 'Для резьбы'),
(94, 'Сосна', '4 м x 120 мм x 28 мм', 'Натуральный', '8.00', 'Шпунтованная'),
(95, 'Ель', '4 м x 110 мм x 26 мм', 'Белёсый', '7.00', 'Для жилых помещений'),
(96, 'Кедр', '3 м x 130 мм x 30 мм', 'Золотистый', '9.00', 'Элитная'),
(97, 'Липа', '2 м x 100 мм x 25 мм', 'Светлый', '4.00', 'Для бань'),
(98, 'Древесное волокно', '2.75 м x 1.22 м x 3.2 мм', 'Коричневый', '15.00', 'Влагостойкая'),
(99, 'Древесное волокно', '2.75 м x 1.22 м x 4.8 мм', 'Коричневый', '20.00', 'Твердая'),
(100, 'Древесное волокно', '2.75 м x 1.22 м x 6.0 мм', 'Коричневый', '25.00', 'Супертвердая'),
(101, 'Щепа хвойных пород', '2.5 м x 1.25 м x 9 мм', 'Желтый', '18.00', 'OSB-3'),
(102, 'Щепа хвойных пород', '2.5 м x 1.25 м x 12 мм', 'Желтый', '24.00', 'OSB-3 влагостойкая'),
(103, 'Щепа хвойных пород', '2.5 м x 1.25 м x 15 мм', 'Желтый', '30.00', 'OSB-4 усиленная'),
(105, 'дерево', '60', 'белоый', '2.50', '-');

-- --------------------------------------------------------

--
-- Структура таблицы `Reviews`
--
-- Создание: Май 20 2025 г., 18:09
-- Последнее обновление: Июн 12 2025 г., 11:19
--

DROP TABLE IF EXISTS `Reviews`;
CREATE TABLE `Reviews` (
  `review_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `rating` int NOT NULL,
  `review_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `Reviews`
--

INSERT INTO `Reviews` (`review_id`, `product_id`, `user_id`, `rating`, `review_text`, `created_at`) VALUES
(11, 65, 791, 5, '1', '2025-05-20 18:23:28'),
(13, 38, 791, 5, 'Все очень качественно!спасибо за работу!', '2025-05-24 08:40:54'),
(15, 22, 791, 5, 'тиапветчеяплдтяловкачдтеыы', '2025-05-22 13:14:13'),
(16, 5, 791, 5, '1111', '2025-06-12 11:19:50');

-- --------------------------------------------------------

--
-- Структура таблицы `Roles`
--
-- Создание: Апр 24 2025 г., 13:25
--

DROP TABLE IF EXISTS `Roles`;
CREATE TABLE `Roles` (
  `role_id` int UNSIGNED NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `Roles`
--

INSERT INTO `Roles` (`role_id`, `role_name`) VALUES
(777, 'admin'),
(1111, 'Пользователь');

-- --------------------------------------------------------

--
-- Структура таблицы `Suppliers`
--
-- Создание: Апр 24 2025 г., 13:25
--

DROP TABLE IF EXISTS `Suppliers`;
CREATE TABLE `Suppliers` (
  `supplier_id` int UNSIGNED NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `Suppliers`
--

INSERT INTO `Suppliers` (`supplier_id`, `supplier_name`, `contact_person`, `phone`, `email`, `address`) VALUES
(11, 'СтройМаркет', 'Иван Петров', '+7 900 123-45-67', 'petrov@stmarket.ru', 'Москва, ул. Лесная, 12'),
(12, 'Пиломатериалы24', 'Елена Смирнова', '+7 921 555-66-77', 'elena@plm24.ru', 'СПб, пр. Лесной, 8'),
(13, 'EcoBuild', 'Олег Кузнецов', '+7 495 765-43-21', 'oleg@ecobuild.ru', 'Новосибирск, ул. Промышленная, 45'),
(14, 'ЛесТорг', 'Алексей Иванов', '+7 912 345-67-89', 'alex@lestorg.ru', 'Екатеринбург, ул. Деревообрабатывающая, 15'),
(15, 'ДревПром', 'Мария Сидорова', '+7 922 333-44-55', 'maria@drevprom.ru', 'Казань, пр. Лесной, 33'),
(16, 'ДВП-Профи', 'Сергей Волков', '+7 901 234-56-78', 'volkov@dvp-profi.ru', 'Москва, ул. Промышленная, 34'),
(17, 'ОСБ-Трейд', 'Анна Козлова', '+7 902 345-67-89', 'kozlova@osb-trade.ru', 'Санкт-Петербург, пр. Заводской, 56'),
(18, 'Лесной Склад', 'Дмитрий Соколов', '+7 903 456-78-90', 'sokolov@les-sklad.ru', 'Новосибирск, ул. Складская, 78'),
(19, 'Дерево-Экспорт', 'Ольга Морозова', '+7 904 567-89-01', 'morozova@drevo-export.ru', 'Екатеринбург, ул. Экспортная, 90'),
(20, 'Мастер Дерева', 'Артем Лебедев', '+7 905 678-90-12', 'lebedev@master-dereva.ru', 'Казань, пр. Деревообрабатывающий, 12'),
(21, 'Эко-Пиломатериалы', 'Наталья Воробьева', '+7 906 789-01-23', 'vorobeva@eco-pilomaterial.ru', 'Краснодар, ул. Экологичная, 34'),
(22, 'Премиум Дерево', 'Игорь Новиков', '+7 907 890-12-34', 'novikov@premium-derevo.ru', 'Ростов-на-Дону, ул. Премиальная, 56'),
(23, 'Сибирский Лес', 'Евгения Петрова', '+7 908 901-23-45', 'petrova@sib-forest.ru', 'Иркутск, ул. Таежная, 78'),
(24, 'Северный Дерево', 'Александр Кузнецов', '+7 909 012-34-56', 'kuznetsov@sever-derevo.ru', 'Архангельск, пр. Северный, 90'),
(25, 'Южный Лес', 'Татьяна Иванова', '+7 910 123-45-67', 'ivanova@yug-les.ru', 'Сочи, ул. Южная, 12');

-- --------------------------------------------------------

--
-- Структура таблицы `UserCredentials`
--
-- Создание: Апр 24 2025 г., 13:25
-- Последнее обновление: Июн 13 2025 г., 14:38
--

DROP TABLE IF EXISTS `UserCredentials`;
CREATE TABLE `UserCredentials` (
  `user_id` int UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `UserCredentials`
--

INSERT INTO `UserCredentials` (`user_id`, `username`, `password`, `email`) VALUES
(3, 'Куча', '$2y$10$RfswhDigrmbVRnbcyETmZ.BcaUtR3Ozzr3.XzhApzwR/5P/PFonF2', 'kzrxxt@gmail.com'),
(785, 'patlatiy', '$2y$10$cUU9jxJJosfNfpx8Lx/v7u8fIfeBkHIW/sTKAL0a7YQAXOGCQIsMK', 'dshlyupikov@gmail.com'),
(791, 'bADMIN_al1vaa', '$2y$10$4k46da8gQsBB6cdoLsUDKuu5KMF9HHqlFSXn56RvBy/NOswvxVgsm', 'alivir1111@gmail.com');

-- --------------------------------------------------------

--
-- Структура таблицы `Users`
--
-- Создание: Апр 24 2025 г., 13:25
-- Последнее обновление: Июн 13 2025 г., 15:34
--

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
  `user_id` int UNSIGNED NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `role_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `Users`
--

INSERT INTO `Users` (`user_id`, `last_name`, `first_name`, `middle_name`, `phone`, `address`, `role_id`) VALUES
(3, 'Сидоренко', 'Максим', 'Сергеевич', '9520586327', 'Площадь Октябрьская 13', 1111),
(785, 'Шлюпиков', 'Данил', 'Александрович', '+79965214032', 'Гурьевск, СНТ Заречье, Ул. Летняя 79', 1111),
(791, 'Вибецките', 'witch', 'Стасевна', '9114844685', 'г.Калининград ул.Александра Невского, 89', 777);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `CartItems`
--
ALTER TABLE `CartItems`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `Carts`
--
ALTER TABLE `Carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `Categories`
--
ALTER TABLE `Categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`),
  ADD KEY `parent_category_id` (`parent_category_id`);

--
-- Индексы таблицы `Galleries`
--
ALTER TABLE `Galleries`
  ADD PRIMARY KEY (`gallery_id`);

--
-- Индексы таблицы `Inventory`
--
ALTER TABLE `Inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `Manufacturers`
--
ALTER TABLE `Manufacturers`
  ADD PRIMARY KEY (`manufacturer_id`),
  ADD UNIQUE KEY `manufacturer_name` (`manufacturer_name`);

--
-- Индексы таблицы `OrderItems`
--
ALTER TABLE `OrderItems`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `Products`
--
ALTER TABLE `Products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `manufacturer_id` (`manufacturer_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `specification_id` (`specification_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `gallery_id` (`gallery_id`);

--
-- Индексы таблицы `ProductSpecifications`
--
ALTER TABLE `ProductSpecifications`
  ADD PRIMARY KEY (`specification_id`);

--
-- Индексы таблицы `Reviews`
--
ALTER TABLE `Reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `Roles`
--
ALTER TABLE `Roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Индексы таблицы `Suppliers`
--
ALTER TABLE `Suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD UNIQUE KEY `supplier_name` (`supplier_name`);

--
-- Индексы таблицы `UserCredentials`
--
ALTER TABLE `UserCredentials`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `CartItems`
--
ALTER TABLE `CartItems`
  MODIFY `cart_item_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT для таблицы `Carts`
--
ALTER TABLE `Carts`
  MODIFY `cart_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT для таблицы `Categories`
--
ALTER TABLE `Categories`
  MODIFY `category_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `Galleries`
--
ALTER TABLE `Galleries`
  MODIFY `gallery_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT для таблицы `Inventory`
--
ALTER TABLE `Inventory`
  MODIFY `inventory_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT для таблицы `Manufacturers`
--
ALTER TABLE `Manufacturers`
  MODIFY `manufacturer_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `OrderItems`
--
ALTER TABLE `OrderItems`
  MODIFY `order_item_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT для таблицы `Orders`
--
ALTER TABLE `Orders`
  MODIFY `order_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT для таблицы `Products`
--
ALTER TABLE `Products`
  MODIFY `product_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT для таблицы `ProductSpecifications`
--
ALTER TABLE `ProductSpecifications`
  MODIFY `specification_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT для таблицы `Reviews`
--
ALTER TABLE `Reviews`
  MODIFY `review_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `Roles`
--
ALTER TABLE `Roles`
  MODIFY `role_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1112;

--
-- AUTO_INCREMENT для таблицы `Suppliers`
--
ALTER TABLE `Suppliers`
  MODIFY `supplier_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `UserCredentials`
--
ALTER TABLE `UserCredentials`
  MODIFY `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=793;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `CartItems`
--
ALTER TABLE `CartItems`
  ADD CONSTRAINT `CartItems_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `Carts` (`cart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `CartItems_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `Carts`
--
ALTER TABLE `Carts`
  ADD CONSTRAINT `Carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Ограничения внешнего ключа таблицы `Categories`
--
ALTER TABLE `Categories`
  ADD CONSTRAINT `Categories_ibfk_1` FOREIGN KEY (`parent_category_id`) REFERENCES `Categories` (`category_id`);

--
-- Ограничения внешнего ключа таблицы `Inventory`
--
ALTER TABLE `Inventory`
  ADD CONSTRAINT `Inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `OrderItems`
--
ALTER TABLE `OrderItems`
  ADD CONSTRAINT `OrderItems_order_fk` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`),
  ADD CONSTRAINT `OrderItems_product_fk` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `Orders`
--
ALTER TABLE `Orders`
  ADD CONSTRAINT `Orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `Products`
--
ALTER TABLE `Products`
  ADD CONSTRAINT `Products_ibfk_1` FOREIGN KEY (`manufacturer_id`) REFERENCES `Manufacturers` (`manufacturer_id`),
  ADD CONSTRAINT `Products_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `Suppliers` (`supplier_id`),
  ADD CONSTRAINT `Products_ibfk_3` FOREIGN KEY (`specification_id`) REFERENCES `ProductSpecifications` (`specification_id`),
  ADD CONSTRAINT `Products_ibfk_4` FOREIGN KEY (`category_id`) REFERENCES `Categories` (`category_id`),
  ADD CONSTRAINT `Products_ibfk_5` FOREIGN KEY (`gallery_id`) REFERENCES `Galleries` (`gallery_id`);

--
-- Ограничения внешнего ключа таблицы `Reviews`
--
ALTER TABLE `Reviews`
  ADD CONSTRAINT `Reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Ограничения внешнего ключа таблицы `Users`
--
ALTER TABLE `Users`
  ADD CONSTRAINT `Users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `Roles` (`role_id`),
  ADD CONSTRAINT `Users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `UserCredentials` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
