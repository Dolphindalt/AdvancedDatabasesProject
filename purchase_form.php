<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
?>

<h1 style="text-align: center;">Purchase Form</h1>
<form action='purchase_form.php' method='post' style="display: flex; justify-content: space-evenly;">
    <div>
        <div class="form-row">
            <div class="col">
                <h2> Seller Info: </h2>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <label for="date">Date</label>
                <input type="date" class="form-control" name="date" id="date" required>
                <script>
                    document.getElementById('date').value = new Date().toDateInputValue();
                </script>
            </div>
            <div class="col">
                <label for="salesPersonTaxID">Buyer</label>
                <select class="form-control" id="salesPersonTaxID" name="salesPersonTaxID" required>
                <option selected>Choose</option>
                <?php
                    foreach ($db->query("SELECT CONCAT(first_name, ' ', last_name) AS name, employee_id AS id FROM Employee WHERE role = 'Buyer'") as $row) {
                        echo '<option value=' . $row['id'] . '>' . $row['name'] . '</option>';
                    }
                ?>
                </select>
            </div>
            <div class="col">
                <label for="sellerTaxID">Seller</label>
                <select class="form-control" id="sellerTaxID" name="sellerTaxID" required>
                <option selected>Choose</option>
                <?php
                    foreach ($db->query("SELECT seller_tax_id, name FROM Seller") as $row) {
                        $name_string = $row['name'];
                        echo '<option value=' . $row['seller_tax_id'] . '>' . $name_string . '</option>';
                    }
                ?>
                </select>
            </div>
            <div class="col">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="isAuction" name="isAuction">
                    <label class="form-check-label" for="isAuction" style="display: inline-block;">
                        Auction
                    </label>
                </div>
            </div>
            <div class="col">
                <button type="button" class="btn btn-primary" onclick="toggleShowEnterSellerSection()">Enter new seller</button>
            </div>
        </div>
    </div>
    <div>
        <div class="form-row">
            <div class="col">
                <h2> Add Address: </h2>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <label for="street">Street</label>
                <input type="text" class="form-control" name="street" id="street" required>
            </div>
            <div class="col">
                <label for="city">City</label>
                <input type="text" class="form-control" name="city" id="city" required>
            </div>
            <div class="col">
                <label for="state">State</label>
                <input type="text" class="form-control" name="state" id="state" required>
            </div>
            <div class="col">
                <label for="zip">Postal/ZIP Code</label>
                <input type="text" class="form-control" name="zip" id="zip" required>
            </div>
        </div>
        <div id='new-seller-sink' style="display: none;">
            <div class="form-row">
                <div class="col">
                    <h2>Add New Seller</h2>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <label for="newSellerTaxID">Seller Tax ID</label>
                    <input type="text" class="form-control" name="newSellerTaxID" id="newSellerTaxID" >
                </div>
                <div class="col">
                    <label for="newSellerName">Seller Name</label>
                    <input type="text" class="form-control" name="newSellerName" id="newSellerName" >
                </div>
                <div class="col">
                    <button type="button" class="btn btn-primary" onclick="sendAddSeller()">Add Seller</button>
                </div>
            </div>
        </div>
        <div id="purchase-form-sink"></div>
        <div class="form-row">
            <div class="col">
                <button type="button" class="btn btn-primary" onclick="growPurchaseForm()">Add Purchase</button>
                <button type="submit" class="btn btn-primary" >Submit</button>
            </div>
        </div>
    </div>
</form>

