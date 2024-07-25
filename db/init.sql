CREATE DATABASE IF NOT EXISTS camagru;

USE camagru;

CREATE TABLE IF NOT EXISTS users (
    id VARCHAR(24) PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS images (
    id VARCHAR(24) PRIMARY KEY,
    owner_id VARCHAR(24) NOT NULL,
    hash BINARY(32) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users (id)
);

INSERT INTO
    users (id, username, email, password)
VALUES (
        '1',
        'asd',
        'testuser@example.com',
        '$2y$10$Ac36JNb28dNxfUz2j4jI6OHXSlF8/0TtvatWFoTISMEyqVkYqraRK'
    ),
    (
        '2',
        'john_doe',
        'john.doe@example.com',
        'password2'
    );

