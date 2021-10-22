USE rmn_auto;

DELIMITER //
CREATE OR REPLACE PROCEDURE insertCustomer(
    IN tax_id INT,
    IN first_name VARCHAR(255),
    IN last_name VARCHAR(255),
    IN phone_number VARCHAR(255),
    IN gender VARCHAR(255),
    IN dob VARCHAR(255)
)
BEGIN
    INSERT INTO Customer (tax_id, first_name, last_name, phone_number, gender, dob)
    VALUES (tax_id, first_name, last_name, phone_number, gender, STR_TO_DATE(dob, '%c/%e/%Y %H:%i'));
END //
DELIMITER ;

DELIMITER //
CREATE OR REPLACE PROCEDURE getCustomer(
    IN tax_id INT
)
BEGIN
    SELECT * 
    FROM Customer
    WHERE Customer.tax_id = tax_id;
END //
DELIMITER ;