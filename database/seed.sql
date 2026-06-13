-- Données de test — mot de passe : password

USE gest_soutenance;

-- Hash généré avec password_hash('password', PASSWORD_BCRYPT)
SET @pwd = '$2y$10$1oSPf1mG8l6qXNMMahmgp.jn5dJC34Kv58VTyvd0Tj1a.W3hNF1Py';

INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES
('Diallo', 'Amadou',   'admin@univ.sn',      @pwd, 'administrateur'),
('Ndiaye', 'Fatou',    'secretaire@univ.sn', @pwd, 'secretaire'),
('Sow',    'Moussa',   'enseignant@univ.sn', @pwd, 'enseignant'),
('Ba',     'Aissatou', 'etudiant@univ.sn',   @pwd, 'etudiant'),
('Fall',   'Ibrahima', 'resp.ped@univ.sn',   @pwd, 'responsable_pedagogique');

INSERT INTO salles (nom, capacite, localisation, equipements) VALUES
('Amphi A',   120, 'Bâtiment principal — RDC',  'Projecteur, micro, climatisation'),
('Salle B12',  30, 'Bâtiment B — 1er étage',   'Projecteur, tableau blanc'),
('Salle C05',  20, 'Bâtiment C — 2e étage',    'Projecteur'),
('Salle D01',  15, 'Bâtiment D — RDC',         'Tableau blanc');
