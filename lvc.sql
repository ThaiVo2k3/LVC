-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 02, 2026 lúc 12:52 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `lvc`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `brands`
--

INSERT INTO `brands` (`id`, `name`, `image`) VALUES
(1, 'Canon', 'e749cfcc64468292cd260da8dac35650.png'),
(2, 'Sony', '0a9b67c1ca4b2c6076596a0c2defbcfa.jpg'),
(3, 'Nikon', '11d79149d9a0b4d18a65c5957acfc26f.jpg'),
(4, 'Fujifilm', '2268568d2ec02354f2c9a65025fecb06.jpg'),
(5, 'Panasonic', '1b49762c36dafa175cb443875a7e5a1c.jpg'),
(6, 'GoPro', '8905d98e3dd477389d04bd611afb02eb.jpg'),
(7, 'DJI', 'b81269a1db1709667cd93b7b59bcc5de.jpg'),
(8, 'Olympus', 'e2d0a4685074fcaa7e278e707d391c6a.jpg'),
(9, 'Leica', '6839bd764ac0a84be25383f3a7104e75.jpg'),
(10, 'Pentax', '067094bd8a00461c851a48532274d277.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Camera'),
(2, 'Action Cam');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(12,0) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('chờ xử lý','đang xử lý','hoàn thành','đã hủy') DEFAULT 'chờ xử lý',
  `payment_method` enum('cod','online') DEFAULT NULL,
  `payment_status` enum('chưa thanh toán','đã thanh toán','thất bại') DEFAULT 'chưa thanh toán',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `code`, `user_id`, `total_price`, `address`, `status`, `payment_method`, `payment_status`, `created_at`) VALUES
