-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2021 at 06:29 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phone_validator`
--

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `country_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `iso3` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `phone_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country_name`, `iso3`, `phone_code`) VALUES
(1, 'Afghanistan', 'AFG', 93),
(2, 'Albania', 'ALB', 355),
(3, 'Algeria', 'DZA', 213),
(4, 'American Samoa', 'ASM', 1684),
(5, 'Andorra', 'AND', 376),
(6, 'Angola', 'AGO', 244),
(7, 'Anguilla', 'AIA', 1264),
(8, 'Antarctica', 'ATA', 672),
(9, 'Antigua and Barbuda', 'ATG', 1268),
(10, 'Argentina', 'ARG', 54),
(11, 'Armenia', 'ARM', 374),
(12, 'Aruba', 'ABW', 297),
(13, 'Australia', 'AUS', 61),
(14, 'Austria', 'AUT', 43),
(15, 'Azerbaijan', 'AZE', 994),
(16, 'Bahamas', 'BHS', 1242),
(17, 'Bahrain', 'BHR', 973),
(18, 'Bangladesh', 'BGD', 880),
(19, 'Barbados', 'BRB', 1246),
(20, 'Belarus', 'BLR', 375),
(21, 'Belgium', 'BEL', 32),
(22, 'Belize', 'BLZ', 501),
(23, 'Benin', 'BEN', 229),
(24, 'Bermuda', 'BMU', 1441),
(25, 'Bhutan', 'BTN', 975),
(26, 'Bolivia', 'BOL', 591),
(27, 'Bosnia and Herzegovina', 'BIH', 387),
(28, 'Botswana', 'BWA', 267),
(29, 'Brazil', 'BRA', 55),
(30, 'British Indian Ocean Territory', 'IOT', 246),
(31, 'British Virgin Islands', 'VGB', 1284),
(32, 'Brunei', 'BRN', 673),
(33, 'Bulgaria', 'BGR', 359),
(34, 'Burkina Faso', 'BFA', 226),
(35, 'Burundi', 'BDI', 257),
(36, 'Cambodia', 'KHM', 855),
(37, 'Cameroon', 'CMR', 237),
(38, 'Canada', 'CAN', 1),
(39, 'Cape Verde', 'CPV', 238),
(40, 'Cayman Islands', 'CYM', 1345),
(41, 'Central African Republic', 'CAF', 236),
(42, 'Chad', 'TCD', 235),
(43, 'Chile', 'CHL', 56),
(44, 'China', 'CHN', 86),
(45, 'Christmas Island', 'CXR', 61),
(46, 'Cocos Islands', 'CCK', 61),
(47, 'Colombia', 'COL', 57),
(48, 'Comoros', 'COM', 269),
(49, 'Cook Islands', 'COK', 682),
(50, 'Costa Rica', 'CRI', 506),
(51, 'Croatia', 'HRV', 385),
(52, 'Cuba', 'CUB', 53),
(53, 'Curacao', 'CUW', 599),
(54, 'Cyprus', 'CYP', 357),
(55, 'Czech Republic', 'CZE', 420),
(56, 'Democratic Republic of the Congo', 'COD', 243),
(57, 'Denmark', 'DNK', 45),
(58, 'Djibouti', 'DJI', 253),
(59, 'Dominica', 'DMA', 1767),
(60, 'Dominican Republic', 'DOM', 1809),
(61, 'East Timor', 'TLS', 670),
(62, 'Ecuador', 'ECU', 593),
(63, 'Egypt', 'EGY', 20),
(64, 'El Salvador', 'SLV', 503),
(65, 'Equatorial Guinea', 'GNQ', 240),
(66, 'Eritrea', 'ERI', 291),
(67, 'Estonia', 'EST', 372),
(68, 'Ethiopia', 'ETH', 251),
(69, 'Falkland Islands', 'FLK', 500),
(70, 'Faroe Islands', 'FRO', 298),
(71, 'Fiji', 'FJI', 679),
(72, 'Finland', 'FIN', 358),
(73, 'France', 'FRA', 33),
(74, 'French Polynesia', 'PYF', 689),
(75, 'Gabon', 'GAB', 241),
(76, 'Gambia', 'GMB', 220),
(77, 'Georgia', 'GEO', 995),
(78, 'Germany', 'DEU', 49),
(79, 'Ghana', 'GHA', 233),
(80, 'Gibraltar', 'GIB', 350),
(81, 'Greece', 'GRC', 30),
(82, 'Greenland', 'GRL', 299),
(83, 'Grenada', 'GRD', 1473),
(84, 'Guam', 'GUM', 1671),
(85, 'Guatemala', 'GTM', 502),
(86, 'Guernsey', 'GGY', 441481),
(87, 'Guinea', 'GIN', 224),
(88, 'Guinea-Bissau', 'GNB', 245),
(89, 'Guyana', 'GUY', 592),
(90, 'Haiti', 'HTI', 509),
(91, 'Honduras', 'HND', 504),
(92, 'Hong Kong', 'HKG', 852),
(93, 'Hungary', 'HUN', 36),
(94, 'Iceland', 'ISL', 354),
(95, 'India', 'IND', 91),
(96, 'Indonesia', 'IDN', 62),
(97, 'Iran', 'IRN', 98),
(98, 'Iraq', 'IRQ', 964),
(99, 'Ireland', 'IRL', 353),
(100, 'Isle of Man', 'IMN', 441624),
(101, 'Israel', 'ISR', 972),
(102, 'Italy', 'ITA', 39),
(103, 'Ivory Coast', 'CIV', 225),
(104, 'Jamaica', 'JAM', 1876),
(105, 'Japan', 'JPN', 81),
(106, 'Jersey', 'JEY', 441534),
(107, 'Jordan', 'JOR', 962),
(108, 'Kazakhstan', 'KAZ', 7),
(109, 'Kenya', 'KEN', 254),
(110, 'Kiribati', 'KIR', 686),
(111, 'Kosovo', 'XKX', 383),
(112, 'Kuwait', 'KWT', 965),
(113, 'Kyrgyzstan', 'KGZ', 996),
(114, 'Laos', 'LAO', 856),
(115, 'Latvia', 'LVA', 371),
(116, 'Lebanon', 'LBN', 961),
(117, 'Lesotho', 'LSO', 266),
(118, 'Liberia', 'LBR', 231),
(119, 'Libya', 'LBY', 218),
(120, 'Liechtenstein', 'LIE', 423),
(121, 'Lithuania', 'LTU', 370),
(122, 'Luxembourg', 'LUX', 352),
(123, 'Macau', 'MAC', 853),
(124, 'Macedonia', 'MKD', 389),
(125, 'Madagascar', 'MDG', 261),
(126, 'Malawi', 'MWI', 265),
(127, 'Malaysia', 'MYS', 60),
(128, 'Maldives', 'MDV', 960),
(129, 'Mali', 'MLI', 223),
(130, 'Malta', 'MLT', 356),
(131, 'Marshall Islands', 'MHL', 692),
(132, 'Mauritania', 'MRT', 222),
(133, 'Mauritius', 'MUS', 230),
(134, 'Mayotte', 'MYT', 262),
(135, 'Mexico', 'MEX', 52),
(136, 'Micronesia', 'FSM', 691),
(137, 'Moldova', 'MDA', 373),
(138, 'Monaco', 'MCO', 377),
(139, 'Mongolia', 'MNG', 976),
(140, 'Montenegro', 'MNE', 382),
(141, 'Montserrat', 'MSR', 1664),
(142, 'Morocco', 'MAR', 212),
(143, 'Mozambique', 'MOZ', 258),
(144, 'Myanmar', 'MMR', 95),
(145, 'Namibia', 'NAM', 264),
(146, 'Nauru', 'NRU', 674),
(147, 'Nepal', 'NPL', 977),
(148, 'Netherlands', 'NLD', 31),
(149, 'Netherlands Antilles', 'ANT', 599),
(150, 'New Caledonia', 'NCL', 687),
(151, 'New Zealand', 'NZL', 64),
(152, 'Nicaragua', 'NIC', 505),
(153, 'Niger', 'NER', 227),
(154, 'Nigeria', 'NGA', 234),
(155, 'Niue', 'NIU', 683),
(156, 'North Korea', 'PRK', 850),
(157, 'Northern Mariana Islands', 'MNP', 1670),
(158, 'Norway', 'NOR', 47),
(159, 'Oman', 'OMN', 968),
(160, 'Pakistan', 'PAK', 92),
(161, 'Palau', 'PLW', 680),
(162, 'Palestine', 'PSE', 970),
(163, 'Panama', 'PAN', 507),
(164, 'Papua New Guinea', 'PNG', 675),
(165, 'Paraguay', 'PRY', 595),
(166, 'Peru', 'PER', 51),
(167, 'Philippines', 'PHL', 63),
(168, 'Pitcairn', 'PCN', 64),
(169, 'Poland', 'POL', 48),
(170, 'Portugal', 'PRT', 351),
(171, 'Puerto Rico', 'PRI', 1787),
(172, 'Qatar', 'QAT', 974),
(173, 'Republic of the Congo', 'COG', 242),
(174, 'Reunion', 'REU', 262),
(175, 'Romania', 'ROU', 40),
(176, 'Russia', 'RUS', 7),
(177, 'Rwanda', 'RWA', 250),
(178, 'Saint Barthelemy', 'BLM', 590),
(179, 'Saint Helena', 'SHN', 290),
(180, 'Saint Kitts and Nevis', 'KNA', 1869),
(181, 'Saint Lucia', 'LCA', 1758),
(182, 'Saint Martin', 'MAF', 590),
(183, 'Saint Pierre and Miquelon', 'SPM', 508),
(184, 'Saint Vincent and the Grenadines', 'VCT', 1784),
(185, 'Samoa', 'WSM', 685),
(186, 'San Marino', 'SMR', 378),
(187, 'Sao Tome and Principe', 'STP', 239),
(188, 'Saudi Arabia', 'SAU', 966),
(189, 'Senegal', 'SEN', 221),
(190, 'Serbia', 'SRB', 381),
(191, 'Seychelles', 'SYC', 248),
(192, 'Sierra Leone', 'SLE', 232),
(193, 'Singapore', 'SGP', 65),
(194, 'Sint Maarten', 'SXM', 1721),
(195, 'Slovakia', 'SVK', 421),
(196, 'Slovenia', 'SVN', 386),
(197, 'Solomon Islands', 'SLB', 677),
(198, 'Somalia', 'SOM', 252),
(199, 'South Africa', 'ZAF', 27),
(200, 'South Korea', 'KOR', 82),
(201, 'South Sudan', 'SSD', 211),
(202, 'Spain', 'ESP', 34),
(203, 'Sri Lanka', 'LKA', 94),
(204, 'Sudan', 'SDN', 249),
(205, 'Suriname', 'SUR', 597),
(206, 'Svalbard and Jan Mayen', 'SJM', 47),
(207, 'Swaziland', 'SWZ', 268),
(208, 'Sweden', 'SWE', 46),
(209, 'Switzerland', 'CHE', 41),
(210, 'Syria', 'SYR', 963),
(211, 'Taiwan', 'TWN', 886),
(212, 'Tajikistan', 'TJK', 992),
(213, 'Tanzania', 'TZA', 255),
(214, 'Thailand', 'THA', 66),
(215, 'Togo', 'TGO', 228),
(216, 'Tokelau', 'TKL', 690),
(217, 'Tonga', 'TON', 676),
(218, 'Trinidad and Tobago', 'TTO', 1868),
(219, 'Tunisia', 'TUN', 216),
(220, 'Turkey', 'TUR', 90),
(221, 'Turkmenistan', 'TKM', 993),
(222, 'Turks and Caicos Islands', 'TCA', 1649),
(223, 'Tuvalu', 'TUV', 688),
(224, 'U.S. Virgin Islands', 'VIR', 1340),
(225, 'Uganda', 'UGA', 256),
(226, 'Ukraine', 'UKR', 380),
(227, 'United Arab Emirates', 'ARE', 971),
(228, 'United Kingdom', 'GBR', 44),
(229, 'United States', 'USA', 1),
(230, 'Uruguay', 'URY', 598),
(231, 'Uzbekistan', 'UZB', 998),
(232, 'Vanuatu', 'VUT', 678),
(233, 'Vatican', 'VAT', 379),
(234, 'Venezuela', 'VEN', 58),
(235, 'Vietnam', 'VNM', 84),
(236, 'Wallis and Futuna', 'WLF', 681),
(237, 'Western Sahara', 'ESH', 212),
(238, 'Yemen', 'YEM', 967),
(239, 'Zambia', 'ZMB', 260),
(240, 'Zimbabwe', 'ZWE', 263);

