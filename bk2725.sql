/*
SQLyog Community v13.2.0 (64 bit)
MySQL - 9.1.0 : Database - gimnasio_db
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`gimnasio_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `gimnasio_db`;

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

/*Table structure for table `categorias_producto` */

DROP TABLE IF EXISTS `categorias_producto`;

CREATE TABLE `categorias_producto` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `categorias_producto` */

insert  into `categorias_producto`(`id`,`nombre`,`descripcion`,`created_at`,`updated_at`) values 
(1,'Suplementos','Productos nutricionales y suplementos deportivos','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(2,'Ropa','Ropa deportiva y accesorios','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(3,'Bebidas','Bebidas energéticas e hidratantes','2025-07-03 11:03:28','2025-07-03 11:03:28');

/*Table structure for table `dispositivos_control_acceso` */

DROP TABLE IF EXISTS `dispositivos_control_acceso`;

CREATE TABLE `dispositivos_control_acceso` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sucursal_id` bigint unsigned NOT NULL,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_dispositivo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identificador_dispositivo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ej: activo, inactivo, error, mantenimiento',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mac_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `puerto` int DEFAULT NULL,
  `configuracion_adicional` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dispositivos_control_acceso_identificador_dispositivo_unique` (`identificador_dispositivo`),
  UNIQUE KEY `dispositivos_control_acceso_mac_address_unique` (`mac_address`),
  KEY `dispositivos_control_acceso_sucursal_id_foreign` (`sucursal_id`),
  CONSTRAINT `dispositivos_control_acceso_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `dispositivos_control_acceso` */

/*Table structure for table `eventos_acceso` */

DROP TABLE IF EXISTS `eventos_acceso`;

CREATE TABLE `eventos_acceso` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `miembro_id` bigint unsigned DEFAULT NULL,
  `dispositivo_control_acceso_id` bigint unsigned NOT NULL,
  `sucursal_id` bigint unsigned NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `tipo_acceso_intentado` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resultado` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `eventos_acceso_miembro_id_foreign` (`miembro_id`),
  KEY `eventos_acceso_dispositivo_control_acceso_id_foreign` (`dispositivo_control_acceso_id`),
  KEY `eventos_acceso_sucursal_id_foreign` (`sucursal_id`),
  CONSTRAINT `eventos_acceso_dispositivo_control_acceso_id_foreign` FOREIGN KEY (`dispositivo_control_acceso_id`) REFERENCES `dispositivos_control_acceso` (`id`) ON DELETE CASCADE,
  CONSTRAINT `eventos_acceso_miembro_id_foreign` FOREIGN KEY (`miembro_id`) REFERENCES `miembros` (`id`) ON DELETE SET NULL,
  CONSTRAINT `eventos_acceso_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `eventos_acceso` */

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `job_batches` */

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `job_batches` */

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

/*Table structure for table `membresias` */

DROP TABLE IF EXISTS `membresias`;

CREATE TABLE `membresias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `miembro_id` bigint unsigned NOT NULL,
  `tipo_membresia_id` bigint unsigned NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `renovacion_automatica` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `membresias_miembro_id_foreign` (`miembro_id`),
  KEY `membresias_tipo_membresia_id_foreign` (`tipo_membresia_id`),
  CONSTRAINT `membresias_miembro_id_foreign` FOREIGN KEY (`miembro_id`) REFERENCES `miembros` (`id`) ON DELETE CASCADE,
  CONSTRAINT `membresias_tipo_membresia_id_foreign` FOREIGN KEY (`tipo_membresia_id`) REFERENCES `tipos_membresia` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `membresias` */

/*Table structure for table `miembros` */

DROP TABLE IF EXISTS `miembros`;

CREATE TABLE `miembros` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `foto_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo_acceso_numerico` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plantilla_huella` blob,
  `sucursal_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `miembros_email_unique` (`email`),
  KEY `miembros_sucursal_id_foreign` (`sucursal_id`),
  CONSTRAINT `miembros_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `miembros` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_06_03_154117_create_sucursales_table',1),
(5,'2025_06_03_154150_create_tipos_membresia_table',1),
(6,'2025_06_03_154214_create_miembros_table',1),
(7,'2025_06_03_154243_create_membresias_table',1),
(8,'2025_06_03_154306_create_dispositivos_control_acceso_table',1),
(9,'2025_06_03_154334_create_eventos_acceso_table',1),
(10,'2025_06_03_154406_create_pagos_table',1),
(11,'2025_06_03_154435_create_categorias_producto_table',1),
(12,'2025_06_03_154502_create_productos_table',1),
(13,'2025_06_03_154528_create_ventas_table',1),
(14,'2025_06_03_154554_create_ventas_detalle_table',1),
(15,'2025_07_02_144409_add_sucursal_foreign_key_to_users_table',1),
(16,'2025_07_02_151458_create_permission_tables',1),
(17,'2025_07_02_153041_modify_dispositivos_control_acceso_table',1);

/*Table structure for table `model_has_permissions` */

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_permissions` */

/*Table structure for table `model_has_roles` */

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_roles` */

insert  into `model_has_roles`(`role_id`,`model_type`,`model_id`) values 
(1,'App\\Models\\User',1),
(2,'App\\Models\\User',2);

/*Table structure for table `pagos` */

DROP TABLE IF EXISTS `pagos`;

CREATE TABLE `pagos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `miembro_id` bigint unsigned NOT NULL,
  `membresia_id` bigint unsigned DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_pago` date NOT NULL,
  `metodo_pago` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referencia_pago` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `factura_generada` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pagos_miembro_id_foreign` (`miembro_id`),
  KEY `pagos_membresia_id_foreign` (`membresia_id`),
  CONSTRAINT `pagos_membresia_id_foreign` FOREIGN KEY (`membresia_id`) REFERENCES `membresias` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pagos_miembro_id_foreign` FOREIGN KEY (`miembro_id`) REFERENCES `miembros` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `pagos` */

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `permissions` */

insert  into `permissions`(`id`,`name`,`guard_name`,`created_at`,`updated_at`) values 
(1,'ver lista miembros','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(2,'crear miembro','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(3,'editar miembro','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(4,'eliminar miembro','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(5,'ver miembro','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(6,'gestionar membresias miembro','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(7,'ver lista tipos membresia','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(8,'crear tipo membresia','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(9,'editar tipo membresia','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(10,'eliminar tipo membresia','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(11,'ver lista sucursales','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(12,'crear sucursal','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(13,'editar sucursal','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(14,'eliminar sucursal','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(15,'ver lista pagos','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(16,'registrar pago','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(17,'gestionar facturacion','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(18,'registrar acceso manual','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(19,'ver log accesos','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(20,'gestionar dispositivos acceso','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(21,'ver panel monitoreo dispositivos','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(22,'ver lista usuarios','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(23,'crear usuario','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(24,'editar usuario','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(25,'eliminar usuario','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(26,'asignar roles','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(27,'ver lista roles','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(28,'crear rol','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(29,'editar rol','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(30,'eliminar rol','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(31,'asignar permisos a rol','web','2025-07-03 11:03:28','2025-07-03 11:03:28'),
(32,'ver dashboard general','web','2025-07-03 11:03:28','2025-07-03 11:03:28');

/*Table structure for table `productos` */

DROP TABLE IF EXISTS `productos`;

CREATE TABLE `productos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `precio_venta` decimal(10,2) NOT NULL,
  `stock_actual` int NOT NULL DEFAULT '0',
  `categoria_producto_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `productos_categoria_producto_id_foreign` (`categoria_producto_id`),
  CONSTRAINT `productos_categoria_producto_id_foreign` FOREIGN KEY (`categoria_producto_id`) REFERENCES `categorias_producto` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `productos` */

/*Table structure for table `role_has_permissions` */

DROP TABLE IF EXISTS `role_has_permissions`;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `role_has_permissions` */

insert  into `role_has_permissions`(`permission_id`,`role_id`) values 
(1,1),
(2,1),
(3,1),
(4,1),
(5,1),
(6,1),
(7,1),
(8,1),
(9,1),
(10,1),
(11,1),
(12,1),
(13,1),
(14,1),
(15,1),
(16,1),
(17,1),
(18,1),
(19,1),
(20,1),
(21,1),
(22,1),
(23,1),
(24,1),
(25,1),
(26,1),
(27,1),
(28,1),
(29,1),
(30,1),
(31,1),
(32,1),
(1,2),
(2,2),
(3,2),
(5,2),
(6,2),
(7,2),
(15,2),
(16,2),
(18,2),
(19,2),
(32,2);

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`guard_name`,`created_at`,`updated_at`) values 
(1,'Admin','web','2025-07-03 11:03:29','2025-07-03 11:03:29'),
(2,'Recepcionista','web','2025-07-03 11:03:29','2025-07-03 11:03:29'),
(3,'Instructor','web','2025-07-03 11:03:29','2025-07-03 11:03:29');

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

/*Table structure for table `sucursales` */

DROP TABLE IF EXISTS `sucursales`;

CREATE TABLE `sucursales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `horario_atencion` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sucursales` */

insert  into `sucursales`(`id`,`nombre`,`direccion`,`telefono`,`horario_atencion`,`logo_path`,`created_at`,`updated_at`) values 
(1,'Sucursal Central','Av. Principal 123, Ciudad','555-1234','L-V 06:00-22:00, S 08:00-20:00',NULL,'2025-07-03 11:03:27','2025-07-03 11:03:27'),
(2,'Sucursal Norte','Calle Falsa 456, Sector Norte','555-5678','L-V 07:00-21:00, S 09:00-18:00',NULL,'2025-07-03 11:03:28','2025-07-03 11:03:28');

/*Table structure for table `tipos_membresia` */

DROP TABLE IF EXISTS `tipos_membresia`;

CREATE TABLE `tipos_membresia` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `duracion_dias` int NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `acceso_multisucursal` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tipos_membresia` */

insert  into `tipos_membresia`(`id`,`nombre`,`descripcion`,`duracion_dias`,`precio`,`acceso_multisucursal`,`created_at`,`updated_at`) values 
(1,'Mensual','Acceso completo por 30 días. Área de pesas y cardio.',30,130000.00,0,'2025-07-03 11:03:28','2025-07-03 11:03:28'),
(2,'Trimestral','Acceso completo por 90 días. Descuento aplicado.',90,350000.00,0,'2025-07-03 11:03:28','2025-07-03 11:03:28'),
(3,'Anual VIP','Acceso completo por 365 días. Acceso a todas las sucursales y clases especiales.',365,1200000.00,1,'2025-07-03 11:03:28','2025-07-03 11:03:28');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sucursal_id` bigint unsigned DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `foto_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_sucursal_id_foreign` (`sucursal_id`),
  CONSTRAINT `users_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`apellido`,`email`,`email_verified_at`,`password`,`sucursal_id`,`activo`,`foto_path`,`remember_token`,`created_at`,`updated_at`) values 
(1,'Admin','Gimnasio','admin@gim.com','2025-07-03 11:03:29','$2y$12$ORC7a85ZXYSxreKcMJzl0uGbyurhlYnk5ZrlLVMS8mbkNLW3OzbCe',1,1,NULL,NULL,'2025-07-03 11:03:29','2025-07-03 11:03:29'),
(2,'Recepcionista','Gimnasio','recepcion@gim.com','2025-07-03 11:03:30','$2y$12$7efTlVUTZitTYIGHIYZ/bu1yZzKjsXCaIMWgHKHisJrEHVIBpkqvO',1,1,NULL,NULL,'2025-07-03 11:03:30','2025-07-03 11:03:30');

/*Table structure for table `ventas` */

DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `miembro_id` bigint unsigned DEFAULT NULL,
  `fecha_venta` datetime NOT NULL,
  `total_venta` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ventas_miembro_id_foreign` (`miembro_id`),
  CONSTRAINT `ventas_miembro_id_foreign` FOREIGN KEY (`miembro_id`) REFERENCES `miembros` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `ventas` */

/*Table structure for table `ventas_detalle` */

DROP TABLE IF EXISTS `ventas_detalle`;

CREATE TABLE `ventas_detalle` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `venta_id` bigint unsigned NOT NULL,
  `producto_id` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario_en_venta` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ventas_detalle_venta_id_foreign` (`venta_id`),
  KEY `ventas_detalle_producto_id_foreign` (`producto_id`),
  CONSTRAINT `ventas_detalle_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ventas_detalle_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `ventas_detalle` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