<?php
    }
    else if ($method == "POST")
    {
        $db->query("START TRANSACTION;");

        // Global fields 
        $seller_tax_id = $_POST['sellerTaxID'];
        $employee_tax_id = $_POST['salesPersonTaxID'];
        $date = $_POST['date'];
        $isAuction = key_exists('isAuction', $_POST) ? $_POST['isAuction'] : 0;

        $street = $_POST['street'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];

        $statement = $db->prepare("INSERT INTO Location (address, city, state, zip) VALUES (?, ?, ?, ?);");
        $statement->bindParam(1, $street, PDO::PARAM_STR);
        $statement->bindParam(2, $city, PDO::PARAM_STR);
        $statement->bindParam(3, $state, PDO::PARAM_STR);
        $statement->bindParam(4, $zip, PDO::PARAM_STR);
        $statement->execute();

        $statement = $db->prepare("INSERT INTO Purchase (date, location_id, is_auction, seller_id, tax_id) VALUES (STR_TO_DATE(?, '%Y-%c-%e'), LAST_INSERT_ID(), ?, ?, ?)");
        $statement->bindParam(1, $date, PDO::PARAM_STR);
        $statement->bindParam(2, $isAuction, PDO::PARAM_BOOL);
        $statement->bindParam(3, $seller_tax_id, PDO::PARAM_STR);
        $statement->bindParam(4, $employee_tax_id, PDO::PARAM_STR);
        $statement->execute();

        $query = $db->query("SELECT LAST_INSERT_ID() AS purchase_id;");
        $query->execute();
        $purchase_id = $query->fetchAll(PDO::FETCH_ASSOC)[0]['purchase_id'];

        // Purchase fields
        for ($p = 1; isset($_POST['vin' . $p]); $p++)
        {
            $vin = $_POST['vin' . $p];
            $bookPrice = $_POST['bookPrice' . $p];
            $actualPrice = $_POST['actualPrice' . $p];
            $make = $_POST['make' . $p];
            $model = $_POST['model' . $p];
            $year = $_POST['year' . $p];
            $color = $_POST['color' . $p];
            $miles = $_POST['miles' . $p];
            $style = $_POST['style' . $p];
            $condition = $_POST['condition' . $p];

            $statement = $db->prepare("INSERT INTO Vehicle (`vin`, `make`, `model`, `year`, `color`, `miles`, `style`, `condition`, sold) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)
            ON DUPLICATE KEY UPDATE `make` = VALUES(`make`), `model` = VALUES(`model`), `year` = VALUES(`year`), 
            `color` = VALUES(`color`), `miles` = VALUES(`miles`), `style` = VALUES(`style`), `condition` = VALUES(`condition`), sold = 0;");
            $statement->bindParam(1, $vin, PDO::PARAM_STR);
            $statement->bindParam(2, $make, PDO::PARAM_STR);
            $statement->bindParam(3, $model, PDO::PARAM_STR);
            $statement->bindParam(4, $year, PDO::PARAM_INT);
            $statement->bindParam(5, $color, PDO::PARAM_STR);
            $statement->bindParam(6, $miles, PDO::PARAM_STR);
            $statement->bindParam(7, $style, PDO::PARAM_STR);
            $statement->bindParam(8, $condition, PDO::PARAM_STR);
            $statement->execute();

            $statement = $db->prepare("INSERT INTO Vehicle_Purchase (vin, purchase_id, book_price, paid_price, color, miles, `condition`) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $statement->bindParam(1, $vin, PDO::PARAM_STR);
            $statement->bindParam(2, $purchase_id, PDO::PARAM_INT);
            $statement->bindParam(3, $bookPrice, PDO::PARAM_STR);
            $statement->bindParam(4, $actualPrice, PDO::PARAM_STR);
            $statement->bindParam(5, $color, PDO::PARAM_STR);
            $statement->bindParam(6, $miles, PDO::PARAM_STR);
            $statement->bindParam(7, $condition, PDO::PARAM_STR);
            $statement->execute();

            // Optional vehicle problems fields 
            for ($i = 1; key_exists('problemDescription' . $p . $i, $_POST); $i++)
            {
                $pid = "" . $p . $i;
                $estimatedCost = $_POST['problemCost' . $pid];
                $description = $_POST['problemDescription' . $pid];

                $statement = $db->prepare("INSERT INTO Problem (description, estimated_repair_cost) VALUES (?, ?)");
                $statement->bindParam(1, $description, PDO::PARAM_STR);
                $statement->bindParam(2, $estimatedCost, PDO::PARAM_STR);
                $statement->execute();

                $query = $db->query("SELECT LAST_INSERT_ID() AS problem_id;");
                $query->execute();
                $problem_id = $query->fetchAll(PDO::FETCH_ASSOC)[0]['problem_id'];

                $statement = $db->prepare("INSERT INTO Vehicle_Problem (vin, problem_id) VALUES (?, ?)");
                $statement->bindParam(1, $vin, PDO::PARAM_INT);
                $statement->bindParam(2, $problem_id, PDO::PARAM_INT);
                $statement->execute();
            }
        }

        $db->query("COMMIT;");
        echo "Purchase completed.";
    }
    require_once 'footer.php';
?>