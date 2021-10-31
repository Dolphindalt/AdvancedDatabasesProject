<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
?>
<h1>Sales Form</h1>
<form action='sales_form.php' method='post' style="display: flex; justify-content: space-evenly;">
    <div>
        <h2>Sale Infomation:</h2>
        <div class="form-row">
            <div class="col">
                <label for="date">Date</label>
                <input type="date" class="form-control" name="date" id="date" placeholder="" required>
                <script>
                    document.getElementById('date').value = new Date().toDateInputValue();
                </script>
            </div>
            <div class="col">
                <label for="downPayment">Down Payment</label>
                <input type="text" class="form-control" name="downPayment" id="downPayment" placeholder="" required>
            </div>
            <div class="col">
                <label for="financedAmount">Financed Amount</label>
                <input type="text" class="form-control" name="financedAmount" id="financedAmount" placeholder="" required>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <label for="salePrice">Sale Price</label>
                <input type="text" class="form-control" name="salePrice" id="salePrice" placeholder="" required>
            </div>
            <div class="col">
                <label for="listedPrice">Listed Price</label>
                <input type="text" class="form-control" name="listedPrice" id="listedPrice" placeholder="" required>
            </div>
        </div>
        <div class="form-row">
            <h2>Salesperson:</h2>
        </div>
        <div class="form-row">
            <div class="col">
                <label for="salespersonCommission">Commission %</label>
                <input type="number" class="form-control" name="salespersonCommission" id="salespersonCommission" value="25" required>
            </div>
            <div class="col">
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
        </div>
    </div>
    <div>
        <div class="form-row">
            <h2>Vehicle Information:</h2>
        </div>
        <div class="form-row">
            <div class="col">
                <script>
                    function onVinChange(event) {
                        $.ajax({
                            url: "vehicle.php",
                            type: "POST",
                            data: { 
                                vin: event.target.value
                            },
                            success: function(data, status, xhr) {
                                document.getElementById("vehicleColor").value = data.color;
                                document.getElementById("vehicleMiles").value = data.miles;
                                document.getElementById("vehicleCondition").value = data.condition;
                            },
                            error: function(jqXhr, textStatus, errorMessage) {
                                showSnackbar(vagueError);
                            }
                        });
                    }
                </script>
                <label for="vin">VIN</label>
                <select class="form-control" id="vin" name="vin" onchange="onVinChange(event)" required>
                <option selected>Choose</option>
                <?php
                    foreach ($db->query("SELECT vin FROM Vehicle WHERE sold = 0") as $row) {
                        echo '<option value=' . $row['vin'] . '>' . $row['vin'] . '</option>';
                    }
                ?>
                </select>
            </div>
            <div class='col'>
                <label for="vehicleColor">Color</label>
                <input type="text" class="form-control" name="vehicleColor" id="vehicleColor" value="" required>
            </div>
            <div class='col'>
                <label for="vehicleMiles">Miles</label>
                <input type="number" class="form-control" name="vehicleMiles" id="vehicleMiles" value="" required>
            </div>
            <div class='col'>
                <label for="vehicleCondition">Condition</label>
                <input type="text" class="form-control" name="vehicleCondition" id="vehicleCondition" value="" required>
            </div>
        </div>
        <div class="form-row">
            <h2>Customer:</h2>
        </div>
        <div class="form-row">
            <div class="col">
                <label for="customerID">Customer</label>
                <select class="form-control" id="customerID" name="customerID" required>
                <option selected>Choose</option>
                <?php
                    foreach ($db->query("SELECT CONCAT(first_name, ' ', last_name) AS name, tax_id AS id FROM Customer") as $row) {
                        echo '<option value=' . $row['id'] . '>' . $row['name'] . '</option>';
                    }
                ?>
                </select>
            </div>
        </div>
        <br/>
        <div class="form-row">
            <div class="col">
                <button type="submit" class="btn btn-primary">Generate Sale</button>
            </div>
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

        $statement = $db->prepare("SELECT color, miles, `condition` FROM Vehicle WHERE vin = ?;");
        $statement->bindParam(1, $vin, PDO::PARAM_STR);
        $statement->execute();
        $vehicle_info = $statement->fetchAll(PDO::FETCH_ASSOC)[0];
        $color = $vehicle_info['color'];
        $miles = $vehicle_info['miles'];
        $condition = $vehicle_info['condition'];

        $statement = $db->prepare("INSERT INTO Vehicle_Sale (vin, sale_id, list_price, sales_price, color, miles, `condition`) VALUES (?, ?, ?, ?, ?, ?, ?);");
        $statement->bindParam(1, $vin, PDO::PARAM_STR);
        $statement->bindParam(2, $sale_id, PDO::PARAM_INT);
        $statement->bindParam(3, $listedPrice, PDO::PARAM_STR);
        $statement->bindParam(4, $salePrice, PDO::PARAM_STR);
        $statement->bindParam(5, $color, PDO::PARAM_STR);
        $statement->bindParam(6, $miles, PDO::PARAM_STR);
        $statement->bindParam(7, $condition, PDO::PARAM_STR);
        $statement->execute();

        $statement = $db->prepare("UPDATE Vehicle SET sold = 1 WHERE vin = ?;");
        $statement->bindParam(1, $vin, PDO::PARAM_STR);
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
                Sale record created. Click <a href="view_sale.php?saleid=<?php echo $sale_id; ?>">here</a> to view it.
            </div>
        <?php
    }
    require_once 'footer.php';
?>