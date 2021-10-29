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
    VALUES (tax_id, first_name, last_name, phone_number, gender, STR_TO_DATE(dob, '%Y-%c-%e'));
END //
DELIMITER ;

DELIMITER //
CREATE OR REPLACE PROCEDURE insertAddress(
    IN tax_id INT,
    IN street VARCHAR(255),
    IN city VARCHAR(255),
    IN state VARCHAR(2),
    IN zip VARCHAR(10)
)
BEGIN
    INSERT INTO Location (address, city, state, zip)
    VALUES (street, city, state, zip);
    INSERT INTO Customer_Location (tax_id, location_id)
    VALUES (tax_id, LAST_INSERT_ID());
END //
DELIMITER ;

DELIMITER //
CREATE OR REPLACE PROCEDURE getCustomerAddress(
    IN tax_id INT
)
BEGIN
    SELECT loc.address, loc.state, loc.city, loc.zip, loc.location_id
    FROM Location AS loc
    JOIN Customer_Location AS cloc
    ON cloc.tax_id = tax_id;
END //
DELIMITER ;

DELIMITER //
CREATE OR REPLACE PROCEDURE insertEmploymentHistory(
    IN employer VARCHAR(255),
    IN title VARCHAR(255),
    IN supervisor VARCHAR(255),
    IN phone VARCHAR(255),
    IN address VARCHAR(255),
    IN start_date VARCHAR(255)
)
BEGIN
    INSERT INTO EmployementHistory (employer, title, supervisor, phone, address, start_date)
    VALUES (employer, title, supervisor, phone, address, start_date);
END //
DELIMITER ;

DELIMITER //
CREATE OR REPLACE PROCEDURE addCustomerPageSubmit(
    IN tax_id INT,
    IN first_name VARCHAR(255),
    IN last_name VARCHAR(255),
    IN phone_number VARCHAR(255),
    IN gender VARCHAR(255),
    IN dob VARCHAR(255),
    IN street VARCHAR(255),
    IN city VARCHAR(255),
    IN state VARCHAR(2),
    IN zip VARCHAR(10)
)
BEGIN
    CALL insertCustomer(tax_id, first_name, last_name, phone_number, gender, dob);
    CALL insertAddress(tax_id, street, city, state, zip);
END //
DELIMITER ;

DELIMITER //
CREATE OR REPLACE PROCEDURE addEmploymentHistoryEntry(
    IN tax_id INT,
    IN employer VARCHAR(255),
    IN title VARCHAR(255),
    IN supervisor VARCHAR(255),
    IN phone VARCHAR(255),
    IN address VARCHAR(255),
    IN start_date VARCHAR(255)
)
BEGIN
    INSERT INTO EmployementHistory (employer, title, supervisor, phone, address, start_date)
    VALUES (employer, title, supervisor, phone, address, STR_TO_DATE(start_date, '%Y-%c-%e'));
    INSERT INTO Customer_EmploymentHistory (tax_id, location_id) VALUES (tax_id, LAST_INSERT_ID());
END //
DELIMITER ;

DELIMITER //
CREATE OR REPLACE PROCEDURE removeEmploymentHistoryEntry(
    IN tax_id INT,
    IN employer VARCHAR(255),
    IN title VARCHAR(255),
    IN supervisor VARCHAR(255),
    IN phone VARCHAR(255),
    IN address VARCHAR(255)
)
BEGIN
    DECLARE eid INT;
    SELECT eh.employement_history_id 
    FROM Customer_EmploymentHistory AS ceh 
    LEFT JOIN EmployementHistory AS eh
    ON eh.employement_history_id = ceh.employement_history_id
    WHERE eh.employer = employer AND eh.title = title AND eh.supervisor = supervisor AND eh.phone = phone AND 
    eh.address = address AND ceh.tax_id = tax_id
    INTO eid;
    DELETE FROM Customer_EmploymentHistory WHERE tax_id = tax_id AND employement_history_id = eid;
    DELETE FROM EmployementHistory WHERE eh.employer = employer AND eh.title = title AND 
    eh.supervisor = supervisor AND eh.phone = phone AND eh.address = address;
END //
DELIMITER ;