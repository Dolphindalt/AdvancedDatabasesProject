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
CREATE OR REPLACE PROCEDURE insertAddress(
    IN VARCHAR(255) street,
    IN VARCHAR(255) city,
    IN VARCHAR(2) state,
    IN VARCHAR(10) zip
)
BEGIN
    INSERT INTO Location (address, city, state, zip)
    VALUES (sreet, city, state, zip);
END //
DELIMITER ;

DELIMITER //
CREATE OR REPLACE PROCEDURE insertEmploymentHistory(
    IN VARCHAR(255) employer,
    IN VARCHAR(255) title,
    IN VARCHAR(255) supervisor,
    IN VARCHAR(255) phone,
    IN VARCHAR(255) address,
    IN VARCHAR(255) start_date
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
    IN VARCHAR(255) street,
    IN VARCHAR(255) city,
    IN VARCHAR(2) state,
    IN VARCHAR(10) zip
)
BEGIN
    CALL insertCustomer(tax_id, first_name, last_name, phone_number, gender, dob);
    CALL insertAddress(street, city, state, zip);
END //
DELIMITER ;

DELIMITER //
CREATE OR REPLACE PROCEDURE addEmploymentHistoryEntry(
    IN tax_id INT,
    IN VARCHAR(255) employer,
    IN VARCHAR(255) title,
    IN VARCHAR(255) supervisor,
    IN VARCHAR(255) phone,
    IN VARCHAR(255) address,
    IN VARCHAR(255) start_date
)
BEGIN
    INSERT INTO EmployementHistory (employer, title, supervisor, phone, address, start_date)
    VALUES (employer, title, supervisor, phone, address, start_date);
    INSERT INTO Customer_EmploymentHistory (tax_id, LAST_INSERT_ID());
END //
DELIMITER ;

DELIMITER //
CREATE OR REPLACE PROCEDURE removeEmploymentHistoryEntry(
    IN tax_id INT,
    IN VARCHAR(255) employer,
    IN VARCHAR(255) title,
    IN VARCHAR(255) supervisor,
    IN VARCHAR(255) phone,
    IN VARCHAR(255) address,
    IN VARCHAR(255) start_date
)
BEGIN
    DECLARE eid INT;
    SELECT eh.employement_history_id 
    FROM Customer_EmploymentHistory AS ceh 
    LEFT JOIN EmployementHistory AS eh
    ON eh.employement_history_id = ceh.employement_history_id
    WHERE eh.employer = employer AND eh.title = title AND eh.supervisor = supervisor AND eh.phone = phone AND 
    eh.address = address AND eh.start_date = start_date AND ceh.tax_id = tax_id
    INTO eid;
    DELETE FROM Customer_EmploymentHistory WHERE tax_id = tax_id AND employement_history_id = eid;
    DELETE FROM EmployementHistory WHERE eh.employer = employer AND eh.title = title AND 
    eh.supervisor = supervisor AND eh.phone = phone AND eh.address = address AND eh.start_date = start_date;
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