CREATE TABLE `persons` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `semester` varchar(20) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `persons` (`id`, `name`, `email`, `phone`, `department`, `semester`, `gender`, `image`, `created_at`, `user_id`) 
VALUES
(1, 'Ahmad Abdullah', 'worldneon8@gmail.com', '01880001517', 'CSE', '7th', 'Male', '', '2026-07-10 13:09:08', 0),
(4, 'Mahrab Hasan', 'mahrabh256@gmail.com', '01923067120', 'CSE', '4th', 'Male', '', '2026-07-11 06:33:46', 0),
(7, 'Ahmad Abdullah', 'worldneon8@gmail.com', '01880001517', 'CSE', '7th', 'Male', '', '2026-07-11 08:58:28', 11),
(8, 'Shahriar Hossain Fahim', 'fahimsah@gmail.com', '01754893216', 'CSE', '7th', 'Male', '', '2026-07-11 13:07:36', 11),
(17, 'kamaboko Gonpachiro', 'kamaboko@gmail.com', '09631641397', 'M.M', '6th', 'Male', '1783923342_kamaboko_Gonpachiro.png', '2026-07-13 06:15:41', 16),
(18, 'Sefataj kabir', 'sefatajkabir@gmail.com', '01934531782', 'CSE', '7th', 'Male', '1783923599_Sefataj_kabir.png', '2026-07-13 06:16:53', 16),
(20, 'Mahrab Hasan', 'mahrab256@gmail.com', '01923967120', 'CSE', '4th', 'Male', '1783923667_Mahrab_Hasan.png', '2026-07-13 06:21:07', 16),
(23, 'personkd6_3', 'personkd63@gmail.com', '01874281524', 'CSE', '7th', 'Male', '1783942513_Ahmad_Abdullah.jpg', '2026-07-13 08:13:27', 9),
(24, 'Shahriar Hossain Fahim', 'fahimsahriar61482@gmail.com', '01529561017', 'CSE', '7th', 'Male', '1783935356_Sefataj_Bin_Khidir.png', '2026-07-13 09:35:55', 9),
(25, 'Mahrab Hasan ', 'mahrab256@gmail.com', '01923967120', 'CSE', '4th', 'Male', '1783935498_Mahrab_Hasan_.png', '2026-07-13 09:38:18', 9),
(26, 'Salman Sady', 'salmadmuktadir92459@gmail.com', '01329031092', 'CSE', '5th', 'Male', '1783942460_Salman_Sady.jpg', '2026-07-13 11:34:20', 9),
(27, 'Ryomen Sukuna', 'sukunasummonmohoraga@gmail.com', '090-4679-3197', 'M.M', '9th', 'Male', '1784007880_Ryomen_Sukuna.jpeg', '2026-07-14 05:09:57', 9),
(28, 'Miyamoto Musashi', 'musashinokamifujiwara@gmail.com', '090-9137-6482', 'M.M', '9th', 'Male', '1784007164_Miyamoto_Musashi.jpg', '2026-07-14 05:32:44', 9),
(29, 'Thorfinn Karlsefni', 'vikersthorfinn@gmail.com', '090-1673-7139', 'M.M', '8th', 'Male', '1784007757_Thorfinn_Karlsefni.jpg', '2026-07-14 05:32:47', 9),
(30, 'Guts satoshi', 'theblackswordsman@gmail.com', '090-1973-8264', 'M.M', '10th', 'Male', '1784008148_Guts_satoshi.jpg', '2026-07-14 05:49:08', 9),
(31, 'Hanzo Hasashi', 'scorpeanbrutality@gmail.com', '090-8096-0097', 'M.M', '12th', 'Male', '1784008931_Hanzo_Hasashi.jpg', '2026-07-14 06:02:11', 9),
(32, 'Bi-Han', 'noobsaibotfatal@gmail.com', '090-8016-0147', 'M.M', '12th', 'Male', '1784009212_Bi-Han.jpeg', '2026-07-14 06:04:48', 9),
(33, 'Kuai Liang', 'subzeroreconnect@gmail.com', '090-9674-1947', 'M.M', '12th', 'Male', '1784009433_Kuai_Liang.jpg', '2026-07-14 06:10:33', 9),
(35, 'Intersteller void', 'void@gmail.com', '01973416258', 'EEE', '12th', 'Male', '1784171277_Intersteller_void.png', '2026-07-16 03:07:57', 9),
(36, 'Satoshi Nakamoto', 'nakaotocoin@gmail.com', '090-6274-5568', 'AGENT', 'OUT OF MATRIX OR MAY', 'Male', '1784172921_Satoshi_Nakamoto.png', '2026-07-16 03:35:22', 9),
(37, 'Hinata Haguya', 'haguyaclan@gmail.com', '090-9473-8219', 'M.M', '8th', 'Female', '1784174337_Hinata_Haguya.jpg', '2026-07-16 03:58:57', 9),
(38, 'Ryan Gosling ', 'goslingbladerunner@gmail.com', '(415) 297-7419', 'CSE', '12th', 'Male', '1784174627_Ryan_Gosling_.jpg', '2026-07-16 04:03:47', 9),
(39, 'Fubuki', 'fubuki@gmail.com', '963258741', 'M.M', '11th', 'Female', '1786024910_Fubuki.webp', '2026-08-06 14:01:50', 9);