-- --------------------------------------------------------

--
-- Table structure for table `phones`
--

CREATE TABLE `phones` (
  `id` bigint(20) NOT NULL,
  `country_id` int(11) NOT NULL,
  `phone_number` bigint(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phone_confirmations`
--

CREATE TABLE `phone_confirmations` (
  `id` bigint(20) NOT NULL,
  `transaction_id` bigint(20) NOT NULL,
  `validation_code` int(11) NOT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `confirmed_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phone_confirmation_attempts`
--

CREATE TABLE `phone_confirmation_attempts` (
  `id` bigint(20) NOT NULL,
  `phone_confirmation_id` bigint(20) NOT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) NOT NULL,
  `user_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone_id` bigint(20) NOT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `updated_at` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_5D66EBADD910F5E2` (`country_name`);

--
-- Indexes for table `phones`
--
ALTER TABLE `phones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_E3282EF56B01BC5B` (`phone_number`);

--
-- Indexes for table `phone_confirmations`
--
ALTER TABLE `phone_confirmations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phone_confirmation_attempts`
--
ALTER TABLE `phone_confirmation_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `phones`
--
ALTER TABLE `phones`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phone_confirmations`
--
ALTER TABLE `phone_confirmations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phone_confirmation_attempts`
--
ALTER TABLE `phone_confirmation_attempts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
