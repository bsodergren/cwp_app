CREATE TABLE "paper_type" (
	"id"	INTEGER NOT NULL UNIQUE,
	"paper_wieght"	int NOT NULL,
	"paper_size"	varchar(20) NOT NULL,
	"pages"	int NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);
--
-- Dumping data for table `paper_type`
--

INSERT INTO `paper_type` VALUES(1, 38, 'small', 4);
INSERT INTO `paper_type` VALUES(2, 38, 'small', 6);
INSERT INTO `paper_type` VALUES(3, 38, 'small', 8);
INSERT INTO `paper_type` VALUES(4, 38, 'large', 4);
INSERT INTO `paper_type` VALUES(5, 38, 'large', 6);
INSERT INTO `paper_type` VALUES(6, 38, 'large', 8);
INSERT INTO `paper_type` VALUES(7, 50, 'small', 4);
INSERT INTO `paper_type` VALUES(8, 50, 'small', 6);
INSERT INTO `paper_type` VALUES(9, 50, 'small', 8);
INSERT INTO `paper_type` VALUES(10, 50, 'large', 4);
INSERT INTO `paper_type` VALUES(11, 50, 'large', 6);
INSERT INTO `paper_type` VALUES(12, 50, 'large', 8);
