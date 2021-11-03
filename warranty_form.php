<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
?>
<h1>Warranty Form</h1>
<form action="warranty_form.php" method="POST" style="padding-left: 2em;">
    <div class='form-row'>
        <h2>Warranty Infomation:</h2>
    </div>
    <div class='form-row'>
        <div class='col'>
            <label for="vin">VIN</label>
            <select class="form-control" id="vin" name="vin" required>
            <option selected>Choose</option>
            <?php
                foreach ($db->query("SELECT vin FROM Vehicle") as $row) {
                    echo '<option value=' . $row['vin'] . '>' . $row['vin'] . '</option>';
                }
            ?>
            </select>
        </div>
        <div class='col'>
            <label for='customerID'>Customer</label>
            <select class='form-control' id='customerID' name='customerID' required>
            <option selected>Choose</option>
            <?php
                foreach ($db->query("SELECT CONCAT(first_name, ' ', last_name) AS name, tax_id AS id FROM Customer") as $row) {
                    echo '<option value=' . $row['id'] . '>' . $row['name'] . '</option>';
                }
            ?>
            </select>
        </div>
        <div class='col'>
            <label for="salesPersonTaxID">Salesperson</label>
            <select class="form-control" id="salesPersonTaxID" name="salesPersonTaxID" required>
            <option selected>Choose</option>
            <?php
                foreach ($db->query("SELECT CONCAT(first_name, ' ', last_name) AS name, employee_id AS id FROM Employee WHERE role = 'Salesperson'") as $row) {
                    echo '<option value=' . $row['id'] . '>' . $row['name'] . '</option>';
                }
            ?>
            </select>
        </div>
        <div class="col">
            <label for="cosigner">Cosigner</label>
            <input type="text" class="form-control" name="cosigner" id="cosigner" value="" required>
        </div>
        <div class="col">
            <label for="installments">Monthly Installments</label>
            <input type="number" class="form-control" name="installments" id="installments" value="12" required>
        </div>
    </div>
    <div id="warranty-sink">

    </div>
    <div class='form-row'>
        <div class='col' style="display: inline-block;">
            <button type='button' class='btn btn-primary' onclick="growWarrantyForm()">Add Warranty</button>
        </div>
        <div class='col' style="display: inline-block;">
            <button type='submit' class='btn btn-primary'>Generate</button>
        </div>
    </div>
