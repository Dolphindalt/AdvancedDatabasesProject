USE rmn_auto;

START TRANSACTION;

INSERT INTO Seller (seller_tax_id, name) VALUES (1594234, "John Truck Repo");
INSERT INTO Seller (seller_tax_id, name) VALUES (7952342, "Daisies Dealers");
INSERT INTO Seller (seller_tax_id, name) VALUES (6832999, "Junkyard Auto Restorations");

INSERT INTO Customer (tax_id, first_name, last_name, phone_number, gender, dob) VALUES 
(2234467, "John", "Partha", "949-838-8881", "Male", STR_TO_DATE("1995-12-25", '%Y-%c-%e')),
(6948383, "Gaius", "Ceasar", "949-777-7777", "Male", STR_TO_DATE("1991-01-01", '%Y-%c-%e'));

INSERT INTO Location (address, city, state, zip) VALUES 
("301 Park Street", "Fullerton", "CA", "92679"),
("30 Foro Imperium", "Newmark", "MA", "72134");

INSERT INTO Customer_Location (tax_id, location_id) VALUES 
(2234467, 1),
(6948383, 2);

INSERT INTO Employee (employee_id, role, wage, first_name, last_name, phone_number, gender, dob) 
VALUES (5823423, "Buyer", 20, "George", "Soros", "949-343-3434", "Male", STR_TO_DATE("1999-10-28", '%Y-%c-%e'));
INSERT INTO Employee (employee_id, role, wage, first_name, last_name, phone_number, gender, dob) 
VALUES (1353223, "Laborer", 20, "Dan", "Hauge", "949-333-3234", "Male", STR_TO_DATE("2003-09-08", '%Y-%c-%e'));
INSERT INTO Employee (employee_id, role, wage, first_name, last_name, phone_number, gender, dob) 
VALUES (1978382, "Salesperson", 20, "Corinna", "Pompeius", "949-665-6565", "Female", STR_TO_DATE("1997-10-01", '%Y-%c-%e'));

INSERT INTO Vehicle (`vin`, `make`, `model`, `year`, `color`, `miles`, `style`, `condition`) 
VALUES ("2323123", "Honda", "Beat", 1999, "Red", 2000.0, "Coup", "Poor") 
ON DUPLICATE KEY UPDATE `make` = VALUES(`make`), `model` = VALUES(`model`), `year` = VALUES(`year`), `color` = VALUES(`color`), `miles` = VALUES(`miles`), `style` = VALUES(`style`), `condition` = VALUES(`condition`);

COMMIT;