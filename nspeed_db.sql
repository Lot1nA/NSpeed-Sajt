-- SQL skripta za kreiranje baze podataka i tabela
-- Ime baze podataka: nspeed_db

-- Kreiranje tabele za rezervacije fiksnih simulatora
CREATE TABLE IF NOT EXISTS `reservations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50),
    `reservation_date` DATE NOT NULL,
    `reservation_time` VARCHAR(5) NOT NULL, -- Format HH:MM
    `simulator_type` VARCHAR(50) NOT NULL, -- npr. 'Standardni', 'Pro'
    `status` ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Kreiranje tabele za rezervacije mobilnih simulatora (ako je to posebna funkcionalnost)
-- Pretpostavka: mobilni simulatori se iznajmljuju na dane, ne na sate
CREATE TABLE IF NOT EXISTS `mobile_simulator_rentals` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50),
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `location` VARCHAR(255), -- Lokacija dostave/preuzimanja
    `notes` TEXT,
    `status` ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Kreiranje tabele za administrativne korisnike
CREATE TABLE IF NOT EXISTS `admin_users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL, -- Ovde se cuva hash lozinke!
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Dodavanje podrazumevanog admin korisnika
-- Lozinka 'admin123' (HASHED) - PROMENITE OVO ODMAH NAKON PRVE PRIJAVE!
-- Password hash je generisan pomocu password_hash('admin123', PASSWORD_DEFAULT)
INSERT IGNORE INTO `admin_users` (`username`, `password`) VALUES
('admin', '$2y$10$Wp/21G2k/gG1F2F2Z2D2U.2Q2X2Y2Z2a2b2c2d2e2f2g2h2i2j2k2l2m2n2o2p2q2r2s2t2u2v2w2x2y2z202122232425262728292A2B2C2D2E2F2G2H2I2J2K2L2M2N2O2P2Q2R2S2T2U2V2W2X2Y2Z2'); -- Example hash, replace with a real one
