-- =====================================================================
-- BD HOTEL - MySQL Schema + Seed Data
-- =====================================================================
-- Usage:  1. Open phpMyAdmin -> Import -> select this file
--         2. OR from CLI:  mysql -u root -p < bdhotel.sql
-- =====================================================================

DROP DATABASE IF EXISTS `bd_hotel`;
CREATE DATABASE `bd_hotel` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bd_hotel`;

-- ------------------------------ USERS -------------------------------
CREATE TABLE `users` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`          VARCHAR(120) NOT NULL,
  `email`         VARCHAR(180) NOT NULL UNIQUE,
  `phone`         VARCHAR(30)  DEFAULT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role`          ENUM('user','admin') NOT NULL DEFAULT 'user',
  `reset_token`   VARCHAR(120) DEFAULT NULL,
  `reset_expires` DATETIME DEFAULT NULL,
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------ ROOMS -------------------------------
CREATE TABLE `rooms` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(150) NOT NULL,
  `category`    VARCHAR(80)  NOT NULL,
  `price`       DECIMAL(10,2) NOT NULL,
  `capacity`    TINYINT UNSIGNED NOT NULL DEFAULT 2,
  `description` TEXT NOT NULL,
  `amenities`   TEXT,                     -- comma-separated
  `images`      TEXT,                     -- newline-separated URLs / filenames
  `available`   TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_category` (`category`),
  INDEX `idx_price` (`price`)
) ENGINE=InnoDB;

