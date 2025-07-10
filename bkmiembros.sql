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

/*Data for the table `miembros` */

insert  into `miembros`(`id`,`nombre`,`apellido`,`direccion`,`telefono`,`email`,`fecha_nacimiento`,`foto_path`,`codigo_acceso_numerico`,`plantilla_huella`,`acceso_habilitado`,`sucursal_id`,`created_at`,`updated_at`) values 
(1,'EDGARDO DANIEL','MEZA FELITAS','asdasdas','0961331213','D@D.COM','1985-04-26','fotos_miembros/3CbfJa1WP1tlV2eYnpBOb3iKKsmlwgngNd171NN5.jpg','641957',NULL,1,1,'2025-07-08 16:35:21','2025-07-08 16:37:07'),
(2,'dia','dia','jndnsa','222222','dia@dia.com','1980-04-20','fotos_miembros/VbtUSc6gp0g7z8C1oTukGEM2WQXSHnhtZW5ERkAi.png','389297',NULL,1,2,'2025-07-08 16:38:56','2025-07-08 16:38:56');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
