CREATE TABLE "settings" (
	"id"	INTEGER NOT NULL UNIQUE,
	"definedName"	varchar(200) NOT NULL,
	"value"	TEXT DEFAULT NULL,
	"description" TEXT DEFAULT NULL,
	"name " varchar(200) NOT NULL,
	"type"	varchar(30) NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);

INSERT INTO "settings" VALUES (1,'__USER_XLSX_DIR__','','text');
INSERT INTO "settings" VALUES (2,'__USE_LOCAL_XLSX__','0','bool');
INSERT INTO "settings" VALUES (3,'__SHOW_TRACY__','0','bool');