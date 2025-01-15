-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 09-12-2024 a las 13:23:35
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mvc_pdo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idFiscal` varchar(9) NOT NULL COMMENT 'CIF DNI ESPANYOLES',
  `contact_name` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_phone_number` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_address` varchar(255) DEFAULT NULL,
  `company_phone_number` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clients`
--

INSERT INTO `clients` (`id`, `idFiscal`, `contact_name`, `contact_email`, `contact_phone_number`, `company_name`, `company_address`, `company_phone_number`) VALUES
(1, '23', 'ad', 'ads', '12', 'ads', 'sad', NULL),
(2, '34', 'prueba', 'prueba', '123', 'prubea', 'calle', NULL),
(3, '12', 'sad', 'sad', '123', 'sad', 'fdf', '665');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Abierto',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'JEFE DE PROYECTO',
  `client_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `deadline`, `status`, `user_id`, `client_id`) VALUES
(1, 'Proyecto Prueba', 'Muchas cosas', '2023-11-11', 'Abierto', 1, NULL),
(2, 'Proyecto Prueba2 ', 'Demasiado trabajo', '2024-01-11', 'Abierto', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `task_status` varchar(255) NOT NULL DEFAULT 'Abierto',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'RESPONSABLE DE TAREA',
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `usuario` varchar(100) NOT NULL COMMENT 'Para el login equivalente a nick'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

/*INSERT INTO `users` (`id`, `name`, `email`, `password`, `usuario`) VALUES
(1, 'Ana Lopez', 'ana@gmail.com', 'ana', 'ana'),
(2, 'Luis Perez', 'luis@gmail.com', 'luis', 'luis'),
(4, 'Maria Lopez', 'maria@gmail.com', 'maria', 'maria'),
(11, 'Admin Mola', 'admin@gmail.com', 'admin', 'admin');*/

INSERT INTO `users` (`id`, `name`, `email`, `password`, `usuario`) VALUES
(1, 'Ana Lopez', 'ana@gmail.com', '$2y$10$w4HeBV2bvsRvlRG7Om0vweL6UZvGRtfjw0NAT0b2M/Hfu45HSvWge' ,'ana'),
(2, 'Luis Perez', 'luis@gmail.com', '$2y$10$NhMucZgqKKRQheq7RlToA.RCddDONxmyFBHOwQRUY6qYtXKs1co0W' ,'luis'),
(4, 'Maria Lopez', 'maria@gmail.com', '$2y$10$gbZfp2YBoxyJT9cG4vBmueG1OYWxXk5Z5x7W06BhWR6uPeXjmKIF2' ,'maria'),
(11, 'Admin Mola', 'admin@gmail.com', '$2y$10$dIBvy.QseqFNzP7GErgSF.P7coKdx/onIiO4QNiiLnbwYyPMxOUFm' ,'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clients_contact_email_unique` (`contact_email`),
  ADD UNIQUE KEY `users_cif_dni` (`idFiscal`);

--
-- Indices de la tabla `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_client_id_foreign` (`client_id`) USING BTREE,
  ADD KEY `projects_user_id_foreign` (`user_id`) USING BTREE;

--
-- Indices de la tabla `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_client_id_foreign` (`client_id`) USING BTREE,
  ADD KEY `tasks_project_id_foreign` (`project_id`) USING BTREE,
  ADD KEY `tasks_user_id_foreign` (`user_id`) USING BTREE;

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_UN` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_FK` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `projects_FK_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_FK` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_FK_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_FK_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
