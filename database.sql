
CREATE DATABASE IF NOT EXISTS csgo_trust_factor;

USE csgo_trust_factor;


CREATE TABLE IF NOT EXISTS trust_factors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    steam_id VARCHAR(255) NOT NULL,
    trust_factor VARCHAR(10) NOT NULL
);

