CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    status TINYINT(1) DEFAULT 1
);

UPDATE users SET is_admin = 1 WHERE email = 'admin@admin.com';