CREATE TABLE `userss` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `otp` varchar(6) DEFAULT NULL,
  `otp_expire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `userss` (`id`, `name`, `email`, `password`, `created_at`, `otp`, `otp_expire`) 
VALUES
(1, 'personkd6_3', 'minecomputer2024@gmail.com', '$2y$10$kIV7KKj2PPlWfb2l69zKHe0HVtvijNk/onYb3BSJgYla9ozx4wqCm', '2026-07-12 15:13:29', NULL, NULL),
(9, 'Ahmad Abdullah', 'sefatajbinkhidir@gmail.com', '$2y$10$If6MRUnnTavb7lwo0bZntuNGFZA6NV5t/qJHONiDCsWCc97I6Fh9u', '2026-07-12 15:13:29', NULL, NULL),
(10, 'MD.Arif Hossain', 'mhossainarif@agni.com', '$2y$10$FGtTsl44TQPFRxbdhL/HyeMh8JcZD99nou4Gv7zP8vwDDhCWIp.rm', '2026-07-12 15:13:29', NULL, NULL),
(11, 'Neon', 'worldneon8@gmail.com', '$2y$10$4ocP8to.0jfG1fqVLfNyQ.TUJ9TKHeoQh7s5/oIM.M.gGC0IvWPdi', '2026-07-12 15:13:29', '431894', '2026-07-14 05:59:01'),
(12, 'Mahrab26', 'mahrabh256@gmail.com', '$2y$10$gWiCM.aRNeGU0cVDdjCWXujVAgCuOkqaWSAowDcXj5WCnb6nDhRV.', '2026-07-12 15:13:29', NULL, NULL),
(13, 'personkd6_3', 'tumiamarpriyotoma2026@gmail.com', '$2y$10$bLk./9MCCil/N2Iv5mUys.CZtnm8EP6TdOZH6Wa1m8Xwa1O0sWFdC', '2026-07-12 15:13:29', NULL, NULL),
(16, 'personkd6_3', 'cyberrunner04@gmail.com', '$2y$10$xuYacNEuZXL3oUTCrhXaz.i8EEl7qhHfxNcQVF.2H4V4xHOzB6KyW', '2026-07-12 15:13:29', NULL, NULL),
(17, 'Sefataj', 'personkd63@gmail.com', '$2y$10$PZDETpED9VSG3hyHRJlTquKS3AlAMmxQ2nOjAEtlUJ7O4RDDqkHp2', '2026-07-12 15:21:24', '168185', '2026-07-14 05:44:17');

ALTER TABLE `persons`
ADD PRIMARY KEY (`id`);

ALTER TABLE `userss`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `persons`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

ALTER TABLE `userss`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;