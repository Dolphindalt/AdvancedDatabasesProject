USE rmn_auto;

START TRANSACTION;

CREATE TABLE `Employee` (
  `employee_id` INT PRIMARY KEY NOT NULL,
  `role` VARCHAR(255) NOT NULL,
  `wage` DOUBLE NOT NULL,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `phone_number` VARCHAR(255) NOT NULL,
  `gender` VARCHAR(255) NOT NULL,
  `dob` DATETIME NOT NULL
);

CREATE TABLE `Customer` (
  `tax_id` INT PRIMARY KEY NOT NULL,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `phone_number` VARCHAR(255) NOT NULL,
  `gender` VARCHAR(255) NOT NULL,
  `dob` DATETIME NOT NULL
);

CREATE TABLE `Location` (
  `location_id` INT PRIMARY KEY AUTO_INCREMENT,
  `address` VARCHAR(255) NOT NULL,
  `city` VARCHAR(255) NOT NULL,
  `state` VARCHAR(2) NOT NULL,
  `ZIP` VARCHAR(10) NOT NULL
);

CREATE TABLE `Repair` (
  `repair_id` INT PRIMARY KEY AUTO_INCREMENT,
  `description` VARCHAR(255) NOT NULL,
  `material_cost` DOUBLE NOT NULL,
  `labor_cost` DOUBLE NOT NULL
);

CREATE TABLE `Purchase` (
  `purchase_id` INT PRIMARY KEY AUTO_INCREMENT,
  `date` DATETIME NOT NULL,
  `location_id` INT NOT NULL,
  `is_auction` bit NOT NULL,
  `seller_id` INT NOT NULL,
  `tax_id` INT NOT NULL
);

CREATE TABLE `Vehicle` (
  `vin` VARCHAR(255) PRIMARY KEY NOT NULL,
  `make` VARCHAR(255) NOT NULL,
  `model` VARCHAR(255) NOT NULL,
  `year` INT NOT NULL,
  `color` VARCHAR(255) NOT NULL,
  `miles` DOUBLE NOT NULL,
  `style` VARCHAR(255) NOT NULL,
  `condition` VARCHAR(255) NOT NULL,
  `sold` bit DEFAULT 0 NOT NULL
);

CREATE TABLE `Problem` 
  `problem_id` INT PRIMARY KEY AUTO_INCREMENT,
  `description` VARCHAR(255) NOT NULL,
  `estimated_repair_cost` DOUBLE NOT NULL,
  `actual_repair_cost` DOUBLE NOT NULL
);

CREATE TABLE `WarrantyForm` (
  `warranty_form_id` INT PRIMARY KEY AUTO_INCREMENT,
  `cosigner` VARCHAR(255),
  `date_sold` DATETIME NOT NULL,
  `total_cost` DOUBLE NOT NULL,
  `monthly_cost` DOUBLE NOT NULL
);

CREATE TABLE `Warranty` (
  `warranty_id` INT PRIMARY KEY AUTO_INCREMENT,
  `start_date` DATETIME NOT NULL,
  `end_date` DATETIME NOT NULL,
  `cost` DOUBLE NOT NULL,
  `deductible` DOUBLE
);

CREATE TABLE `Items` (
  `item_id` INT PRIMARY KEY AUTO_INCREMENT,
  `description` VARCHAR(255) NOT NULL
);

CREATE TABLE `Payment` (
  `payment_id` INT PRIMARY KEY AUTO_INCREMENT,
  `due_date` DATETIME NOT NULL,
  `paid_date` DATETIME,
  `amount` DOUBLE NOT NULL,
  `bank_account` INT
);

CREATE TABLE `Sale` (
  `sale_id` INT PRIMARY KEY AUTO_INCREMENT,
  `date` DATETIME NOT NULL,
  `total_due` DOUBLE NOT NULL,
  `down_payment` DOUBLE,
  `financed_amount` DOUBLE
);

CREATE TABLE `EmploymentHistory` (
  `employment_history_id` INT PRIMARY KEY AUTO_INCREMENT,
  `employer` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `supervisor` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `start_date` DATETIME NOT NULL,
  `shadow_deleted` BIT DEFAULT 0 NOT NULL
);

CREATE TABLE `Seller` (
  `seller_tax_id` INT PRIMARY KEY NOT NULL,
  `name` VARCHAR(255) NOT NULL
);

CREATE TABLE `Employee_Location` (
  `employee_id` INT NOT NULL,
  `location_id` INT NOT NULL
);

CREATE TABLE `Customer_Location` (
  `tax_id` INT NOT NULL,
  `location_id` INT NOT NULL
);

CREATE TABLE `Vehicle_Purchase` (
  `vin` VARCHAR(255) NOT NULL,
  `purchase_id` INT NOT NULL,
  `book_price` DOUBLE NOT NULL,
  `paid_price` DOUBLE NOT NULL,
  `color` VARCHAR(255) NOT NULL,
  `miles` DOUBLE NOT NULL,
  `condition` VARCHAR(255) NOT NULL
);

CREATE TABLE `Vehicle_Problem` (
  `vin` VARCHAR(255) NOT NULL,
  `problem_id` INT NOT NULL
);

CREATE TABLE `WarrantyForm_SalesPerson` (
  `warranty_form_id` INT NOT NULL,
  `employee_id` INT NOT NULL
);

CREATE TABLE `Vehicle_WarrantyForm` (
  `warranty_form_id` INT NOT NULL,
  `vin` VARCHAR(255) NOT NULL
);

CREATE TABLE `Vehicle_Warranty` (
  `warranty_id` INT NOT NULL,
  `vin` VARCHAR(255) NOT NULL
);

CREATE TABLE `Customers_WarrantyForm` (
  `warranty_form_id` INT NOT NULL,
  `tax_id` INT NOT NULL
);

CREATE TABLE `Warranty_Items` (
  `warranty_id` INT NOT NULL,
  `item_id` INT NOT NULL
);

CREATE TABLE `Customer_Payments` (
  `tax_id` INT NOT NULL,
  `payment_id` INT NOT NULL
);

CREATE TABLE `Vehicle_Sale` (
  `vin` VARCHAR(255) NOT NULL,
  `sale_id` INT NOT NULL,
  `list_price` DOUBLE NOT NULL,
  `sales_price` DOUBLE NOT NULL,
  `color` VARCHAR(255) NOT NULL,
  `miles` DOUBLE NOT NULL,
  `condition` VARCHAR(255) NOT NULL
);

CREATE TABLE `Sale_Employee` (
  `sale_id` INT NOT NULL,
  `employee_id` INT NOT NULL,
  `employee_commission_percent` DOUBLE NOT NULL
);

CREATE TABLE `Sale_Customer` (
  `sale_id` INT NOT NULL,
  `tax_id` INT NOT NULL
);

CREATE TABLE `Sale_CustomerLocation` (
  `sale_id` INT NOT NULL,
  `location_id` INT NOT NULL
);

CREATE TABLE `Customer_EmploymentHistory` (
  `tax_id` INT NOT NULL,
  `employment_history_id` INT NOT NULL
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