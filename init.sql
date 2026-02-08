-- Create the database tables for the Lego Mini Figures project

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE minifigures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    priceCents INT NOT NULL,
    categoryId INT,
    imageUrl VARCHAR(255),
    description TEXT,
    FOREIGN KEY (categoryId) REFERENCES categories(id)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    passwordHash VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NULL,
    customerName VARCHAR(255) NOT NULL,
    customerEmail VARCHAR(255) NOT NULL,
    totalCents INT NOT NULL,
    status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE orderItems (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orderId INT NOT NULL,
    minifigureId INT NOT NULL,
    quantity INT NOT NULL,
    priceCents INT NOT NULL,
    lineTotalCents INT NOT NULL,
    FOREIGN KEY (orderId) REFERENCES orders(id),
    FOREIGN KEY (minifigureId) REFERENCES minifigures(id)
);

CREATE TABLE invitations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    invitedBy INT NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    token VARCHAR(255) UNIQUE NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    acceptedAt TIMESTAMP NULL,
    FOREIGN KEY (invitedBy) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert some sample data
INSERT INTO categories (name) VALUES ('Heroes'), ('Spellcasters'), ('Legends');

INSERT INTO minifigures (name, priceCents, categoryId, imageUrl, description) VALUES
('TheLadyOfPain',      620, 3, '/images/minifigures/TheLadyOfPain.png',      'Mysterious ruler who floats silently, face masked with blades.'),
('JaheiraInspiredDruid', 500, 2, '/images/minifigures/JaheiraInspiredDruid.png', 'Nature sage who commands vines and storms.'),
('HalflingDruid',      470, 2, '/images/minifigures/HalflingDruid.png',      'Cheerful guardian who shapeshifts into woodland beasts.'),
('TashaTheWitchQueen', 610, 3, '/images/minifigures/TashaTheWitchQueen.png', 'Legendary witch queen wielding playful yet deadly curses.'),
('TieflingSorcerer',   560, 2, '/images/minifigures/TieflingSorcerer.png',   'Infernal-blooded caster channeling raw arcane power.'),
('ElfBard',            490, 1, '/images/minifigures/ElfBard.png',            'Silver-tongued minstrel whose songs inspire allies.'),
('DragonbornPaladin',  580, 1, '/images/minifigures/DragonbornPaladin.png',  'Oath-bound champion with radiant breath and shield.'),
('WolfpackBeastmaster',520, 1, '/images/minifigures/WolfpackBeastmaster.png','Tracker leading loyal wolves into battle.'),
('AgathaHarkness',     600, 3, '/images/minifigures/AgathaHarkness.png',     'Ancient witch mastering forbidden hexes.'),
('SzassTam',           620, 3, '/images/minifigures/SzassTam.png',           'Undying lich lord commanding legions of the dead.');