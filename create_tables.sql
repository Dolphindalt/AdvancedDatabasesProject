USE rmn_auto;

START TRANSACTION;

CREATE TABLE `Employee` (
  `employee_id` int PRIMARY KEY,
  `role` varchar(255),
  `wage` double,
  `first_name` varchar(255),
  `last_name` varchar(255),
  `phone_number` varchar(255),
  `gender` varchar(255),
  `dob` datetime
);

CREATE TABLE `Customer` (
  `tax_id` int PRIMARY KEY,
  `first_name` varchar(255),
  `last_name` varchar(255),
  `phone_number` varchar(255),
  `gender` varchar(255),
  `dob` datetime
);

CREATE TABLE `Location` (
  `location_id` int PRIMARY KEY,
  `address` varchar(255),
  `city` varchar(255),
  `state` char,
  `ZIP` char
);

CREATE TABLE `Repair` (
  `repair_id` int PRIMARY KEY,
  `description` varchar(255),
  `material_cost` double,
  `labor_cost` double
);

CREATE TABLE `Purchase` (
  `purchase_id` int PRIMARY KEY,
  `date` datetime,
  `location_id` int,
  `is_auction` bit,
  `seller_id` int,
  `tax_id` int
);

CREATE TABLE `Vehicle` (
  `vin` varchar(255) PRIMARY KEY,
  `make` varchar(255),
  `model` varchar(255),
  `year` int,
  `color` varchar(255),
  `miles` double,
  `style` varchar(255),
  `condition` varchar(255)
);

CREATE TABLE `Problem` (
  `problem_id` int PRIMARY KEY,
  `description` varchar(255),
  `estimated_repair_cost` double,
  `actual_repair_cost` double
);

CREATE TABLE `WarrantyForm` (
  `warranty_form_id` int PRIMARY KEY,
  `cosigner` varchar(255),
  `date_sold` datetime,
  `total_cost` double,
  `monthly_cost` double
);

CREATE TABLE `Warranty` (
  `warranty_id` int PRIMARY KEY,
  `start_date` datetime,
  `length` int,
  `cost` double,
  `deductible` double
);

CREATE TABLE `Items` (
  `item_id` int PRIMARY KEY,
  `description` varchar(255)
);

CREATE TABLE `Payment` (
  `payment_id` int PRIMARY KEY,
  `payment_date` datetime,
  `due_date` datetime,
  `paid_date` datetime,
  `amount` double,
  `bank_account` int
);

CREATE TABLE `Sale` (
  `sale_id` int PRIMARY KEY,
  `date` datetime,
  `total_due` double,
  `down_payment` double,
  `financed_amount` double
);

CREATE TABLE `EmployementHistory` (
  `employement_history_id` int PRIMARY KEY,
  `employer` varchar(255),
  `title` varchar(255),
  `supervisor` varchar(255),
  `phone` varchar(255),
  `address` varchar(255),
  `start_date` datetime
);

CREATE TABLE `Employee_Location` (
  `employee_id` int,
  `location_id` int
);

CREATE TABLE `Customer_Location` (
  `tax_id` int,
  `location_id` int
);

CREATE TABLE `Vehicle_Purchase` (
  `vin` varchar(255),
  `purchase_id` int,
  `book_price` double,
  `paid_price` double
);

CREATE TABLE `Vehicle_Problem` (
  `vin` varchar(255),
  `problem_id` int
);

CREATE TABLE `WarrantyForm_SalesPerson` (
  `warranty_form_id` int,
  `employee_id` int
);

CREATE TABLE `Vehicle_WarrantyForm` (
  `warranty_form_id` int,
  `vin` varchar(255)
);

CREATE TABLE `Customers_WarrantyForm` (
  `warranty_form_id` int,
  `tax_id` int
);

CREATE TABLE `Warranty_Items` (
  `warranty_id` int,
  `item_id` int
);

CREATE TABLE `Customer_Payments` (
  `tax_id` int,
  `payment_id` int,
  `total_late_payments` int,
  `average_number_of_late_days` int
);

CREATE TABLE `Vehicle_Sale` (
  `vin` varchar(255),
  `sale_id` int,
  `list_price` double,
  `sales_price` double
);

CREATE TABLE `Sale_Employee` (
  `sale_id` int,
  `employee_id` int,
  `employee_commission_percent` double
);

CREATE TABLE `Sale_Customer` (
  `sale_id` int,
  `tax_id` int
);

CREATE TABLE `Sale_CustomerLocation` (
  `sale_id` int,
  `location_id` int
);

CREATE TABLE `Customer_EmploymentHistory` (
  `tax_id` int,
  `employement_history_id` int
);

ALTER TABLE `Purchase` ADD FOREIGN KEY (`location_id`) REFERENCES `Location` (`location_id`);

ALTER TABLE `Purchase` ADD FOREIGN KEY (`seller_id`) REFERENCES `Employee` (`employee_id`);

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

ALTER TABLE `Customer_EmploymentHistory` ADD FOREIGN KEY (`employement_history_id`) REFERENCES `EmployementHistory` (`employement_history_id`);

COMMIT;