CREATE TABLE "flag_style" (
	"id"	INTEGER NOT NULL UNIQUE,
	"style_name"	varchar(150) NOT NULL,
	"ecol"	varchar(5) DEFAULT NULL,
	"erow"	varchar(3) DEFAULT NULL,
	"text"	varchar(50) DEFAULT NULL,
	"bold"	int DEFAULT NULL,
	"font_size"	int DEFAULT NULL,
	"h_align"	int DEFAULT NULL,
	"v_align"	int DEFAULT NULL,
	"width"	int DEFAULT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);
--
-- Dumping data for table `flag_style`
--

INSERT INTO `flag_style` VALUES(1, 'Job Number', 'A', '6', 'Job Number', 1, 22, NULL, NULL, NULL);
INSERT INTO `flag_style` VALUES(2, 'Market Text', 'A', '7', 'Market', 1, 22, NULL, NULL, NULL);
INSERT INTO `flag_style` VALUES(3, '', 'A', '8', 'Magazine', 1, 22, 0, 0, NULL);
INSERT INTO `flag_style` VALUES(4, '', 'A', '9', 'Destination', 1, 22, 0, 0, NULL);
INSERT INTO `flag_style` VALUES(5, '', 'A', '10', 'Packaging', 1, 22, 0, 0, NULL);
INSERT INTO `flag_style` VALUES(6, '', 'B', '6', '', 1, 32, 1, 0, NULL);
INSERT INTO `flag_style` VALUES(7, '', 'B', '7', '', 0, 20, 1, 0, NULL);
INSERT INTO `flag_style` VALUES(8, '', 'B', '8', '', 0, 20, 1, 0, NULL);
INSERT INTO `flag_style` VALUES(9, '', 'B', '9', '', 0, 20, 1, 0, NULL);
INSERT INTO `flag_style` VALUES(10, '', 'B', '10', '', 1, 24, 1, 0, NULL);
INSERT INTO `flag_style` VALUES(11, '', 'D', '6', '', 1, 32, 0, 0, NULL);
INSERT INTO `flag_style` VALUES(12, '', 'D', '7', '', 0, 20, 1, 0, NULL);
INSERT INTO `flag_style` VALUES(13, '', 'D', '8', '', 1, 20, 1, 0, NULL);
INSERT INTO `flag_style` VALUES(14, '', 'A', '13', '', 0, 14, 0, 0, NULL);
INSERT INTO `flag_style` VALUES(15, '', 'A', '14', '', 0, 14, 0, 0, NULL);
INSERT INTO `flag_style` VALUES(16, '', 'A', '15', '', 0, 14, 0, 0, NULL);
INSERT INTO `flag_style` VALUES(17, '', 'A', '16', '', 0, 14, 0, 0, NULL);
INSERT INTO `flag_style` VALUES(18, '', 'A', '17', '', 0, 14, 0, 0, NULL);
INSERT INTO `flag_style` VALUES(19, '', 'A', '18', '', 0, 14, 0, 0, NULL);
INSERT INTO `flag_style` VALUES(20, '', 'A', '19', '', 0, 14, 0, 0, NULL);
INSERT INTO `flag_style` VALUES(21, '', 'A', '20', '', 0, 14, 0, 0, NULL);
INSERT INTO `flag_style` VALUES(22, '', 'A', '21', '', 0, 14, 0, 0, NULL);
INSERT INTO `flag_style` VALUES(23, '', 'A', '22', '', 0, 14, 0, 0, NULL);
INSERT INTO `flag_style` VALUES(24, '', 'A', '24', '', 1, 48, 1, 1, NULL);
INSERT INTO `flag_style` VALUES(25, '', 'A', '25', '', 1, 48, 1, 1, NULL);
INSERT INTO `flag_style` VALUES(26, '', 'A', '26', '', 1, 48, 1, 1, NULL);
INSERT INTO `flag_style` VALUES(27, '', 'B', '13', '', 1, 14, 1, 0, NULL);
INSERT INTO `flag_style` VALUES(28, '', 'B', '14', '', 1, 14, 1, 0, NULL);
INSERT INTO `flag_style` VALUES(29, '', 'B', '15', '', 1, 14, 1, 0, NULL);
INSERT INTO `flag_style` VALUES(30, '', 'B', '16', '', 1, 14, 1, 0, NULL);
INSERT INTO `flag_style` VALUES(31, '', 'B', '17', '', 1, 14, 1, 0, NULL);
INSERT INTO `flag_style` VALUES(32, '', 'B', '18', '', 1, 14, 1, 0, NULL);
INSERT INTO `flag_style` VALUES(33, '', 'B', '19', '', 1, 14, 1, 0, NULL);
INSERT INTO `flag_style` VALUES(34, '', 'B', '20', '', 1, 14, 1, 0, NULL);
INSERT INTO `flag_style` VALUES(35, '', 'B', '21', '', 1, 14, 1, 0, NULL);
INSERT INTO `flag_style` VALUES(36, '', 'B', '22', '', 1, 14, 1, 0, NULL);
INSERT INTO `flag_style` VALUES(38, 'Load Flag', 'A', NULL, NULL, NULL, NULL, NULL, NULL, 22);
INSERT INTO `flag_style` VALUES(39, 'Load Flag', 'B', NULL, NULL, NULL, NULL, NULL, NULL, 22);
INSERT INTO `flag_style` VALUES(40, 'Load Flag', 'C', NULL, NULL, NULL, NULL, NULL, NULL, 22);
INSERT INTO `flag_style` VALUES(41, 'Load Flag', 'D', NULL, NULL, NULL, NULL, NULL, NULL, 18);
INSERT INTO `flag_style` VALUES(42, 'master sheet', 'E', NULL, NULL, NULL, NULL, NULL, NULL, 20);
INSERT INTO `flag_style` VALUES(43, 'master sheet', 'F', NULL, NULL, NULL, NULL, NULL, NULL, 20);
INSERT INTO `flag_style` VALUES(44, 'master sheet', 'A', NULL, NULL, NULL, NULL, NULL, NULL, 5);
INSERT INTO `flag_style` VALUES(45, 'master sheet', 'B', NULL, NULL, NULL, NULL, NULL, NULL, 20);
INSERT INTO `flag_style` VALUES(46, 'master sheet', 'C', NULL, NULL, NULL, NULL, NULL, NULL, 20);
INSERT INTO `flag_style` VALUES(47, 'master sheet', 'E', NULL, NULL, NULL, NULL, NULL, NULL, 5);
