-- GestSoutenance — Schéma MySQL

CREATE DATABASE IF NOT EXISTS gest_soutenance
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE gest_soutenance;

CREATE TABLE utilisateurs (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom              VARCHAR(100) NOT NULL,
    prenom           VARCHAR(100) NOT NULL,
    email            VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe     VARCHAR(255) NOT NULL,
    role             ENUM('administrateur','secretaire','enseignant','etudiant','responsable_pedagogique') NOT NULL,
    actif            TINYINT(1) NOT NULL DEFAULT 1,
    doit_changer_mdp TINYINT(1) NOT NULL DEFAULT 0,
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE salles (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom          VARCHAR(100) NOT NULL,
    capacite     INT UNSIGNED NOT NULL DEFAULT 30,
    localisation VARCHAR(200),
    equipements  TEXT,
    actif        TINYINT(1) NOT NULL DEFAULT 1,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE soutenances (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etudiant_id     INT UNSIGNED NOT NULL,
    directeur_id    INT UNSIGNED NOT NULL,
    titre           VARCHAR(300) NOT NULL,
    filiere         VARCHAR(100) NOT NULL,
    type            ENUM('licence','master','doctorat') NOT NULL,
    date_soutenance DATE NOT NULL,
    heure_debut     TIME NOT NULL,
    heure_fin       TIME NOT NULL,
    salle_id        INT UNSIGNED,
    statut          ENUM('brouillon','planifiee','confirmee','realisee','annulee') NOT NULL DEFAULT 'brouillon',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (etudiant_id)  REFERENCES utilisateurs(id),
    FOREIGN KEY (directeur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (salle_id)     REFERENCES salles(id)
) ENGINE=InnoDB;

CREATE TABLE audit_log (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT UNSIGNED,
    action         VARCHAR(100) NOT NULL,
    entite         VARCHAR(50)  NOT NULL,
    entite_id      INT UNSIGNED,
    details        TEXT,
    ip_address     VARCHAR(45),
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE INDEX idx_salles_actif ON salles(actif);
