-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: assalam
-- ------------------------------------------------------
-- Server version	8.0.46

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_designs`
--

DROP TABLE IF EXISTS `custom_designs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_designs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Sofa & Kursi Tamu Mewah',
  `length_cm` int NOT NULL DEFAULT '180',
  `width_cm` int NOT NULL DEFAULT '80',
  `height_cm` int NOT NULL DEFAULT '75',
  `wood_material` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Kayu Jati Perhutani (Grade A)',
  `color_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Amber Gold',
  `color_hex` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#d97706',
  `tone_percent` int NOT NULL DEFAULT '100',
  `sketch_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_designs_order_id_foreign` (`order_id`),
  KEY `custom_designs_product_id_foreign` (`product_id`),
  CONSTRAINT `custom_designs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `custom_designs_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `produks` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_designs`
--

LOCK TABLES `custom_designs` WRITE;
/*!40000 ALTER TABLE `custom_designs` DISABLE KEYS */;
INSERT INTO `custom_designs` VALUES (3,16,NULL,'Sofa & Kursi Tamu Mewah',180,80,75,'Kayu Jati Perhutani (Grade A)','Amber Gold','#d97706',100,NULL,'Model sofa minimalis 3 seater dengan kain pelapis fabric hangat.','2026-09-14 19:14:59','2026-09-14 19:14:59'),(4,17,NULL,'Meja Minimalis Modern',160,90,78,'Kayu Mahoni Oven Premium','Natural Oak','#c85a32',90,NULL,'Include 4 kursi makan dudukan busa.','2026-09-14 19:14:59','2026-09-14 19:14:59'),(5,18,NULL,'Pintu Rumah & Gebyok',210,90,4,'Kayu Jati Perhutani (Grade A)','Deep Mahogany','#4a2c2a',100,NULL,'Pintu kupu tarung 2 daun full ukir klasik.','2026-09-14 19:14:59','2026-09-14 19:14:59');
/*!40000 ALTER TABLE `custom_designs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_05_21_000001_create_wa_sessions_table',1),(5,'2026_05_21_000002_create_wa_messages_table',1),(6,'2026_05_21_000003_create_wa_contacts_table',1),(7,'2026_05_22_000001_add_ack_to_wa_messages',1),(8,'2026_05_22_000002_add_deleted_at_to_wa_messages',1),(9,'2026_08_12_004717_create_settings_table',1),(10,'2026_08_12_004917_create_shipping_costs_table',1),(11,'2026_08_15_001919_create_produks_table',1),(12,'2026_08_15_053023_create_studio_settings_table',1),(13,'2026_08_16_014701_add_role_to_users_table',1),(14,'2026_08_18_000001_create_orders_table',1),(15,'2026_08_18_000002_create_custom_designs_table',1),(16,'2026_08_18_000003_create_order_progresses_table',1),(17,'2026_08_18_000004_create_order_items_table',1),(18,'2026_08_19_000001_create_wa_templates_table',1),(19,'2026_08_19_000002_create_wa_message_logs_table',1),(20,'2026_09_05_000001_add_username_to_users_table',1),(21,'2026_09_05_000002_add_product_id_to_custom_designs_table',1),(22,'2026_09_05_000003_add_rejection_and_order_status_to_orders_table',1),(23,'2026_09_06_000004_add_profile_photo_to_users_table',1),(24,'2026_09_10_000001_create_product_reviews_table',1),(25,'2026_09_11_000001_create_password_reset_otps_table',1),(26,'2026_09_12_000001_add_tipe_produk_to_produks_table',1),(27,'2026_09_12_000002_add_tipe_pesanan_to_orders_table',1),(28,'2026_09_12_000003_add_tipe_produk_to_order_items_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `produk_id` bigint unsigned DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `quantity` int NOT NULL DEFAULT '1',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe_produk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pre_order',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_produk_id_foreign` (`produk_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produks` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (3,19,5,'Lemari 2 Pintu Sliding',2200000.00,1,2200000.00,NULL,'ready','2026-09-14 19:14:59','2026-09-14 19:14:59');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_progresses`
--

DROP TABLE IF EXISTS `order_progresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_progresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `step_number` int NOT NULL DEFAULT '1',
  `stage_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `media_files` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_progresses_order_id_foreign` (`order_id`),
  CONSTRAINT `order_progresses_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=339 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_progresses`
--

LOCK TABLES `order_progresses` WRITE;
/*!40000 ALTER TABLE `order_progresses` DISABLE KEYS */;
INSERT INTO `order_progresses` VALUES (104,16,1,'Konfirmasi Pesanan','Selesai',NULL,NULL,'2026-09-09 19:14:59','2026-09-14 19:14:59','2026-09-14 19:14:59'),(105,16,2,'Validasi Pembayaran','Selesai',NULL,NULL,'2026-09-10 19:14:59','2026-09-14 19:14:59','2026-09-14 19:14:59'),(106,16,3,'Pesanan Diterima','Selesai',NULL,NULL,'2026-09-11 19:14:59','2026-09-14 19:14:59','2026-09-14 19:14:59'),(107,16,4,'Menyiapkan Bahan','Selesai',NULL,NULL,'2026-09-12 19:14:59','2026-09-14 19:14:59','2026-09-14 19:14:59'),(108,16,5,'Perakitan','Sedang Berjalan',NULL,'Proses perakitan kerangka utama sofa jati 70%',NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(109,16,6,'Penyelesaian','Pending',NULL,NULL,NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(110,16,7,'Pengiriman','Pending',NULL,NULL,NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(111,16,8,'Pesanan Selesai','Pending',NULL,NULL,NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(112,17,1,'Konfirmasi Pesanan','Sedang Berjalan',NULL,NULL,NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(113,17,2,'Validasi Pembayaran','Pending',NULL,NULL,NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(114,17,3,'Pesanan Diterima','Pending',NULL,NULL,NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(115,17,4,'Menyiapkan Bahan','Pending',NULL,NULL,NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(116,17,5,'Perakitan','Pending',NULL,NULL,NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(117,17,6,'Penyelesaian','Pending',NULL,NULL,NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(118,17,7,'Pengiriman','Pending',NULL,NULL,NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(119,17,8,'Pesanan Selesai','Pending',NULL,NULL,NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(120,18,1,'Konfirmasi Pesanan','Selesai',NULL,NULL,'2026-09-05 19:14:59','2026-09-14 19:14:59','2026-09-14 19:14:59'),(121,18,2,'Validasi Pembayaran','Selesai',NULL,NULL,'2026-09-06 19:14:59','2026-09-14 19:14:59','2026-09-14 19:14:59'),(122,18,3,'Pesanan Diterima','Selesai',NULL,NULL,'2026-09-07 19:14:59','2026-09-14 19:14:59','2026-09-14 19:14:59'),(123,18,4,'Menyiapkan Bahan','Selesai',NULL,NULL,'2026-09-08 19:14:59','2026-09-14 19:14:59','2026-09-14 19:14:59'),(124,18,5,'Perakitan','Selesai',NULL,NULL,'2026-09-09 19:14:59','2026-09-14 19:14:59','2026-09-14 19:14:59'),(125,18,6,'Penyelesaian','Selesai',NULL,NULL,'2026-09-10 19:14:59','2026-09-14 19:14:59','2026-09-14 19:14:59'),(126,18,7,'Pengiriman','Selesai',NULL,NULL,'2026-09-11 19:14:59','2026-09-14 19:14:59','2026-09-14 19:14:59'),(127,18,8,'Pesanan Selesai','Selesai',NULL,NULL,'2026-09-12 19:14:59','2026-09-14 19:14:59','2026-09-14 19:14:59'),(128,19,1,'Konfirmasi Pesanan','Selesai',NULL,NULL,'2026-09-12 19:14:59','2026-09-14 19:14:59','2026-09-14 19:14:59'),(129,19,2,'Validasi Pembayaran','Selesai',NULL,NULL,'2026-09-13 19:14:59','2026-09-14 19:14:59','2026-09-14 19:14:59'),(130,19,3,'Pengemasan Barang','Sedang Berjalan',NULL,'Unit lemari dicek kondisi mulus dan dikemas dengan pelindung sudut kayu',NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(131,19,4,'Pengiriman','Pending',NULL,NULL,NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(132,19,5,'Pesanan Selesai','Pending',NULL,NULL,NULL,'2026-09-14 19:15:00','2026-09-14 19:15:00');
/*!40000 ALTER TABLE `order_progresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `total_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `dp_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `shipping_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `remaining_payment` decimal(12,2) NOT NULL DEFAULT '0.00',
  `order_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu Konfirmasi',
  `tipe_pesanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pre_order',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'qris',
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu Pembayaran DP',
  `production_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu Konfirmasi',
  `current_stage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Konfirmasi Pesanan',
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `dp_receipt_proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_receipt_proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `customer_notes` text COLLATE utf8mb4_unicode_ci,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (16,'ORD-8821',64,4500000.00,2250000.00,50000.00,2300000.00,'Menunggu Konfirmasi','pre_order','qris','DP Terverifikasi','Dalam Pengerjaan','Perakitan','Budi Santoso','081234567890','Jl. Pemuda No. 45, Kecamatan Genteng, Kota Surabaya, Jawa Timur',NULL,NULL,'Rangka kayu jati sudah selesai dipotong, saat ini masuk tahap perakitan sambungan purus.','Tolong sandaran dibuat empuk dobel busa dan kaki meja dibubut rapi.',NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(17,'ORD-10025',65,3800000.00,1900000.00,50000.00,1950000.00,'Menunggu Konfirmasi','pre_order','transfer','Belum Bayar','Menunggu Konfirmasi','Konfirmasi Pesanan','Rina Wijaya','085987654321','Jl. Diponegoro No. 88, Kota Malang, Jawa Timur',NULL,NULL,NULL,'Finishing tolong warna natural oak doff.',NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(18,'ORD-10018',66,6000000.00,3000000.00,50000.00,0.00,'Menunggu Konfirmasi','pre_order','qris','Lunas','Selesai','Pesanan Selesai','Ahmad Fauzi','087811223344','Jl. Panglima Sudirman No. 10, Kabupaten Sumenep, Jawa Timur',NULL,NULL,NULL,'Pintu utama ukir Jepara bunga melati.',NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(19,'ORD-RDY01',64,2200000.00,2200000.00,50000.00,0.00,'Menunggu Konfirmasi','ready','transfer','Lunas','Siap Dikemas','Pengemasan Barang','Budi Santoso','081234567890','Jl. Pemuda No. 45, Kecamatan Genteng, Kota Surabaya, Jawa Timur',NULL,NULL,'Stok produk diperiksa dari gudang display, saat ini sedang proses packing bubble wrap & kardus tebal.','Barang ready stock, mohon dicek packingnya agar aman sampai tujuan.',NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_otps`
--

DROP TABLE IF EXISTS `password_reset_otps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_otps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp_code` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT '0',
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_reset_otps_user_id_is_used_index` (`user_id`,`is_used`),
  KEY `password_reset_otps_otp_code_is_used_index` (`otp_code`,`is_used`),
  CONSTRAINT `password_reset_otps_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_otps`
--

LOCK TABLES `password_reset_otps` WRITE;
/*!40000 ALTER TABLE `password_reset_otps` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_otps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_reviews`
--

DROP TABLE IF EXISTS `product_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `produk_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `rating` tinyint unsigned NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified_buyer` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu Persetujuan',
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_reviews_order_id_foreign` (`order_id`),
  KEY `product_reviews_produk_id_status_index` (`produk_id`,`status`),
  KEY `product_reviews_user_id_index` (`user_id`),
  CONSTRAINT `product_reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `product_reviews_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_reviews`
--

LOCK TABLES `product_reviews` WRITE;
/*!40000 ALTER TABLE `product_reviews` DISABLE KEYS */;
INSERT INTO `product_reviews` VALUES (10,1,64,NULL,5,'Ukiran sangat rapi dan indah','Kualitas ukirannya luar biasa, detail motif bunga dan sulurnya sangat halus. Kayu jati terasa padat dan berkualitas. Packing juga aman saat pengiriman. Sangat direkomendasikan!',NULL,1,'Disetujui',NULL,'2026-09-10 19:14:59','2026-09-14 19:14:59'),(11,1,65,NULL,5,'Puas sekali, mebel impian tercapai','Awalnya ragu beli online karena harga lumayan, ternyata tidak mengecewakan. Finishing warnanya persis seperti foto katalog. Pelayanan admin juga responsif saat ditanya-tanya dulu.',NULL,0,'Disetujui',NULL,'2026-06-21 19:14:59','2026-09-14 19:14:59'),(12,2,65,NULL,4,'Kualitas bagus, pengiriman agak lama','Produk sesuai deskripsi, kayu jati asli dan ukirannya bagus. Hanya proses pembuatannya agak lama karena antrean produksi, tapi hasilnya sepadan dengan waktu tunggu.',NULL,1,'Disetujui',NULL,'2026-06-25 19:14:59','2026-09-14 19:14:59'),(13,2,66,NULL,5,'Kualitas premium, langganan tetap','Sudah order kedua kali di sini. Konsisten kualitasnya. Kayu solid, tidak mengecor, dan ukiran dibuat manual oleh pengrajin berpengalaman. Terima kasih Assalam Mebel!',NULL,0,'Disetujui',NULL,'2026-08-08 19:14:59','2026-09-14 19:14:59'),(14,2,64,NULL,4,'Bagus dan kokoh','Mebelnya kokoh dan stabil, tidak goyang sama sekali. Warna tonenya elegan. Sedikit catatan: sebaiknya dilapisi kain pelindung di bagian kaki. Overall puas.',NULL,1,'Disetujui',NULL,'2026-06-28 19:14:59','2026-09-14 19:14:59'),(15,3,66,NULL,4,'Bagus dan kokoh','Mebelnya kokoh dan stabil, tidak goyang sama sekali. Warna tonenya elegan. Sedikit catatan: sebaiknya dilapisi kain pelindung di bagian kaki. Overall puas.',NULL,1,'Disetujui',NULL,'2026-08-18 19:14:59','2026-09-14 19:14:59'),(16,3,64,NULL,5,'Desain sesuai permintaan','Saya request penyesuaian ukuran lewat Studio Custom dan hasilnya persis seperti desain yang saya ajukan. Komunikasinya lancar dari awal sampai barang jadi.',NULL,0,'Disetujui',NULL,'2026-08-01 19:14:59','2026-09-14 19:14:59'),(17,3,65,NULL,3,'Cukup baik, ada perbaikan kecil','Secara umum produk bagus. Ada sedikit perbedaan warna dengan foto katalog, mungkin efek pencahayaan. Admin menanggapi dengan baik dan memberi solusi.',NULL,1,'Disetujui',NULL,'2026-08-27 19:14:59','2026-09-14 19:14:59'),(18,3,66,NULL,5,'Cocok untuk hadiah pernikahan','Dibeli sebagai hadiah untuk keluarga, sampainya dengan selamat dan penerimanya sangat senang. Ukirannya mewah dan classy. Harga sepadan dengan kualitas.',NULL,0,'Disetujui',NULL,'2026-08-20 19:14:59','2026-09-14 19:14:59'),(19,4,64,NULL,3,'Cukup baik, ada perbaikan kecil','Secara umum produk bagus. Ada sedikit perbedaan warna dengan foto katalog, mungkin efek pencahayaan. Admin menanggapi dengan baik dan memberi solusi.',NULL,1,'Disetujui',NULL,'2026-08-13 19:14:59','2026-09-14 19:14:59'),(20,4,65,NULL,5,'Cocok untuk hadiah pernikahan','Dibeli sebagai hadiah untuk keluarga, sampainya dengan selamat dan penerimanya sangat senang. Ukirannya mewah dan classy. Harga sepadan dengan kualitas.',NULL,0,'Disetujui',NULL,'2026-08-18 19:14:59','2026-09-14 19:14:59'),(21,5,65,NULL,4,'Recommended seller mebel jati','Proses DP dan pelunasannya jelas, ada progres produksi yang bisa dipantau step by step. Jadi tenang karena tahu pesanan kita dikerjakan sungguhan.',NULL,1,'Disetujui',NULL,'2026-07-07 19:14:59','2026-09-14 19:14:59'),(22,5,66,NULL,5,'Kayu jati asli, bukan abu-abu','Sebagai orang yang paham kayu, saya memastikan ini jati asli perhutani. Serat kayunya indah dan beratnya sesuai. Ukiran tangan terlihat dari detailnya. Mantap!',NULL,0,'Disetujui',NULL,'2026-07-11 19:14:59','2026-09-14 19:14:59'),(23,5,64,NULL,5,'Ukiran sangat rapi dan indah','Kualitas ukirannya luar biasa, detail motif bunga dan sulurnya sangat halus. Kayu jati terasa padat dan berkualitas. Packing juga aman saat pengiriman. Sangat direkomendasikan!',NULL,1,'Disetujui',NULL,'2026-06-20 19:14:59','2026-09-14 19:14:59'),(24,6,66,NULL,5,'Ukiran sangat rapi dan indah','Kualitas ukirannya luar biasa, detail motif bunga dan sulurnya sangat halus. Kayu jati terasa padat dan berkualitas. Packing juga aman saat pengiriman. Sangat direkomendasikan!',NULL,1,'Disetujui',NULL,'2026-09-04 19:14:59','2026-09-14 19:14:59'),(25,6,64,NULL,5,'Puas sekali, mebel impian tercapai','Awalnya ragu beli online karena harga lumayan, ternyata tidak mengecewakan. Finishing warnanya persis seperti foto katalog. Pelayanan admin juga responsif saat ditanya-tanya dulu.',NULL,0,'Disetujui',NULL,'2026-06-24 19:14:59','2026-09-14 19:14:59'),(26,6,65,NULL,4,'Kualitas bagus, pengiriman agak lama','Produk sesuai deskripsi, kayu jati asli dan ukirannya bagus. Hanya proses pembuatannya agak lama karena antrean produksi, tapi hasilnya sepadan dengan waktu tunggu.',NULL,1,'Disetujui',NULL,'2026-06-20 19:14:59','2026-09-14 19:14:59'),(27,6,66,NULL,5,'Kualitas premium, langganan tetap','Sudah order kedua kali di sini. Konsisten kualitasnya. Kayu solid, tidak mengecor, dan ukiran dibuat manual oleh pengrajin berpengalaman. Terima kasih Assalam Mebel!',NULL,0,'Disetujui',NULL,'2026-06-26 19:14:59','2026-09-14 19:14:59'),(28,7,64,NULL,4,'Kualitas bagus, pengiriman agak lama','Produk sesuai deskripsi, kayu jati asli dan ukirannya bagus. Hanya proses pembuatannya agak lama karena antrean produksi, tapi hasilnya sepadan dengan waktu tunggu.',NULL,1,'Disetujui',NULL,'2026-08-26 19:14:59','2026-09-14 19:14:59'),(29,7,65,NULL,5,'Kualitas premium, langganan tetap','Sudah order kedua kali di sini. Konsisten kualitasnya. Kayu solid, tidak mengecor, dan ukiran dibuat manual oleh pengrajin berpengalaman. Terima kasih Assalam Mebel!',NULL,0,'Disetujui',NULL,'2026-07-26 19:14:59','2026-09-14 19:14:59'),(30,8,65,NULL,4,'Bagus dan kokoh','Mebelnya kokoh dan stabil, tidak goyang sama sekali. Warna tonenya elegan. Sedikit catatan: sebaiknya dilapisi kain pelindung di bagian kaki. Overall puas.',NULL,1,'Disetujui',NULL,'2026-08-21 19:14:59','2026-09-14 19:14:59'),(31,8,66,NULL,5,'Desain sesuai permintaan','Saya request penyesuaian ukuran lewat Studio Custom dan hasilnya persis seperti desain yang saya ajukan. Komunikasinya lancar dari awal sampai barang jadi.',NULL,0,'Disetujui',NULL,'2026-07-03 19:14:59','2026-09-14 19:14:59'),(32,8,64,NULL,3,'Cukup baik, ada perbaikan kecil','Secara umum produk bagus. Ada sedikit perbedaan warna dengan foto katalog, mungkin efek pencahayaan. Admin menanggapi dengan baik dan memberi solusi.',NULL,1,'Disetujui',NULL,'2026-07-07 19:14:59','2026-09-14 19:14:59'),(33,9,66,NULL,3,'Cukup baik, ada perbaikan kecil','Secara umum produk bagus. Ada sedikit perbedaan warna dengan foto katalog, mungkin efek pencahayaan. Admin menanggapi dengan baik dan memberi solusi.',NULL,1,'Disetujui',NULL,'2026-07-05 19:14:59','2026-09-14 19:14:59'),(34,9,64,NULL,5,'Cocok untuk hadiah pernikahan','Dibeli sebagai hadiah untuk keluarga, sampainya dengan selamat dan penerimanya sangat senang. Ukirannya mewah dan classy. Harga sepadan dengan kualitas.',NULL,0,'Disetujui',NULL,'2026-07-06 19:14:59','2026-09-14 19:14:59'),(35,9,65,NULL,4,'Recommended seller mebel jati','Proses DP dan pelunasannya jelas, ada progres produksi yang bisa dipantau step by step. Jadi tenang karena tahu pesanan kita dikerjakan sungguhan.',NULL,1,'Disetujui',NULL,'2026-07-06 19:14:59','2026-09-14 19:14:59'),(36,9,66,NULL,5,'Kayu jati asli, bukan abu-abu','Sebagai orang yang paham kayu, saya memastikan ini jati asli perhutani. Serat kayunya indah dan beratnya sesuai. Ukiran tangan terlihat dari detailnya. Mantap!',NULL,0,'Disetujui',NULL,'2026-07-24 19:14:59','2026-09-14 19:14:59'),(37,10,64,NULL,4,'Recommended seller mebel jati','Proses DP dan pelunasannya jelas, ada progres produksi yang bisa dipantau step by step. Jadi tenang karena tahu pesanan kita dikerjakan sungguhan.',NULL,1,'Disetujui',NULL,'2026-09-01 19:14:59','2026-09-14 19:14:59'),(38,10,65,NULL,5,'Kayu jati asli, bukan abu-abu','Sebagai orang yang paham kayu, saya memastikan ini jati asli perhutani. Serat kayunya indah dan beratnya sesuai. Ukiran tangan terlihat dari detailnya. Mantap!',NULL,0,'Disetujui',NULL,'2026-08-15 19:14:59','2026-09-14 19:14:59'),(39,11,65,NULL,5,'Ukiran sangat rapi dan indah','Kualitas ukirannya luar biasa, detail motif bunga dan sulurnya sangat halus. Kayu jati terasa padat dan berkualitas. Packing juga aman saat pengiriman. Sangat direkomendasikan!',NULL,1,'Disetujui',NULL,'2026-07-07 19:14:59','2026-09-14 19:14:59'),(40,11,66,NULL,5,'Puas sekali, mebel impian tercapai','Awalnya ragu beli online karena harga lumayan, ternyata tidak mengecewakan. Finishing warnanya persis seperti foto katalog. Pelayanan admin juga responsif saat ditanya-tanya dulu.',NULL,0,'Disetujui',NULL,'2026-08-05 19:14:59','2026-09-14 19:14:59'),(41,11,64,NULL,4,'Kualitas bagus, pengiriman agak lama','Produk sesuai deskripsi, kayu jati asli dan ukirannya bagus. Hanya proses pembuatannya agak lama karena antrean produksi, tapi hasilnya sepadan dengan waktu tunggu.',NULL,1,'Disetujui',NULL,'2026-07-23 19:14:59','2026-09-14 19:14:59'),(42,12,66,NULL,4,'Kualitas bagus, pengiriman agak lama','Produk sesuai deskripsi, kayu jati asli dan ukirannya bagus. Hanya proses pembuatannya agak lama karena antrean produksi, tapi hasilnya sepadan dengan waktu tunggu.',NULL,1,'Disetujui',NULL,'2026-07-07 19:14:59','2026-09-14 19:14:59'),(43,12,64,NULL,5,'Kualitas premium, langganan tetap','Sudah order kedua kali di sini. Konsisten kualitasnya. Kayu solid, tidak mengecor, dan ukiran dibuat manual oleh pengrajin berpengalaman. Terima kasih Assalam Mebel!',NULL,0,'Disetujui',NULL,'2026-09-01 19:14:59','2026-09-14 19:14:59'),(44,12,65,NULL,4,'Bagus dan kokoh','Mebelnya kokoh dan stabil, tidak goyang sama sekali. Warna tonenya elegan. Sedikit catatan: sebaiknya dilapisi kain pelindung di bagian kaki. Overall puas.',NULL,1,'Disetujui',NULL,'2026-08-27 19:14:59','2026-09-14 19:14:59'),(45,12,66,NULL,5,'Desain sesuai permintaan','Saya request penyesuaian ukuran lewat Studio Custom dan hasilnya persis seperti desain yang saya ajukan. Komunikasinya lancar dari awal sampai barang jadi.',NULL,0,'Disetujui',NULL,'2026-06-27 19:14:59','2026-09-14 19:14:59'),(46,13,64,NULL,4,'Bagus dan kokoh','Mebelnya kokoh dan stabil, tidak goyang sama sekali. Warna tonenya elegan. Sedikit catatan: sebaiknya dilapisi kain pelindung di bagian kaki. Overall puas.',NULL,1,'Disetujui',NULL,'2026-08-06 19:14:59','2026-09-14 19:14:59'),(47,13,65,NULL,5,'Desain sesuai permintaan','Saya request penyesuaian ukuran lewat Studio Custom dan hasilnya persis seperti desain yang saya ajukan. Komunikasinya lancar dari awal sampai barang jadi.',NULL,0,'Disetujui',NULL,'2026-07-31 19:14:59','2026-09-14 19:14:59');
/*!40000 ALTER TABLE `product_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produks`
--

DROP TABLE IF EXISTS `produks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `tipe_produk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pre_order',
  `estimasi_po` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produks`
--

LOCK TABLES `produks` WRITE;
/*!40000 ALTER TABLE `produks` DISABLE KEYS */;
INSERT INTO `produks` VALUES (1,'Kursi Ukir (1 Set)','Set kursi tamu ukir kayu jati, terdiri dari sofa besar, sepasang kursi kecil, dan meja tengah, ukiran motif bunga dan sulur khas Madura. Ukuran: Sofa: P150×L60×T95 cm; kursi kecil: P70×60 cm; meja: P120×L55×T45 cm.',32000000.00,'pre_order','21','produk/01_kursi-sofa-ukir-set.jpg','2026-09-14 19:14:59','2026-09-14 19:14:59'),(2,'Podium/Mimbar Kayu Ukir Logo Garuda','Podium kayu jati dengan lambang Garuda Pancasila berwarna emas dan papan nama \"DESA PAKAMBAN LAOK\", motif ukir batik pada badan podium.',4500000.00,'ready',NULL,'produk/02_podium-desa-pakamban-laok.jpg','2026-09-14 19:14:59','2026-09-14 19:14:59'),(3,'Pintu Tarung Full Ukir','Sepasang pintu kayu jati ukir penuh motif bunga dan sulur daun, bagian atas melengkung (arch top). Ukuran: 250×130 cm, tebal 4 cm.',8000000.00,'pre_order','14','produk/03_pintu-tarung-full-ukir.jpg','2026-09-14 19:14:59','2026-09-14 19:14:59'),(4,'Logo NU Ukir','Panel kayu ukir logo Nahdlatul Ulama (NU) dengan finishing prada emas, dilengkapi kaligrafi Arab dan bintang sembilan. Ukuran: 150×100 cm, tebal 3 cm.',2000000.00,'pre_order','10','produk/04_logo-NU.jpg','2026-09-14 19:14:59','2026-09-14 19:14:59'),(5,'Lemari 2 Pintu Sliding','Lemari pakaian pintu geser (sliding) 2 pintu dengan 2 laci bawah, motif garis minimalis, finishing coklat tua.',2200000.00,'ready',NULL,'produk/05_lemari-2-pintu-sliding.jpg','2026-09-14 19:14:59','2026-09-14 19:14:59'),(6,'Kursi Sidang 5 Set + Meja','Meja dan 5 kursi sidang ukir kayu jati dengan jok bludru biru, ukiran prada warna-warni dan logo lambang di tengah meja.',16500000.00,'pre_order','25','produk/06_kursi-sidang-5-set-meja.jpg','2026-09-14 19:14:59','2026-09-14 19:14:59'),(7,'Pendopo/Gazebo Kayu Jati','Bangunan pendopo terbuka kayu jati dengan atap joglo genteng tanah liat, tiang-tiang penyangga berukir.',45000000.00,'pre_order','30','produk/07_pendopo-gazebo-jati.jpg','2026-09-14 19:14:59','2026-09-14 19:14:59'),(8,'Kursi Sofa Motif 1 (Set 3)','Set sofa 3 buah (1 sofa panjang + 2 kursi single) dengan meja tengah, ukiran prada warna-warni motif bunga.',10000000.00,'pre_order','18','produk/08_kursi-sofa-motif-1-set-3.jpg','2026-09-14 19:14:59','2026-09-14 19:14:59'),(9,'Blawong','Panel ukir gantung (blawong) sepasang burung phoenix/merak dengan motif sulur, kayu jati. Ukuran: 65×45 cm, tebal 2 cm.',250000.00,'ready',NULL,'produk/09_blawong-65x45.jpg','2026-09-14 19:14:59','2026-09-14 19:14:59'),(10,'Lemari Minimalis 10 Pintu','Lemari serbaguna minimalis 10 pintu dengan rak kaca dan ruang terbuka tengah, finishing natural kayu. Ukuran: P200×L65 cm, tinggi 190 cm.',2500000.00,'ready',NULL,'produk/10_lemari-minimalis-10-pintu.jpg','2026-09-14 19:14:59','2026-09-14 19:14:59'),(11,'Blawong (Set 2 Motif)','Sepasang panel ukir gantung motif burung dan bunga, warna prada emas dan ungu.',500000.00,'ready',NULL,'produk/11_blawong-set-2-motif.jpg','2026-09-14 19:14:59','2026-09-14 19:14:59'),(12,'Ukiran 30×30 (Set 2)','Panel ukir persegi motif bunga dan sulur daun, kayu jati, dijual berpasangan. Ukuran: 30×30 cm, tebal 2 cm.',150000.00,'ready',NULL,'produk/12_ukiran-30x30-set-2.jpg','2026-09-14 19:14:59','2026-09-14 19:14:59'),(13,'Lemari Rias Kaca','Lemari rias dengan cermin besar berlampu, 2 lemari kaca samping dan 4 laci tengah. Ukuran: P150×L55 cm, tinggi 160 cm.',2200000.00,'pre_order','14','produk/13_lemari-rias-kaca.jpg','2026-09-14 19:14:59','2026-09-14 19:14:59');
/*!40000 ALTER TABLE `produks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (7,'wa_number','085234567890','2026-09-14 19:14:59','2026-09-14 19:14:59'),(8,'wa_status','Terhubung / Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(9,'wa_template','Halo *{nama}*, pembaruan untuk pesanan mebel custom Anda (*{produk}* - #{no_pesanan}) saat ini telah memasuki tahap: *{tahap}*. Silakan cek foto progres di aplikasi Assalam Mebel. Terima kasih!','2026-09-14 19:14:59','2026-09-14 19:14:59'),(10,'shop_name','Assalam Mebel','2026-09-14 19:14:59','2026-09-14 19:14:59'),(11,'shop_address','VPR6+PH7, Somangkaan, Karduluk, Kec. Pragaan, Kabupaten Sumenep, Jawa Timur 69465','2026-09-14 19:14:59','2026-09-14 19:14:59'),(12,'workshop_address','VPR6+PH7, Somangkaan, Karduluk, Kec. Pragaan, Kabupaten Sumenep, Jawa Timur 69465','2026-09-14 19:14:59','2026-09-14 19:14:59'),(13,'workshop_plus_code','VPR6+PH7','2026-09-14 19:14:59','2026-09-14 19:14:59'),(14,'workshop_lat','-7.1082125','2026-09-14 19:14:59','2026-09-14 19:14:59'),(15,'workshop_lng','113.7114219','2026-09-14 19:14:59','2026-09-14 19:14:59'),(16,'coverage_area','Se-Pulau Madura (Sumenep, Pamekasan, Sampang, Bangkalan)','2026-09-14 19:14:59','2026-09-14 19:14:59'),(17,'payment_dana_status','Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(18,'payment_dana_number','085234567890','2026-09-14 19:14:59','2026-09-14 19:14:59'),(19,'payment_dana_name','Assalam Mebel Official','2026-09-14 19:14:59','2026-09-14 19:14:59'),(20,'payment_dana_qr',NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(21,'payment_dana_instructions','Buka aplikasi DANA > Tekan Pindai / Pay > Scan QR Code atau transfer manual ke nomor DANA di atas. Masukkan nominal sesuai tagihan.','2026-09-14 19:14:59','2026-09-14 19:14:59');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipping_costs`
--

DROP TABLE IF EXISTS `shipping_costs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipping_costs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kecamatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `biaya` decimal(12,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipping_costs`
--

LOCK TABLES `shipping_costs` WRITE;
/*!40000 ALTER TABLE `shipping_costs` DISABLE KEYS */;
INSERT INTO `shipping_costs` VALUES (1,'Pragaan & Karduluk (Sumenep - Area Workshop)',20000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(2,'Bluto (Sumenep)',30000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(3,'Saronggi (Sumenep)',35000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(4,'Kota Sumenep (Sumenep)',45000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(5,'Kalianget (Sumenep)',45000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(6,'Ganding & Guluk-Guluk (Sumenep)',40000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(7,'Lenteng & Manding (Sumenep)',45000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(8,'Ambunten & Pasongsongan (Sumenep)',55000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(9,'Batang-Batang & Gapura (Sumenep)',60000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(10,'Dungkek (Sumenep)',65000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(11,'Larangan & Galis (Pamekasan)',40000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(12,'Kota Pamekasan (Pamekasan)',50000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(13,'Tlanakan & Pademawu (Pamekasan)',50000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(14,'Proppo & Palengaan (Pamekasan)',60000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(15,'Kadur & Pegantenan (Pamekasan)',65000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(16,'Waru, Batumarmar & Pasean (Pamekasan)',75000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(17,'Camplong (Sampang)',65000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(18,'Kota Sampang (Sampang)',80000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(19,'Torjun & Jrengik (Sampang)',90000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(20,'Omben, Kedungdung & Robatal (Sampang)',95000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(21,'Sreseh & Tambelangan (Sampang)',100000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(22,'Ketapang, Banyuates & Sokobanah (Sampang)',110000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(23,'Blega & Galis (Bangkalan)',100000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(24,'Tanah Merah & Konang (Bangkalan)',110000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(25,'Modung & Kwanyar (Bangkalan)',120000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(26,'Kota Bangkalan & Burneh (Bangkalan)',125000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(27,'Kamal & Labang / Akses Suramadu (Bangkalan)',130000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(28,'Arosbaya, Klampis & Sepulu (Bangkalan)',140000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59'),(29,'Tanjungbumi & Geger (Bangkalan)',150000.00,'Aktif','2026-09-14 19:14:59','2026-09-14 19:14:59');
/*!40000 ALTER TABLE `shipping_costs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `studio_settings`
--

DROP TABLE IF EXISTS `studio_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `studio_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `studio_settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `studio_settings`
--

LOCK TABLES `studio_settings` WRITE;
/*!40000 ALTER TABLE `studio_settings` DISABLE KEYS */;
INSERT INTO `studio_settings` VALUES (1,'base_rate_jati','4500000','2026-09-14 19:14:59','2026-09-14 19:14:59'),(2,'base_rate_mahoni','3500000','2026-09-14 19:14:59','2026-09-14 19:14:59'),(3,'base_rate_sungkai','3800000','2026-09-14 19:14:59','2026-09-14 19:14:59'),(4,'dp_percentage','50','2026-09-14 19:14:59','2026-09-14 19:14:59'),(5,'min_length','50','2026-09-14 19:14:59','2026-09-14 19:14:59'),(6,'max_length','300','2026-09-14 19:14:59','2026-09-14 19:14:59'),(7,'min_width','30','2026-09-14 19:14:59','2026-09-14 19:14:59'),(8,'max_width','200','2026-09-14 19:14:59','2026-09-14 19:14:59'),(9,'min_height','30','2026-09-14 19:14:59','2026-09-14 19:14:59'),(10,'max_height','250','2026-09-14 19:14:59','2026-09-14 19:14:59'),(11,'estimated_work_days','14','2026-09-14 19:14:59','2026-09-14 19:14:59');
/*!40000 ALTER TABLE `studio_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=189 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (63,'Administrator Assalam','lilik','admin@assalammebel.com',NULL,'$2y$12$0wwBqCjJ0c7crqFSUwisZO0pw5tJDv6HdIOxyyOs5fkUvtpaC.izu','085234567890','Jl. Raya Mebel Assalam No. 12, Sumenep, Madura',NULL,'admin',NULL,'2026-09-14 19:14:58','2026-09-14 19:14:58'),(64,'Budi Santoso','budisantoso','budi@gmail.com',NULL,'$2y$12$oYEwipd46szoQhNjxMkP9en6TqZggTdn58zQ7pS1KLAWAAWXPAGQ6','081234567890','Jl. Pemuda No. 45, Kecamatan Genteng, Kota Surabaya, Jawa Timur',NULL,'customer',NULL,'2026-09-14 19:14:58','2026-09-14 19:14:58'),(65,'Rina Wijaya','rinawijaya','rina.wijaya@gmail.com',NULL,'$2y$12$D5jYAYmscD8zgkzyYHBMYue/kINN5.EZS4vYQPrUCjnd1XJ8QcZk2','085987654321','Jl. Diponegoro No. 88, Kota Malang, Jawa Timur',NULL,'customer',NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59'),(66,'Ahmad Fauzi','ahmadfauzi','ahmad.fauzi@gmail.com',NULL,'$2y$12$jifun7inQHNWMUpmwmMGUePsdA2d6DhweRuqQXwsmlMggu12yjfvG','087811223344','Jl. Panglima Sudirman No. 10, Kabupaten Sumenep, Jawa Timur',NULL,'customer',NULL,'2026-09-14 19:14:59','2026-09-14 19:14:59');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wa_contacts`
--

DROP TABLE IF EXISTS `wa_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wa_contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wa_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pushname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_business` tinyint(1) NOT NULL DEFAULT '0',
  `is_my_contact` tinyint(1) NOT NULL DEFAULT '0',
  `is_blocked` tinyint(1) NOT NULL DEFAULT '0',
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wa_contacts_session_id_wa_id_unique` (`session_id`,`wa_id`),
  KEY `wa_contacts_number_index` (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wa_contacts`
--

LOCK TABLES `wa_contacts` WRITE;
/*!40000 ALTER TABLE `wa_contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `wa_contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wa_message_logs`
--

DROP TABLE IF EXISTS `wa_message_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wa_message_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned DEFAULT NULL,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message_body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Pending','Sent','Delivered','Failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `response_payload` text COLLATE utf8mb4_unicode_ci,
  `retry_count` int NOT NULL DEFAULT '0',
  `last_retry_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wa_message_logs_order_id_foreign` (`order_id`),
  CONSTRAINT `wa_message_logs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wa_message_logs`
--

LOCK TABLES `wa_message_logs` WRITE;
/*!40000 ALTER TABLE `wa_message_logs` DISABLE KEYS */;
INSERT INTO `wa_message_logs` VALUES (4,16,'Budi Santoso','6281234567890','order_created','Halo *Budi Santoso*,\n\nTerima kasih telah memesan mebel premium di *Assalam Mebel*! 🪵\n\n📌 *Detail Pesanan:*\n• No. Pesanan: *#ORD-8821*\n• Produk: *Sofa Tamu Minimalis Jati*\n• Estimasi Total: *Rp 4.500.000*\n• Tagihan DP (50%): *Rp 2.250.000*\n\nSilakan transfer pembayaran DP agar pesanan Anda dapat segera dijadwalkan ke dapur produksi pengrajin kami.\n\n🔗 Pantau progres pesanan Anda di:\nhttps://assalam.ghaibnet.co.id/customer/progress','Delivered','{\"id\":\"true_6281234567890@c.us_3EB0123456789\",\"ack\":2,\"timestamp\":1789391700}',0,NULL,'2026-09-14 13:15:00','2026-09-14 13:15:00'),(5,16,'Budi Santoso','6281234567890','dp_verified','Halo *Budi Santoso*,\n\nPembayaran Uang Muka (DP) untuk pesanan *#ORD-8821* (Sofa Tamu Minimalis Jati) sebesar *Rp 2.250.000* telah *TERVERIFIKASI* oleh tim Assalam Mebel. ✅\n\nPengrajin kami kini mulai menyiapkan material kayu solid pilihan untuk memproduksi furniture impian Anda.\n\n🔗 Cek pembaruan status pengerjaan:\nhttps://assalam.ghaibnet.co.id/customer/progress','Sent','{\"id\":\"true_6281234567890@c.us_3EB0987654321\",\"ack\":1,\"timestamp\":1789398900}',0,NULL,'2026-09-14 15:15:00','2026-09-14 15:15:00'),(6,16,'Budi Santoso','6281234567890','progress_updated','Halo *Budi Santoso*,\n\nAda kabar terbaru untuk pesanan furniture Anda (*#ORD-8821* - Sofa Tamu Minimalis Jati)! 🪚✨\n\n🎯 *Tahap Pengerjaan Saat Ini:*\n👉 *Menyiapkan Bahan*\n\n📝 *Catatan Pengrajin:*\n_Kayu jati perhutani TPK grade A telah dipotong dan diserut presisi sesuai ukuran._\n\n📸 Foto dokumentasi pengerjaan telah kami unggah ke sistem. Anda dapat melihat foto & detail tahapan secara langsung di tautan berikut:\nhttps://assalam.ghaibnet.co.id/customer/progress','Sent','{\"id\":\"true_6281234567890@c.us_3EB0112233445\",\"ack\":1,\"timestamp\":1789406100}',0,NULL,'2026-09-14 17:15:00','2026-09-14 17:15:00'),(7,17,'Rina Kartika','6285987654321','order_created','Halo *Rina Kartika*,\n\nTerima kasih telah memesan mebel premium di *Assalam Mebel*! 🪵\n\n📌 *Detail Pesanan:*\n• No. Pesanan: *#ORD-10025*\n• Produk: *Meja Trembesi Solid*\n• Estimasi Total: *Rp 8.200.000*\n• Tagihan DP (50%): *Rp 4.100.000*\n\nSilakan transfer pembayaran DP agar pesanan Anda dapat segera dijadwalkan ke dapur produksi pengrajin kami.\n\n🔗 Pantau progres pesanan Anda di:\nhttps://assalam.ghaibnet.co.id/customer/progress','Failed','{\"error\":\"Connection to WhatsApp Web Sidecar timeout. Retry requested.\"}',1,'2026-09-14 18:45:00','2026-09-14 18:15:00','2026-09-14 18:45:00');
/*!40000 ALTER TABLE `wa_message_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wa_messages`
--

DROP TABLE IF EXISTS `wa_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wa_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `backend` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wa_message_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chat_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `body` text COLLATE utf8mb4_unicode_ci,
  `payload` json DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ack` smallint DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_for_everyone` tinyint(1) NOT NULL DEFAULT '0',
  `wa_timestamp` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wa_messages_session_id_chat_id_index` (`session_id`,`chat_id`),
  KEY `wa_messages_wa_message_id_index` (`wa_message_id`),
  KEY `wa_messages_direction_index` (`direction`),
  KEY `wa_messages_status_index` (`status`),
  KEY `wa_messages_ack_index` (`ack`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wa_messages`
--

LOCK TABLES `wa_messages` WRITE;
/*!40000 ALTER TABLE `wa_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `wa_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wa_sessions`
--

DROP TABLE IF EXISTS `wa_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wa_sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `backend` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web',
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'initializing',
  `last_qr_at` timestamp NULL DEFAULT NULL,
  `ready_at` timestamp NULL DEFAULT NULL,
  `disconnected_at` timestamp NULL DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wa_sessions_status_index` (`status`),
  KEY `wa_sessions_backend_index` (`backend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wa_sessions`
--

LOCK TABLES `wa_sessions` WRITE;
/*!40000 ALTER TABLE `wa_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wa_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wa_templates`
--

DROP TABLE IF EXISTS `wa_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wa_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_trigger` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wa_templates_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wa_templates`
--

LOCK TABLES `wa_templates` WRITE;
/*!40000 ALTER TABLE `wa_templates` DISABLE KEYS */;
INSERT INTO `wa_templates` VALUES (8,'order_created','Konfirmasi Pesanan Masuk','Saat Pelanggan Checkout / Mengajukan Pesanan Custom','Halo *{nama}*,\n\nTerima kasih telah memesan mebel premium di *Assalam Mebel*! 🪵\n\n📌 *Detail Pesanan:*\n• No. Pesanan: *#{no_pesanan}*\n• Produk: *{produk}*\n• Estimasi Total: *Rp {total_harga}*\n• Tagihan DP (50%): *Rp {dp_amount}*\n\nSilakan transfer pembayaran DP agar pesanan Anda dapat segera dijadwalkan ke dapur produksi pengrajin kami.\n\n🔗 Pantau progres pesanan Anda di:\n{link_tracking}\n\nSalam hangat,\n*Tim Assalam Mebel Jepara*',1,'2026-09-14 19:15:00','2026-09-14 19:15:00'),(9,'dp_verified','Verifikasi Pembayaran DP Berhasil','Saat Admin Memverifikasi Pembayaran DP','Halo *{nama}*,\n\nPembayaran Uang Muka (DP) untuk pesanan *#{no_pesanan}* (*{produk}*) sebesar *Rp {dp_amount}* telah *TERVERIFIKASI* oleh tim Assalam Mebel. ✅\n\nPengrajin kami kini mulai menyiapkan material kayu solid pilihan untuk memproduksi furniture impian Anda.\n\n🔗 Cek pembaruan status pengerjaan:\n{link_tracking}\n\nTerima kasih atas kepercayaan Anda!',1,'2026-09-14 19:15:00','2026-09-14 19:15:00'),(10,'progress_updated','Pembaruan Tahap Produksi Mebel','Saat Admin Mengubah Tahap Pengerjaan di Menu Progres Produksi','Halo *{nama}*,\n\nAda kabar terbaru untuk pesanan furniture Anda (*#{no_pesanan}* - *{produk}*)! 🪚✨\n\n🎯 *Tahap Pengerjaan Saat Ini:*\n👉 *{tahap}*\n\n📝 *Catatan Pengrajin:*\n_{catatan}_\n\n📸 Foto dokumentasi pengerjaan telah kami unggah ke sistem. Anda dapat melihat foto & detail tahapan secara langsung di tautan berikut:\n{link_tracking}\n\nSalam,\n*Assalam Mebel Jepara*',1,'2026-09-14 19:15:00','2026-09-14 19:15:00'),(11,'payment_completed','Konfirmasi Pelunasan Pembayaran','Saat Pembayaran Sisa Tagihan Dilunasi','Halo *{nama}*,\n\nPembayaran pelunasan untuk pesanan *#{no_pesanan}* telah kami terima dengan sukses. Status tagihan Anda saat ini: *LUNAS* 🎉\n\nFurniture Anda sedang melalui tahap pengecekan kualitas akhir (Quality Control) dan siap dikemas rapi untuk pengiriman aman.\n\n🔗 Pantau pengiriman di:\n{link_tracking}',1,'2026-09-14 19:15:00','2026-09-14 19:15:00'),(12,'order_finished','Pesanan Selesai & Dikirim','Saat Pesanan Diserahkan ke Ekspedisi / Telah Tiba','Halo *{nama}*,\n\nPesanan furniture *#{no_pesanan}* (*{produk}*) telah selesai diproduksi dan kini dalam perjalanan menuju alamat Anda! 🚚📦\n\nSemoga furniture kayu solid dari Assalam Mebel mempercantik ruangan Anda dan awet berpuluh-puluh tahun. Jangan ragu menghubungi kami jika memerlukan panduan perawatan mebel kayu solid.\n\nTerima kasih telah berbelanja di *Assalam Mebel*! ❤️',1,'2026-09-14 19:15:00','2026-09-14 19:15:00'),(13,'otp_forgot_password','OTP Reset Kata Sandi (Lupa Password)','Saat Pengguna Meminta Reset Password','Halo *{nama}*,\n\nBerikut adalah kode OTP verifikasi untuk mengatur ulang kata sandi (reset password) akun Assalam Mebel Jepara Anda:\n\n🔑 *{otp}*\n\nKode ini berlaku selama {menit} menit. Demi keamanan akun Anda, JANGAN bagikan kode ini kepada siapa pun termasuk pihak Assalam Mebel.\n\nJika Anda tidak meminta perubahan kata sandi, silakan abaikan pesan ini.\n\nSalam hangat,\n*Assalam Mebel Jepara*',1,'2026-09-14 19:15:00','2026-09-14 19:15:00');
/*!40000 ALTER TABLE `wa_templates` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-14 19:41:40