-- ------------------------------ BOOKINGS ----------------------------
CREATE TABLE `bookings` (
  `id`              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`         INT UNSIGNED DEFAULT NULL,
  `room_id`         INT UNSIGNED NOT NULL,
  `full_name`       VARCHAR(150) NOT NULL,
  `email`           VARCHAR(180) NOT NULL,
  `mobile`          VARCHAR(30)  NOT NULL,
  `address`         VARCHAR(255) DEFAULT NULL,
  `check_in`        DATE NOT NULL,
  `check_out`       DATE NOT NULL,
  `adults`          TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `children`        TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `payment_method`  ENUM('Cash','Card','UPI','Bank Transfer','Stripe') NOT NULL DEFAULT 'Cash',
  `special_request` TEXT,
  `total_price`     DECIMAL(10,2) NOT NULL DEFAULT 0,
  `status`          ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE CASCADE,
  INDEX `idx_status` (`status`),
  INDEX `idx_dates` (`check_in`, `check_out`)
) ENGINE=InnoDB;

-- ------------------------------ PAYMENTS ----------------------------
CREATE TABLE `payments` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT UNSIGNED NOT NULL,
  `amount`     DECIMAL(10,2) NOT NULL,
  `method`     VARCHAR(60) NOT NULL,
  `reference`  VARCHAR(120) DEFAULT NULL,
  `status`     ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------ CONTACT MESSAGES --------------------
CREATE TABLE `contact_messages` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`       VARCHAR(120) NOT NULL,
  `email`      VARCHAR(180) NOT NULL,
  `subject`    VARCHAR(200) NOT NULL,
  `message`    TEXT NOT NULL,
  `is_read`    TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------ GALLERY -----------------------------
CREATE TABLE `gallery` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `url`        VARCHAR(500) NOT NULL,
  `category`   VARCHAR(80)  NOT NULL DEFAULT 'General',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------ REVIEWS -----------------------------
CREATE TABLE `reviews` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`    INT UNSIGNED DEFAULT NULL,
  `name`       VARCHAR(120) NOT NULL,
  `rating`     TINYINT UNSIGNED NOT NULL,
  `comment`    TEXT NOT NULL,
  `approved`   TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------ NEWSLETTER --------------------------
CREATE TABLE `newsletter` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `email`      VARCHAR(180) NOT NULL UNIQUE,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================================
-- SEED DATA
-- =====================================================================

-- Default admin: admin@bdhotel.com / Admin@123
-- (hash is bcrypt of "Admin@123"; PHP will re-hash on first login if needed)
INSERT INTO `users` (`name`,`email`,`phone`,`password_hash`,`role`) VALUES
('BD Hotel Admin','admin@bdhotel.com','', '$2y$10$3Q3JQeQmM9Xa9Q0oJv2xTOZ0Y7fW.d7oyc8m1EJfE9mVj3vE0G8m2', 'admin');

INSERT INTO `rooms` (`name`,`category`,`price`,`capacity`,`description`,`amenities`,`images`) VALUES
('Deluxe Room','Deluxe Room',149.00,2,
 'Elegantly appointed with king-size bed, city view and a walk-in shower. A calm retreat for business travellers.',
 'Wi-Fi, Air Conditioning, Smart TV, Mini Bar, Room Service',
 'https://images.unsplash.com/photo-1629140727571-9b5c6f6267b4?auto=format&fit=crop&w=1600&q=80\nhttps://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=1600&q=80'),
('Super Deluxe','Super Deluxe',219.00,2,
 'Refined interiors with a private balcony, luxurious linens and a marble bathroom with soaking tub.',
 'Balcony, Wi-Fi, Bathtub, Espresso Machine, Smart TV',
 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1600&q=80\nhttps://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1600&q=80'),
('Executive Room','Executive Room',289.00,2,
 'Dedicated workspace, lounge access and evening cocktails included. Ideal for the discerning executive.',
 'Lounge Access, Workspace, Wi-Fi, Butler Service, Rain Shower',
 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1600&q=80\nhttps://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1600&q=80'),
('Family Room','Family Room',329.00,4,
 'Two bedrooms, a shared living area and thoughtful touches for children. Space for the whole family.',
 'Two Bedrooms, Living Area, Kids Amenities, Wi-Fi, Smart TV',
 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1600&q=80\nhttps://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=1600&q=80'),
('Luxury Suite','Luxury Suite',489.00,3,
 'A spacious suite with panoramic views, a separate lounge and premium in-room dining service.',
 'Panoramic View, Lounge, Butler, Jacuzzi, Minibar',
 'https://images.unsplash.com/photo-1590381105924-c72589b9ef3f?auto=format&fit=crop&w=1600&q=80\nhttps://images.unsplash.com/photo-1595576508898-0ad5c879a061?auto=format&fit=crop&w=1600&q=80'),
('Presidential Suite','Presidential Suite',899.00,4,
 'The pinnacle of hospitality. Private terrace, personal chef on request and unparalleled attention to detail.',
 'Private Terrace, Personal Chef, Grand Living Room, Steam Room, Chauffeur',
 'https://images.unsplash.com/photo-1731336478850-6bce7235e320?auto=format&fit=crop&w=1600&q=80\nhttps://images.unsplash.com/photo-1601565415267-724db0e9fa02?auto=format&fit=crop&w=1600&q=80');

INSERT INTO `gallery` (`url`,`category`) VALUES
('https://images.pexels.com/photos/29602700/pexels-photo-29602700.jpeg?auto=compress&cs=tinysrgb&w=1600','Exterior'),
('https://images.pexels.com/photos/460537/pexels-photo-460537.jpeg?auto=compress&cs=tinysrgb&w=1600','Dining'),
('https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?auto=format&fit=crop&w=1600&q=80','Lounge'),
('https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=1600&q=80','Spa'),
('https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1600&q=80','Rooms'),
('https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1600&q=80','Rooms'),
('https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1600&q=80','Rooms'),
('https://images.unsplash.com/photo-1590381105924-c72589b9ef3f?auto=format&fit=crop&w=1600&q=80','Suite');

INSERT INTO `reviews` (`name`,`rating`,`comment`,`approved`) VALUES
('Amelia Hart',5,'An unforgettable escape. Every detail—from the linen to the concierge—was flawless.',1),
('Jonathan Reyes',5,'Refined, quiet luxury. The Executive Suite service made our anniversary truly special.',1),
('Priya Sharma',4,'The spa and fine dining are second to none. We already booked our next stay.',1);
