CREATE TABLE user (
    email VARCHAR(255) NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    password VARCHAR(255) DEFAULT '',
    access_level int DEFAULT 0
);