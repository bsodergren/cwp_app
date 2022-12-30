CREATE TABLE "paper_count" (
	"id"	INTEGER NOT NULL UNIQUE,
	"paper_id"	int NOT NULL,
	"pcs_carton"	int DEFAULT NULL,
	"back_lift"	int DEFAULT NULL,
	"front_lift"	int DEFAULT NULL,
	"max_carton"	int DEFAULT NULL,
	"max_half_skid"	int DEFAULT NULL,
	"max_full_skid"	int DEFAULT NULL,
	"half_skid_lifts_layer"	int DEFAULT NULL,
	"full_skid_lifts_layer"	int DEFAULT NULL,
	"back_half_skid_layers"	int DEFAULT NULL,
	"back_full_skid_layers"	int DEFAULT NULL,
	"front_half_skid_layers"	int DEFAULT NULL,
	"front_full_skid_layers"	int DEFAULT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);
--
-- Dumping data for table `paper_count`
--

INSERT INTO `paper_count` VALUES(1, 1, 2500, 500, 1250, 17500, 40000, 135000, 8, 18, 10, 15, 4, 6);
INSERT INTO `paper_count` VALUES(2, 2, 1400, NULL, 700, 9800, 28000, NULL, 8, 18, NULL, NULL, 5, 9);
INSERT INTO `paper_count` VALUES(3, 3, 1000, NULL, 500, 7000, 16000, NULL, 8, 18, NULL, NULL, 4, 6);
INSERT INTO `paper_count` VALUES(4, 4, 2500, 500, 1250, 17500, 40000, NULL, 8, 18, 10, 15, 4, 6);
INSERT INTO `paper_count` VALUES(5, 5, 1400, NULL, 700, 9800, 28000, NULL, 8, 18, NULL, NULL, 5, 9);
INSERT INTO `paper_count` VALUES(6, 6, 1000, NULL, 500, 7000, 16000, NULL, 8, 18, NULL, NULL, 4, 6);
INSERT INTO `paper_count` VALUES(7, 7, 2000, 500, 1000, 20000, 40000, 90000, 8, 18, 12, 12, 7, 6);
INSERT INTO `paper_count` VALUES(8, 8, 1300, NULL, 650, 13000, 26000, NULL, 8, 18, NULL, NULL, 7, 6);
INSERT INTO `paper_count` VALUES(9, 9, 900, NULL, 450, 9000, 20000, NULL, 8, 18, NULL, NULL, 6, 6);
INSERT INTO `paper_count` VALUES(10, 10, 2000, 500, 1000, 20000, 40000, 90000, 6, 17, 12, 12, 7, 6);
INSERT INTO `paper_count` VALUES(11, 11, 1300, NULL, 650, 13000, 26000, NULL, 6, 17, NULL, NULL, 7, 6);
INSERT INTO `paper_count` VALUES(12, 12, 900, NULL, 450, 9000, 20000, NULL, 6, 17, NULL, NULL, 6, 6);
