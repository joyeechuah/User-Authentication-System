CREATE DATABASE IF NOT EXISTS login_logout_auth;
 
USE login_logout_auth;
 
CREATE TABLE IF NOT EXISTS user (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(255) NOT NULL,
    email    VARCHAR(75)  NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
