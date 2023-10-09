CREATE TABLE transaction (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    email VARCHAR(255) NOT NULL,
    transaction_type VARCHAR(255) NOT NULL,
    amount FLOAT DEFAULT 0.0,
    transaction_date TIMESTAMP
);