</form>
<?php 
    }
    else if ($method == "POST")
    {
        $vin = $_POST['vin'];
        $customerID = $_POST['customerID'];
        $salesPersonTaxID = $_POST['salesPersonTaxID'];
        $cosigner = $_POST['cosigner'];
        $installments = $_POST['installments'];

        $db->query("START TRANSACTION;");

        $total_cost = 0;

        for ($w = 1; key_exists('startDate' . $w, $_POST); $w++)
        {
            $startDate = $_POST['startDate' . $w];
            $endDate = $_POST['endDate' . $w];
            $cost = $_POST['cost' . $w];
            $deductible = $_POST['deductible' . $w];

            $total_cost += ($cost - $deductible);

            $statement = $db->prepare("INSERT INTO Warranty (start_date, end_date, cost, deductible) VALUES (?, ?, ?, ?);");
            $statement->bindParam(1, $startDate, PDO::PARAM_STR);
            $statement->bindParam(2, $endDate, PDO::PARAM_STR);
            $statement->bindParam(3, $cost, PDO::PARAM_INT);
            $statement->bindParam(4, $deductible, PDO::PARAM_INT);
            $statement->execute();

            $query = $db->query("SELECT LAST_INSERT_ID() AS warranty_id;");
            $query->execute();
            $warranty_id = $query->fetchAll(PDO::FETCH_ASSOC)[0]['warranty_id'];

            $statement = $db->prepare("INSERT INTO Vehicle_Warranty (warranty_id, vin) VALUES (?, ?);");
            $statement->bindParam(1, $warranty_id, PDO::PARAM_INT);
            $statement->bindParam(2, $vin, PDO::PARAM_STR);
            $statement->execute();

            for ($i = 1; key_exists('description' . $w . $i, $_POST); $i++)
            {
                $item_id = $w . $i;
                $description = $_POST['description' . $item_id];

                $statement = $db->prepare("INSERT INTO Items (description) VALUES (?);");
                $statement->bindParam(1, $description, PDO::PARAM_STR);
                $statement->execute();

                $statement = $db->prepare("INSERT INTO Warranty_Items (warranty_id, item_id) VALUES (?, LAST_INSERT_ID());");
                $statement->bindParam(1, $warranty_id, PDO::PARAM_INT);
                $statement->execute();
            }
        }

        $monthly_cost = floatval($total_cost) / floatval($installments);

        $nice_date = date('Y-m-d');
        $statement = $db->prepare("INSERT INTO WarrantyForm (cosigner, date_sold, total_cost, monthly_cost) VALUES (?, ?, ?, ?);");
        $statement->bindParam(1, $cosigner, PDO::PARAM_STR);
        $statement->bindParam(2, $nice_date, PDO::PARAM_STR);
        $statement->bindParam(3, $total_cost, PDO::PARAM_INT);
        $statement->bindParam(4, $monthly_cost, PDO::PARAM_INT);
        $statement->execute();

        $query = $db->query("SELECT LAST_INSERT_ID() AS warranty_form_id;");
        $query->execute();
        $warranty_form_id = $query->fetchAll(PDO::FETCH_ASSOC)[0]['warranty_form_id'];

        $statement = $db->prepare("INSERT INTO WarrantyForm_SalesPerson (warranty_form_id, employee_id) VALUES (?, ?);");
        $statement->bindParam(1, $warranty_form_id, PDO::PARAM_INT);
        $statement->bindParam(2, $salesPersonTaxID, PDO::PARAM_INT);
        $statement->execute();

        $statement = $db->prepare("INSERT INTO Vehicle_WarrantyForm (warranty_form_id, vin) VALUES (?, ?);");
        $statement->bindParam(1, $warranty_form_id, PDO::PARAM_INT);
        $statement->bindParam(2, $vin, PDO::PARAM_STR);
        $statement->execute();

        $statement = $db->prepare("INSERT INTO Customers_WarrantyForm (warranty_form_id, tax_id) VALUES (?, ?);");
        $statement->bindParam(1, $warranty_form_id, PDO::PARAM_INT);
        $statement->bindParam(2, $customerID, PDO::PARAM_STR);
        $statement->execute();

        // Generate financed payments.
        $now = time();
        if ($monthly_cost > 0) {
            for ($i = 0; $i < $installments; $i++) {
                $now = strtotime("+1 month", $now);
                $nice_date = date('Y-m-d', $now);
                $statement = $db->prepare("INSERT INTO Payment (due_date, amount) VALUES (?, ?);");
                $statement->bindParam(1, $nice_date, PDO::PARAM_STR);
                $statement->bindParam(2, $monthly_cost, PDO::PARAM_INT);
                $statement->execute();

                $query = $db->query("SELECT LAST_INSERT_ID() AS payment_id;");
                $query->execute();
                $payment_id = $query->fetchAll(PDO::FETCH_ASSOC)[0]['payment_id'];

                $statement = $db->prepare("INSERT INTO Customer_Payments (tax_id, payment_id) VALUES (?, ?);");
                $statement->bindParam(1, $customerID, PDO::PARAM_INT);
                $statement->bindParam(2, $payment_id, PDO::PARAM_INT);
                $statement->execute();
            }
        }

        $db->query("COMMIT;");

        ?>
        <div class="alert alert-primary" role="alert">
                Warranties created. Click <a href="view_vehicle.php?vin=<?php echo $vin; ?>">here</a> to view warranties on <?php echo $vin; ?>.
        </div>
        <?php
    }
    require_once 'footer.php';
?>