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
    INSERT INTO EmploymentHistory (employer, title, supervisor, phone, address, start_date)
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
    INSERT INTO EmploymentHistory (employer, title, supervisor, phone, address, start_date)
    VALUES (employer, title, supervisor, phone, address, STR_TO_DATE(start_date, '%Y-%c-%e'));
    INSERT INTO Customer_EmploymentHistory (tax_id, employment_history_id) VALUES (tax_id, LAST_INSERT_ID());
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
    SELECT eh.employment_history_id 
    FROM Customer_EmploymentHistory AS ceh 
    LEFT JOIN EmploymentHistory AS eh
        ON eh.employment_history_id = ceh.employment_history_id
    WHERE eh.employer = employer AND eh.title = title AND eh.supervisor = supervisor AND eh.phone = phone AND 
    eh.address = address AND ceh.tax_id = tax_id
    INTO eid;
    DELETE FROM Customer_EmploymentHistory WHERE tax_id = tax_id AND employment_history_id = eid;
    DELETE FROM EmploymentHistory WHERE eh.employer = employer AND eh.title = title AND 
    eh.supervisor = supervisor AND eh.phone = phone AND eh.address = address;
END //
DELIMITER ;

DELIMITER //
CREATE OR REPLACE PROCEDURE getSalesForm(
    IN sale_id INT
)
BEGIN
    SELECT se.employee_commission_percent AS commission, vs.list_price, vs.sales_price,
        s.date, s.total_due, s.down_payment, s.financed_amount,
        e.first_name AS employee_first_name, e.last_name AS employee_last_name, 
        c.first_name AS customer_first_name, c.last_name AS customer_last_name,
        c.phone_number AS customer_phone_number, v.vin, v.miles, v.condition, 
        v.style, v.color
    FROM Sale_Employee AS se
    LEFT JOIN Vehicle_Sale AS vs
        ON se.sale_id = vs.sale_id
    LEFT JOIN Sale AS s
        ON se.sale_id = s.sale_id 
    LEFT JOIN Sale_Customer AS sc
        ON se.sale_id = sc.sale_id
    LEFT JOIN Customer AS c
        ON sc.tax_id = c.tax_id
    LEFT JOIN Employee AS e 
        ON e.employee_id = se.employee_id
    LEFT JOIN Vehicle AS v 
        ON v.vin = vs.vin
    WHERE se.sale_id = sale_id;
END //
DELIMITER ;