CREATE DATABASE cfp61;

USE cfp61;

CREATE TABLE inscripciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullName VARCHAR(100) NOT NULL,
    dni VARCHAR(20) NOT NULL,
    birthDate DATE NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    previousStudies VARCHAR(100) NOT NULL,
    course VARCHAR(100) NOT NULL,
    comments TEXT
);