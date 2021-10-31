<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
?>
<form action="warranty_form.php" method="POST">
    <div class='form-row'>
        <h1>Warranty Form</h1>
    </div>
    <div class='form-row'>
        <div class='col'>
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
        <div class='col'>
            <label for='customerID'>Customer</label>
            <select class='form-control' id='customerID' name='customerID'>
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
            <select class="form-control" id="salesPersonTaxID" name="salesPersonTaxID">
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
            <input type="text" class="form-control" name="cosigner" id="cosigner" value="">
        </div>
    </div>
    <div id="warranty-sink">

    </div>
    <div class='form-row'>
        <div class='col'>
            <button type='button' class='btn btn-primary' onclick="growWarrantyForm()">Add Warranty</button>
        </div>
        <div class='col'>
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

        $db->query("START TRANSACTION;");

        $total_cost = 0;
        $monthly_cost = 0;

        for ($w = 1; key_exists('startDate' . $w, $_POST); $w++)
        {
            $startDate = $_POST['startDate' . $w];
            $endDate = $_POST['endDate' . $w];
            $cost = $_POST['cost' . $w];
            $deductible = $_POST['deductible' . $w];

            $date_diff = strtotime($endDate) - strtotime($startDate);
            $day_diff = round($date_diff / (60 * 60 * 24));

            $total_cost += $cost;
            if ($deductible != 0)
                $monthly_cost += ($cost - $deductible) / $day_diff;

            $statement = $db->prepare("INSERT INTO Warranty (start_date, end_date, cost, deductible) VALUES (?, ?, ?, ?);");
            $statement->bindParam(1, $startDate, PDO::PARAM_STR);
            $statement->bindParam(2, $endDate, PDO::PARAM_STR);
            $statement->bindParam(3, $cost, PDO::PARAM_INT);
            $statement->bindParam(4, $deductible, PDO::PARAM_INT);
            $statement->execute();

            for ($i = 1; key_exists('description' . $w . $i, $_POST); $i++)
            {
                $item_id = $w . $i;
                $description = $_POST['description' . $item_id];

                $statement = $db->prepare("INSERT INTO Items (description) VALUES (?);");
                $statement->bindParam(1, $description, PDO::PARAM_STR);
                $statement->execute();
            }
        }

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

        $db->query("COMMIT;");

        ?>
        <div class="alert alert-primary" role="alert">
                Warranties created. Click <a href="view_warranties.php?vin=<?php echo $vin; ?>">here</a> to view warranties on <?php echo $vin; ?>.
        </div>
        <?php
    }
    require_once 'footer.php';
?>