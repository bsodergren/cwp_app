CREATE TABLE "settings" (
	"id"	INTEGER NOT NULL UNIQUE,
	"name"	varchar(200) NOT NULL,
	"value"	varchar(250) DEFAULT NULL,
	"type"	varchar(30) NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);

INSERT INTO "settings" VALUES (1,'__USER_XLSX_DIR__','','text');
INSERT INTO "settings" VALUES (2,'__USE_LOCAL_XLSX__','0','bool');