(1, 'DH9JJML91Z', 2, 39000000, 'rrr', 'chờ xử lý', 'cod', 'chưa thanh toán', '2026-04-02 09:09:39'),
(2, 'DHOJ0P6RKP', 2, 54000000, 'e', 'chờ xử lý', 'cod', 'chưa thanh toán', '2026-04-02 09:12:19'),
(3, 'DHNETBJQWW', 2, 54000000, 'e', 'chờ xử lý', 'cod', 'chưa thanh toán', '2026-04-02 09:12:22'),
(4, 'DHAHD4DD8K', 2, 220000000, 'aaaaa', 'chờ xử lý', 'cod', 'chưa thanh toán', '2026-04-02 09:13:59'),
(5, 'DHUE7KW8TC', 2, 18000000, 'gh', 'đã hủy', 'cod', 'chưa thanh toán', '2026-04-02 09:21:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(12,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 4, 1, 39000000),
(2, 2, 10, 3, 18000000),
(3, 3, 10, 3, 18000000),
(4, 4, 9, 2, 80000000),
(5, 4, 8, 3, 20000000),
(6, 5, 10, 1, 18000000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` int(10) UNSIGNED DEFAULT NULL,
  `transaction_code` varchar(100) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `sender_name` varchar(100) DEFAULT NULL,
  `request_time` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `amount`, `transaction_code`, `content`, `sender_name`, `request_time`, `created_at`) VALUES
(1, 2, 52000000, 'TXN001', 'Thanh toán đơn ORD001', 'Nguyen Van A', '2026-03-30 08:20:17', '2026-03-30 01:20:17'),
(2, 3, 45000000, 'TXN002', 'Thanh toán đơn ORD002', 'Tran Thi B', '2026-03-30 08:20:17', '2026-03-30 01:20:17'),
(3, 3, 30000000, 'TXN003', 'Thanh toán đơn ORD003', 'Le Van C', '2026-03-30 08:20:17', '2026-03-30 01:20:17'),
(4, 2, 12000000, 'TXN004', 'Thanh toán đơn ORD004', 'Pham Thi D', '2026-03-30 08:20:17', '2026-03-30 01:20:17'),
(5, 3, 11000000, 'TXN005', 'Thanh toán đơn ORD005', 'Hoang Van E', '2026-03-30 08:20:17', '2026-03-30 01:20:17'),
(6, 3, 39000000, 'TXN006', 'Thanh toán đơn ORD006', 'Vo Van F', '2026-03-30 08:20:17', '2026-03-30 01:20:17'),
(7, 3, 20000000, 'TXN007', 'Thanh toán đơn ORD007', 'Do Thi G', '2026-03-30 08:20:17', '2026-03-30 01:20:17'),
(8, 3, 120000000, 'TXN008', 'Thanh toán đơn ORD008', 'Bui Van H', '2026-03-30 08:20:17', '2026-03-30 01:20:17'),
(9, 2, 18000000, 'TXN009', 'Thanh toán đơn ORD009', 'Ngo Thi I', '2026-03-30 08:20:17', '2026-03-30 01:20:17'),
(10, 2, 48000000, 'TXN010', 'Thanh toán đơn ORD010', 'Nguyen Van A', '2026-03-30 08:20:17', '2026-03-30 01:20:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(12,0) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `camera_resolution` varchar(50) DEFAULT NULL,
  `camera_fps` int(11) DEFAULT NULL,
  `camera_lens` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `category_id`, `brand_id`, `description`, `camera_resolution`, `camera_fps`, `camera_lens`, `created_at`) VALUES
(1, 'Canon EOS R6', 52000000, 'r6.jpg', 1, 1, 'Máy ảnh full-frame mạnh mẽ', '20MP', 60, 'RF 24-105mm', '2026-03-30 01:20:17'),
(2, 'Sony A7 III', 45000000, 'a7iii.jpg', 1, 2, 'Chụp thiếu sáng tốt', '24MP', 60, 'FE 28-70mm', '2026-03-30 01:20:17'),
(3, 'Nikon Z6 II', 48000000, 'z6ii.jpg', 1, 3, 'Hiệu năng cao', '24.5MP', 60, 'Z 24-70mm', '2026-03-30 01:20:17'),
(4, 'Fujifilm X-T4', 39000000, 'xt4.jpg', 1, 4, 'Quay video đẹp', '26MP', 60, 'XF 18-55mm', '2026-03-30 01:20:17'),
(5, 'Panasonic GH5', 30000000, 'gh5.jpg', 1, 5, 'Quay phim chuyên nghiệp', '20MP', 120, 'Lumix 12-60mm', '2026-03-30 01:20:17'),
(6, 'GoPro Hero 11', 12000000, 'e85994334105892d0f11ef807bbe4652.webp', 2, 6, 'Quay hành động', '27MP', 120, 'Wide', '2026-03-30 01:20:17'),
(7, 'DJI Osmo Action 4', 11000000, '670097b5dc0f339e1b9330cb6a61075b.webp', 2, 7, 'Chống rung tốt', '20MP', 120, 'Ultra Wide', '2026-03-30 01:20:17'),
(8, 'Olympus OM-D E-M10', 20000000, 'd016b190b1dc163defbc2aa4a281e8e8.webp', 1, 8, 'Nhỏ gọn', '16MP', 30, '14-42mm', '2026-03-30 01:20:17'),
(9, 'Leica Q2', 80000000, 'c0a89529b0c523329a75efc97e3c193e.webp', 1, 9, 'Cao cấp', '47MP', 30, '28mm', '2026-03-30 01:20:17'),
(10, 'Pentax K-70', 18000000, '722facfba513ebf477849e8060ef66c7.webp', 1, 10, 'Chống thời tiết', '24MP', 30, '18-135mm', '2026-03-30 01:20:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `password`, `email`, `role`, `created_at`) VALUES
(1, 'administrator', '0916353946', '$2y$10$qGv6ywfhuVdm6bIbN/SZ8.za2f44qF0.zOmTWVXyu4Tc7aN0Wi8zS', NULL, 'admin', '2026-03-23 15:54:45'),
(2, 'Nguyễn Văn B', '0916353940', '$2y$10$maBOlrK80bDxjpa74KUnyuU8CIBwu4MlCFiybkAaerKxIHE9G5iM6', '', 'customer', '2026-03-23 16:42:37'),
(3, 'Nguyễn Văn BC', '0916353941', '$2y$10$iqYb/byag1TC.NSnXO3P5eBXw3T2Xchs7ve2ETx5hFZHV.fB/iKrC', '', 'customer', '2026-03-25 01:08:39');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
