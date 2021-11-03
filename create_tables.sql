USE rmn_auto;

START TRANSACTION;

CREATE TABLE `Employee` (
  `employee_id` INT PRIMARY KEY,
  `role` VARCHAR(255),
  `wage` DOUBLE,
  `first_name` VARCHAR(255),
  `last_name` VARCHAR(255),
  `phone_number` VARCHAR(255),
  `gender` VARCHAR(255),
  `dob` DATETIME
);

CREATE TABLE `Customer` (
  `tax_id` INT PRIMARY KEY,
  `first_name` VARCHAR(255),
  `last_name` VARCHAR(255),
  `phone_number` VARCHAR(255),
  `gender` VARCHAR(255),
  `dob` DATETIME
);

CREATE TABLE `Location` (
  `location_id` INT PRIMARY KEY AUTO_INCREMENT,
  `address` VARCHAR(255),
  `city` VARCHAR(255),
  `state` VARCHAR(2),
  `ZIP` VARCHAR(10)
);

CREATE TABLE `Repair` (
  `repair_id` INT PRIMARY KEY AUTO_INCREMENT,
  `description` VARCHAR(255),
  `material_cost` DOUBLE,
  `labor_cost` DOUBLE
);

CREATE TABLE `Purchase` (
  `purchase_id` INT PRIMARY KEY AUTO_INCREMENT,
  `date` DATETIME,
  `location_id` INT,
  `is_auction` bit,
  `seller_id` INT,
  `tax_id` INT
);

CREATE TABLE `Vehicle` (
  `vin` VARCHAR(255) PRIMARY KEY,
  `make` VARCHAR(255),
  `model` VARCHAR(255),
  `year` INT,
  `color` VARCHAR(255),
  `miles` DOUBLE,
  `style` VARCHAR(255),
  `condition` VARCHAR(255),
  `sold` bit DEFAULT 0
);

CREATE TABLE `Problem` (
  `problem_id` INT PRIMARY KEY AUTO_INCREMENT,
  `description` VARCHAR(255),
  `estimated_repair_cost` DOUBLE,
  `actual_repair_cost` DOUBLE
);

CREATE TABLE `WarrantyForm` (
  `warranty_form_id` INT PRIMARY KEY AUTO_INCREMENT,
  `cosigner` VARCHAR(255),
  `date_sold` DATETIME,
  `total_cost` DOUBLE,
  `monthly_cost` DOUBLE
);

CREATE TABLE `Warranty` (
  `warranty_id` INT PRIMARY KEY AUTO_INCREMENT,
  `start_date` DATETIME,
  `end_date` DATETIME,
  `cost` DOUBLE,
  `deductible` DOUBLE
);

CREATE TABLE `Items` (
  `item_id` INT PRIMARY KEY AUTO_INCREMENT,
  `description` VARCHAR(255)
);

CREATE TABLE `Payment` (
  `payment_id` INT PRIMARY KEY AUTO_INCREMENT,
  `due_date` DATETIME,
  `paid_date` DATETIME,
  `amount` DOUBLE,
  `bank_account` INT
);

CREATE TABLE `Sale` (
  `sale_id` INT PRIMARY KEY AUTO_INCREMENT,
  `date` DATETIME,
  `total_due` DOUBLE,
  `down_payment` DOUBLE,
  `financed_amount` DOUBLE
);

CREATE TABLE `EmploymentHistory` (
  `employment_history_id` INT PRIMARY KEY AUTO_INCREMENT,
  `employer` VARCHAR(255),
  `title` VARCHAR(255),
  `supervisor` VARCHAR(255),
  `phone` VARCHAR(255),
  `address` VARCHAR(255),
  `start_date` DATETIME
);

CREATE TABLE `Seller` (
  `seller_tax_id` INT PRIMARY KEY,
  `name` VARCHAR(255)
);

CREATE TABLE `Employee_Location` (
  `employee_id` INT,
  `location_id` INT
);

CREATE TABLE `Customer_Location` (
  `tax_id` INT,
  `location_id` INT
);

CREATE TABLE `Vehicle_Purchase` (
  `vin` VARCHAR(255),
  `purchase_id` INT,
  `book_price` DOUBLE,
  `paid_price` DOUBLE,
  `color` VARCHAR(255),
  `miles` DOUBLE,
  `condition` VARCHAR(255)
);

CREATE TABLE `Vehicle_Problem` (
  `vin` VARCHAR(255),
  `problem_id` INT
);

CREATE TABLE `WarrantyForm_SalesPerson` (
  `warranty_form_id` INT,
  `employee_id` INT
);

CREATE TABLE `Vehicle_WarrantyForm` (
  `warranty_form_id` INT,
  `vin` VARCHAR(255)
);

CREATE TABLE `Vehicle_Warranty` (
  `warranty_id` INT,
  `vin` VARCHAR(255)
);

CREATE TABLE `Customers_WarrantyForm` (
  `warranty_form_id` INT,
  `tax_id` INT
);

CREATE TABLE `Warranty_Items` (
  `warranty_id` INT,
  `item_id` INT
);

CREATE TABLE `Customer_Payments` (
  `tax_id` INT,
  `payment_id` INT
);

