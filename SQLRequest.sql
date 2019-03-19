CREATE TABLE "ID" (
	"Key"	TEXT NOT NULL UNIQUE,
	"PC_name"	TEXT NOT NULL UNIQUE,
	PRIMARY KEY("Key")
);

CREATE TABLE "admin" (
	"username"	TEXT,
	"password"	TEXT
);

CREATE TABLE "infoDisk" (
	"idDisk"	INTEGER PRIMARY KEY AUTOINCREMENT,
	"pcKey"	TEXT,
	"pcName"	TEXT,
	"totalSpace"	REAL,
	"dir"	TEXT,
	"diskKey"	TEXT,
	"saveSpace"	REAL,
	"usedSpace"	REAL,
	"freeSpace"	REAL,
	"lastUpdate"	TEXT,
	FOREIGN KEY("pcKey") REFERENCES "ID"("Key")
);

CREATE TABLE "shareList" (
	"idShare"	INTEGER PRIMARY KEY AUTOINCREMENT,
	"idTorrent"	TEXT,
	"idPair"	TEXT,
	"left"	REAL DEFAULT 0,
	FOREIGN KEY("idPair") REFERENCES "ID"("Key"),
	FOREIGN KEY("idTorrent") REFERENCES "torrent"("idTorrent")
);

CREATE TABLE "tempID" (
	"Key"	TEXT NOT NULL,
	"PC_name"	TEXT NOT NULL,
	PRIMARY KEY("Key","PC_name")
);

CREATE TABLE "torrent" (
	"idTorrent"	INTEGER PRIMARY KEY AUTOINCREMENT,
	"libelle"	TEXT,
	"taille"	REAL,
	"statut"	INTEGER,
	"hash"	TEXT,
	"idSource"	TEXT,
	FOREIGN KEY("idSource") REFERENCES "ID"("Key")
);