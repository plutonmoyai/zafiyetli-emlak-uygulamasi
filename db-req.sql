-- sudo apt install mariadb-server
-- sudo mysql -u root -p
-- GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' IDENTIFIED BY '906711';
-- FLUSH PRIVILEGES;

CREATE DATABASE emlak_app;

USE emlak_app;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL,
    firstname VARCHAR(50) NULL,
    lastname VARCHAR(50) NULL,
    linkedin VARCHAR(100) NULL,
    github VARCHAR(100) NULL,
    profile_pic VARCHAR(255) NULL
);

CREATE TABLE properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('konut', 'arsa') NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    area DECIMAL(10, 2) NOT NULL,
    rooms INT NULL, -- Sadece konut için
    floors INT NULL, -- Sadece konut için
    building_age INT NULL, -- Sadece konut için
    zoning_status VARCHAR(50) NULL, -- Sadece arsa için
    land_type VARCHAR(50) NULL, -- Sadece arsa için
    address VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    district VARCHAR(100) NOT NULL,
    image_path VARCHAR(255) NULL,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO users (username, password, role) VALUES 
('admin', PASSWORD('admin123'), 'admin'), 
('editor', PASSWORD('editor123'), 'editor'),
('subscriber', PASSWORD('subs123'), 'subscriber'), 
('user', PASSWORD('user123'), 'user');