CREATE TABLE `Vehicle_Sale` (
  `vin` VARCHAR(255),
  `sale_id` INT,
  `list_price` DOUBLE,
  `sales_price` DOUBLE,
  `color` VARCHAR(255),
  `miles` DOUBLE,
  `condition` VARCHAR(255)
);

CREATE TABLE `Sale_Employee` (
  `sale_id` INT,
  `employee_id` INT,
  `employee_commission_percent` DOUBLE
);

CREATE TABLE `Sale_Customer` (
  `sale_id` INT,
  `tax_id` INT
);

CREATE TABLE `Sale_CustomerLocation` (
  `sale_id` INT,
  `location_id` INT
);

CREATE TABLE `Customer_EmploymentHistory` (
  `tax_id` INT,
  `employment_history_id` INT
);

ALTER TABLE `Purchase` ADD FOREIGN KEY (`location_id`) REFERENCES `Location` (`location_id`);

ALTER TABLE `Purchase` ADD FOREIGN KEY (`seller_id`) REFERENCES `Seller` (`seller_tax_id`);

ALTER TABLE `Employee_Location` ADD FOREIGN KEY (`employee_id`) REFERENCES `Employee` (`employee_id`);

ALTER TABLE `Employee_Location` ADD FOREIGN KEY (`location_id`) REFERENCES `Location` (`location_id`);

ALTER TABLE `Customer_Location` ADD FOREIGN KEY (`tax_id`) REFERENCES `Customer` (`tax_id`);

ALTER TABLE `Customer_Location` ADD FOREIGN KEY (`location_id`) REFERENCES `Location` (`location_id`);

ALTER TABLE `Vehicle_Purchase` ADD FOREIGN KEY (`vin`) REFERENCES `Vehicle` (`vin`);

ALTER TABLE `Vehicle_Purchase` ADD FOREIGN KEY (`purchase_id`) REFERENCES `Purchase` (`purchase_id`);

ALTER TABLE `Vehicle_Problem` ADD FOREIGN KEY (`vin`) REFERENCES `Vehicle` (`vin`);

ALTER TABLE `Vehicle_Problem` ADD FOREIGN KEY (`problem_id`) REFERENCES `Problem` (`problem_id`);

ALTER TABLE `WarrantyForm_SalesPerson` ADD FOREIGN KEY (`warranty_form_id`) REFERENCES `WarrantyForm` (`warranty_form_id`);

ALTER TABLE `WarrantyForm_SalesPerson` ADD FOREIGN KEY (`employee_id`) REFERENCES `Employee` (`employee_id`);

ALTER TABLE `Vehicle_WarrantyForm` ADD FOREIGN KEY (`warranty_form_id`) REFERENCES `WarrantyForm` (`warranty_form_id`);

ALTER TABLE `Vehicle_WarrantyForm` ADD FOREIGN KEY (`vin`) REFERENCES `Vehicle` (`vin`);

ALTER TABLE `Customers_WarrantyForm` ADD FOREIGN KEY (`warranty_form_id`) REFERENCES `WarrantyForm` (`warranty_form_id`);

ALTER TABLE `Customers_WarrantyForm` ADD FOREIGN KEY (`tax_id`) REFERENCES `Customer` (`tax_id`);

ALTER TABLE `Warranty_Items` ADD FOREIGN KEY (`warranty_id`) REFERENCES `Warranty` (`warranty_id`);

ALTER TABLE `Warranty_Items` ADD FOREIGN KEY (`item_id`) REFERENCES `Items` (`item_id`);

ALTER TABLE `Customer_Payments` ADD FOREIGN KEY (`tax_id`) REFERENCES `Customer` (`tax_id`);

ALTER TABLE `Customer_Payments` ADD FOREIGN KEY (`payment_id`) REFERENCES `Payment` (`payment_id`);

ALTER TABLE `Vehicle_Sale` ADD FOREIGN KEY (`vin`) REFERENCES `Vehicle` (`vin`);

ALTER TABLE `Vehicle_Sale` ADD FOREIGN KEY (`sale_id`) REFERENCES `Sale` (`sale_id`);

ALTER TABLE `Sale_Employee` ADD FOREIGN KEY (`sale_id`) REFERENCES `Sale` (`sale_id`);

ALTER TABLE `Sale_Employee` ADD FOREIGN KEY (`employee_id`) REFERENCES `Employee` (`employee_id`);

ALTER TABLE `Sale_Customer` ADD FOREIGN KEY (`sale_id`) REFERENCES `Sale` (`sale_id`);

ALTER TABLE `Sale_Customer` ADD FOREIGN KEY (`tax_id`) REFERENCES `Customer` (`tax_id`);

ALTER TABLE `Sale_CustomerLocation` ADD FOREIGN KEY (`sale_id`) REFERENCES `Sale` (`sale_id`);

ALTER TABLE `Sale_CustomerLocation` ADD FOREIGN KEY (`location_id`) REFERENCES `Location` (`location_id`);

ALTER TABLE `Customer_EmploymentHistory` ADD FOREIGN KEY (`tax_id`) REFERENCES `Customer` (`tax_id`);

ALTER TABLE `Customer_EmploymentHistory` ADD FOREIGN KEY (`employment_history_id`) REFERENCES `EmploymentHistory` (`employment_history_id`);

COMMIT;