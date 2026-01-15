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
    role ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT,
    customerName VARCHAR(255) NOT NULL,
    customerEmail VARCHAR(255) NOT NULL,
    totalCents INT NOT NULL,
    status ENUM('pending', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(id)
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

-- Insert some sample data
INSERT INTO categories (name) VALUES ('Fantasy'), ('Sci-Fi'), ('Historical');

INSERT INTO minifigures (name, priceCents, categoryId, imageUrl, description) VALUES
('Gandalf', 500, 1, '/images/minifigures/GandalfInspiredWizard.png', 'A wise wizard from Middle-earth.'),
('Yoda', 600, 2, '/images/minifigures/YodaInspiredJedi.png', 'A legendary Jedi Master.'),
('Knight', 450, 3, '/images/minifigures/Knight.png', 'A brave medieval knight.');