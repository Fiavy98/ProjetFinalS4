PRAGMA foreign_keys = ON;

CREATE TABLE operateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    prefixes TEXT NOT NULL
);

CREATE TABLE typeOperation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libele TEXT NOT NULL
);

CREATE TABLE frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    idTypeOperation INTEGER NOT NULL,
    min REAL NOT NULL,
    max REAL NOT NULL,
    valeur REAL NOT NULL,
    FOREIGN KEY (idTypeOperation) REFERENCES typeOperation(id)
);

CREATE TABLE client (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    num TEXT NOT NULL UNIQUE,
    mdp TEXT NOT NULL,
    nom TEXT NOT NULL,
    solde REAL NOT NULL DEFAULT 0
);

CREATE TABLE operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    idTypeOperation INTEGER NOT NULL,
    idClient INTEGER NOT NULL,
    valeur REAL NOT NULL,
    idFrais INTEGER,
    description TEXT,
    dateheure DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (idTypeOperation) REFERENCES typeOperation(id),
    FOREIGN KEY (idClient) REFERENCES client(id),
    FOREIGN KEY (idFrais) REFERENCES frais(id)
);

CREATE TABLE historiqueGain (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    dateheure DATETIME DEFAULT CURRENT_TIMESTAMP,
    idOperation INTEGER NOT NULL,
    valeur REAL NOT NULL,

    FOREIGN KEY (idOperation) REFERENCES operation(id)
);

CREATE TABLE gain (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    idOperateur INTEGER NOT NULL,
    idHistorique INTEGER NOT NULL,
    valeur REAL NOT NULL,

    FOREIGN KEY (idOperateur) REFERENCES operateur(id),
    FOREIGN KEY (idHistorique) REFERENCES historiqueGain(id)
);

CREATE TABLE historiqueOperationClient (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    idClient INTEGER NOT NULL,
    idOperation INTEGER NOT NULL,
    dateheure DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (idClient) REFERENCES client(id),
    FOREIGN KEY (idOperation) REFERENCES operation(id)
);

