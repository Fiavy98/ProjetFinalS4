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
    idOperateurSource INTEGER,
    idOperateurDestinataire INTEGER,
    commission REAL NOT NULL DEFAULT 0,
    description TEXT,
    dateheure DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (idTypeOperation) REFERENCES typeOperation(id),
    FOREIGN KEY (idClient) REFERENCES client(id),
    FOREIGN KEY (idFrais) REFERENCES frais(id),
    FOREIGN KEY (idOperateurSource) REFERENCES operateur(id),
    FOREIGN KEY (idOperateurDestinataire) REFERENCES operateur(id)
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

CREATE TABLE commissionAutreOperateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    idOperateur INTEGER NOT NULL,
    idTypeOperation INTEGER NOT NULL,
    pourcentage REAL NOT NULL,

    FOREIGN KEY(idOperateur) REFERENCES operateur(id),
    FOREIGN KEY(idTypeOperation) REFERENCES typeOperation(id)
);


-- Table operateur
INSERT INTO operateur (nom, prefixes) VALUES
('YAS', '034,038'),
('Airtel Money', '033'),
('Orange Money', '037,032');


-- Table typeOperation
INSERT INTO typeOperation (libele) VALUES
('Depot'),
('Retrait'),
('Transfert'),
('Paiement');


-- Table frais
-- Depot
INSERT INTO frais (idTypeOperation, min, max, valeur) VALUES
(1, 0, 10000.99, 100),
(1, 10001, 50000.99, 200),
(1, 50001, 1000000, 500);

-- Retrait
INSERT INTO frais (idTypeOperation, min, max, valeur) VALUES
(2, 0, 10000.99, 300),
(2, 10001, 50000.99, 500),
(2, 50001, 1000000, 1000);

-- Transfert
INSERT INTO frais (idTypeOperation, min, max, valeur) VALUES
(3, 0, 10000.99, 100),
(3, 10001, 50000.99, 250),
(3, 50001, 1000000, 500);


-- Table client
INSERT INTO client (num, mdp, nom, solde) VALUES
('0341234567', '1234', 'Rakoto Jean', 50000),
('0341234568', '1234', 'Rakoto Jean Deux', 50000),
('0341234569', '1234', 'Rakoto Jean Trois', 50000),
('0339876543', '5678', 'Rabe Marie', 100000),
('0371122334', 'abcd', 'Andry Paul', 75000),
('0325566778', '0000', 'Soa Julie', 20000);

-- Commission reçue par l'opérateur destinataire pour un transfert externe.
INSERT INTO commissionAutreOperateur (idOperateur, idTypeOperation, pourcentage) VALUES
(1, 3, 0),
(2, 3, 5),
(3, 3, 5);


-- Table operation
-- Jean fait un dépôt de 10000
INSERT INTO operation 
(idTypeOperation, idClient, valeur, idFrais, idOperateurSource, description)
VALUES
(1, 1, 10000, 1, 1, 'Depot argent');

-- Marie retire 20000
INSERT INTO operation 
(idTypeOperation, idClient, valeur, idFrais, idOperateurSource, description)
VALUES
(2, 2, 20000, 5, 1, 'Retrait argent');

-- Paul transfert 5000
INSERT INTO operation 
(idTypeOperation, idClient, valeur, idFrais, idOperateurSource, description)
VALUES
(3, 3, 5000, 7, 1, 'Transfert vers client');


-- Table historiqueGain
INSERT INTO historiqueGain (idOperation, valeur)
VALUES
(1, 100),
(2, 500),
(3, 100);


-- Table gain
INSERT INTO gain (idOperateur, idHistorique, valeur)
VALUES
(1, 1, 100),
(1, 2, 500),
(1, 3, 100);


-- Table historiqueOperationClient
INSERT INTO historiqueOperationClient
(idClient, idOperation)
VALUES
(1,1),
(2,2),
(3,3);


CREATE TABLE promotion (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    idOperateur int,
    valeur float,
    FOREIGN KEY(idOperateur) REFERENCES operateur(id)
);

insert into promotion(idOperateur, valeur) Values(1,20);