CREATE DATABASE IF NOT EXISTS rmn_auto;
CREATE USER 'test_user'@'localhost' IDENTIFIED BY 'test_user';
GRANT ALL PRIVILEGES ON * . * TO 'test_user'@'localhost';