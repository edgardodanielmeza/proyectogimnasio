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

/*Data for the table `pagos` */

insert  into `pagos`(`id`,`miembro_id`,`membresia_id`,`monto`,`fecha_pago`,`metodo_pago`,`referencia_pago`,`factura_generada`,`created_at`,`updated_at`) values 
(1,1,1,130000.00,'2025-07-08','Inscripción Inicial','Pago inicial: Mensual',0,'2025-07-08 16:35:21','2025-07-08 16:35:21'),
(2,2,2,20000.00,'2025-07-08','Inscripción Inicial','Pago inicial: DIARIA',0,'2025-07-08 16:38:57','2025-07-08 16:38:57');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
