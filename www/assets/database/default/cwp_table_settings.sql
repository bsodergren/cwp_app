CREATE TABLE "settings" (
	"id"	INTEGER NOT NULL UNIQUE,
	"name"	varchar(200) NOT NULL,
	"value"	varchar(250) DEFAULT NULL,
	"type"	varchar(30) NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);