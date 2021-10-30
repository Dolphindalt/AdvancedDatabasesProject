<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
?>

<form action='sales_form.php' method='post'>
    <h1>Sale Form</h1>
    <div class="form-row">
        <div class="col">
            <label for="date">Date</label>
            <input type="date" class="form-control" name="date" id="date" placeholder="">
        </div>
        <div class="col">
            <label for="downPayment">Down Payment</label>
            <input type="text" class="form-control" name="downPayment" id="downPayment" placeholder="">
        </div>
        <div class="col">
            <label for="financedAmount">Financed Amount</label>
            <input type="text" class="form-control" name="financedAmount" id="financedAmount" placeholder="">
        </div>
    </div>
    <div class="form-row">
        <div class="col">
            <label for="salePrice">Sale Price</label>
            <input type="text" class="form-control" name="salePrice" id="salePrice" placeholder="">
        </div>
        <div class="col">
            <label for="listedPrice">Listed Price</label>
            <input type="text" class="form-control" name="listedPrice" id="listedPrice" placeholder="">
        </div>
    </div>
    <div class="form-row">
        <h2>Salesperson</h2>
    </div>
    <div class="form-row">
        <div class="col">
            <label for="salespersonCommission">Commission %</label>
            <input type="number" class="form-control" name="salespersonCommission" id="salespersonCommission" value="25">
        </div>
        <div class="col">
            <label for="salesPersonTaxID">Salesperson</label>
            <select class="form-control" id="salesPersonTaxID" name="salesPersonTaxID">
            <option selected>Choose</option>
            <?php
                foreach ($db->query("SELECT CONCAT(first_name, ' ', last_name) AS name, employee_id AS id FROM Employee WHERE role = 'Salesperson'") as $row) {
                    echo '<option value=' . $row['id'] . '>' . $row['name'] . '</option>';
                }
            ?>
            </select>
        </div>
    </div>
    <div class="form-row">
        <h2>Vehicle and Customer</h2>
    </div>
    <div class="form-row">
        <div class="col">
            <label for="vin">VIN</label>
            <select class="form-control" id="vin" name="vin">
            <option selected>Choose</option>
            <?php
                foreach ($db->query("SELECT vin FROM Vehicle") as $row) {
                    echo '<option value=' . $row['vin'] . '>' . $row['vin'] . '</option>';
                }
            ?>
            </select>
        </div>
        <div class="col">
            <label for="customerID">Customer</label>
            <select class="form-control" id="customerID" name="customerID">
            <option selected>Choose</option>
            <?php
                foreach ($db->query("SELECT CONCAT(first_name, ' ', last_name) AS name, tax_id AS id FROM Customer") as $row) {
                    echo '<option value=' . $row['id'] . '>' . $row['name'] . '</option>';
                }
            ?>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="col">
            <button type="submit" class="btn btn-primary">Generate Sale</button>
        </div>
    </div>
</form>

<?php
    } 
    else if ($method == "POST") 
    {
        $date = $_POST['date'];
        $downPayment = $_POST['downPayment'];
        $financedAmount = $_POST['financedAmount'];
        $totalDue = $downPayment + $financedAmount;
        $salePrice = $_POST['salePrice'];
        $listedPrice = $_POST['listedPrice'];

        $commission = $_POST['salespersonCommission'];
        $salespersonID = $_POST['salesPersonTaxID'];
        $vin = $_POST['vin'];
        $customerID = $_POST['customerID'];

        $db->query("START TRANSACTION;")->execute();

        $statement = $db->prepare("INSERT INTO Sale (date, total_due, down_payment, financed_amount) VALUES (STR_TO_DATE(?, '%Y-%c-%e'), ?, ?, ?);");
        $statement->bindParam(1, $date, PDO::PARAM_STR);
        $statement->bindParam(2, $totalDue, PDO::PARAM_STR);
        $statement->bindParam(3, $downPayment, PDO::PARAM_STR);
        $statement->bindParam(4, $financedAmount, PDO::PARAM_STR);
        $statement->execute();

        $query = $db->query("SELECT LAST_INSERT_ID() AS sale_id;");
        $query->execute();
        $sale_id = $query->fetchAll(PDO::FETCH_ASSOC)[0]['sale_id'];

        $statement = $db->prepare("INSERT INTO Vehicle_Sale (vin, sale_id, list_price, sales_price) VALUES (?, ?, ?, ?);");
        $statement->bindParam(1, $vin, PDO::PARAM_STR);
        $statement->bindParam(2, $sale_id, PDO::PARAM_INT);
        $statement->bindParam(3, $listedPrice, PDO::PARAM_STR);
        $statement->bindParam(4, $salePrice, PDO::PARAM_STR);
        $statement->execute();

        $statement = $db->prepare("INSERT INTO Sale_Employee (sale_id, employee_id, employee_commission_percent) VALUES (?, ?, ?);");
        $statement->bindParam(1, $sale_id, PDO::PARAM_INT);
        $statement->bindParam(2, $salespersonID, PDO::PARAM_INT);
        $statement->bindParam(3, $commission, PDO::PARAM_STR);
        $statement->execute();

        $statement = $db->prepare("INSERT INTO Sale_Customer (sale_id, tax_id) VALUES (?, ?);");
        $statement->bindParam(1, $sale_id, PDO::PARAM_INT);
        $statement->bindParam(2, $customerID, PDO::PARAM_INT);
        $statement->execute();

        $db->query("COMMIT;")->execute();

        ?>
            <div class="alert alert-primary" role="alert">
                Sale record created. Click <a href="view_sale.php/?saleid=<?php echo $sale_id; ?>">here</a> to view it.
            </div>
        <?php
    }
    require_once 'footer.php';
?>