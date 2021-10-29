USE rmn_auto;

START TRANSACTION;

INSERT INTO Seller (seller_tax_id, name) VALUES (1594234, "John Truck Repo");
INSERT INTO Seller (seller_tax_id, name) VALUES (7952342, "Daisies Dealers");
INSERT INTO Seller (seller_tax_id, name) VALUES (6832999, "Junkyard Auto Restorations");

COMMIT;