<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
        if (!key_exists('saleid', $_GET)) {
            http_response_code(400);
            exit;
        }

        $sale_id = $_GET['saleid'];

        $statement = $db->prepare("SELECT *, c.first_name AS cfirst, c.last_name AS clast,
            e.first_name AS efirst, e.last_name AS elast, s.sale_id, s.total_due, 
            s.date, v.vin FROM Sale AS s 
            LEFT JOIN Vehicle_Sale AS vs ON vs.sale_id = s.sale_id 
            LEFT JOIN Sale_Employee AS se ON se.sale_id = s.sale_id
            LEFT JOIN Sale_Customer AS sc ON sc.sale_id = s.sale_id
            LEFT JOIN Employee AS e ON e.employee_id = se.employee_id
            LEFT JOIN Customer AS c ON c.tax_id = sc.tax_id
            LEFT JOIN Vehicle AS v ON v.vin = vs.vin 
            WHERE s.sale_id = ? 
            ORDER BY s.date DESC;");
        $statement->bindParam(1, $sale_id, PDO::PARAM_INT);
        $statement->execute();
        $info = $statement->fetchAll(PDO::FETCH_ASSOC)[0];
?>
<h1>Sales Form</h1>
<form action='sales_form.php' method='post' style="display: flex; justify-content: space-evenly;">
    <div>
        <h2>Sale Information:</h2>
        <div class="form-row">
            <div class="col">
                <label for="date">Date</label>
                <input type="date" class="form-control" name="date" id="date" value="<?php echo date('Y-m-d', strtotime($info['date'])); ?>" readonly>
                <script>
                    document.getElementById('date').value = new Date().toDateInputValue();
                </script>
            </div>
            <div class="col">
                <label for="totalDue">Total Due</label>
                <input type="text" class="form-control" name="totalDue" id="totalDue" value="<?php echo $info['total_due']; ?>" readonly>
            </div>
            <div class="col">
                <label for="downPayment">Down Payment</label>
                <input type="text" class="form-control" name="downPayment" id="downPayment" value="<?php echo $info['down_payment']; ?>" readonly>
            </div>
            <div class="col">
                <label for="financedAmount">Financed Amount</label>
                <input type="text" class="form-control" name="financedAmount" id="financedAmount" value="<?php echo $info['financed_amount']; ?>" readonly>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <label for="salePrice">Sale Price</label>
                <input type="text" class="form-control" name="salePrice" id="salePrice" value="<?php echo $info['sales_price']; ?>" readonly>
            </div>
            <div class="col">
                <label for="listedPrice">Listed Price</label>
                <input type="text" class="form-control" name="listedPrice" id="listedPrice" value="<?php echo $info['list_price']; ?>" readonly>
            </div>
        </div>
        <div class="form-row">
            <h2>Salesperson:</h2>
        </div>
        <div class="form-row">
            <div class="col">
                <label for="salespersonCommission">Commission</label>
                <input type="number" class="form-control" name="salespersonCommission" id="salespersonCommission" value="<?php echo $info['employee_commission_percent']; ?>" onchange="onCommissionChange()" readonly>
            </div>
            <div class="col">
                <label for="salesPersonTaxID">Salesperson</label>
                <select class="form-control" id="salesPersonTaxID" name="salesPersonTaxID" readonly disabled>
                <option selected>Choose</option>
                <?php
                    foreach ($db->query("SELECT CONCAT(first_name, ' ', last_name) AS name, employee_id AS id FROM Employee WHERE role = 'Salesperson'") as $row) {
                        if ($info['employee_id'] != $row['id']) {
                            echo '<option value=' . $row['id'] . '>' . $row['name'] . '</option>';
                        } else {
                            echo '<option selected="selected" value=' . $row['id'] . '>' . $row['name'] . '</option>';
                        }
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
                <label for="vin">VIN</label>
                <select class="form-control" id="vin" name="vin" onchange="onVinChange(event)" readonly disabled>
                <option selected>Choose</option>
                <?php
                    foreach ($db->query("SELECT vin FROM Vehicle") as $row) {
                        if ($info['vin'] != $row['vin']) {
                            echo '<option value=' . $row['vin'] . '>' . $row['vin'] . '</option>';
                        } else {
                            echo '<option selected="selected" value=' . $row['vin'] . '>' . $row['vin'] . '</option>';
                        }
                    }
                ?>
                </select>
                <script>
                    function onVinChange(event) {
                        if (event.target.value != "Choose") {
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
                    }
                </script>
            </div>
            <div class='col'>
                <label for="vehicleColor">Color</label>
                <input type="text" class="form-control" name="vehicleColor" id="vehicleColor" value="<?php echo $info['color']; ?>" readonly>
            </div>
            <div class='col'>
                <label for="vehicleMiles">Miles</label>
                <input type="number" class="form-control" name="vehicleMiles" id="vehicleMiles" value="<?php echo $info['miles']; ?>" readonly>
            </div>
            <div class='col'>
                <label for="vehicleCondition">Condition</label>
                <input type="text" class="form-control" name="vehicleCondition" id="vehicleCondition" value="<?php echo $info['condition']; ?>" readonly>
            </div>
        </div>
        <div class="form-row">
            <h2>Customer:</h2>
        </div>
        <div class="form-row">
            <div class="col">
                <label for="customerID">Customer</label>
                <select class="form-control" id="customerID" name="customerID" onchange="customerChanged(event)" readonly disabled>
                <option selected>Choose</option>
                <?php
                    foreach ($db->query("SELECT CONCAT(first_name, ' ', last_name) AS name, tax_id AS id FROM Customer") as $row) {
                        if ($row['id'] != $info['tax_id']) {
                            echo '<option value=' . $row['id'] . '>' . $row['name'] . '</option>';
                        } else {
                            echo '<option selected="selected" value=' . $row['id'] . '>' . $row['name'] . '</option>';
                        }
                    }
                ?>
                </select>
                <script>
                    function customerChanged(event) {
                        $.ajax({
                            url: "customer.php",
                            type: "POST",
                            data: { 
                                tax_id: event.target.value
                            },
                            success: function(data, status, xhr) {
                                document.getElementById("customerPhone").value = data.phone_number;
                                document.getElementById("viewemphist").href = "view_employment_history.php?taxid=" + data.tax_id;
                            },
                            error: function(jqXhr, textStatus, errorMessage) {
                                showSnackbar(vagueError);
                            }
                        });
                    }
                </script>
            </div>
            <div class='col'>
                <label for="customerPhone">Phone Number</label>
                <input type="tel" class="form-control" name="customerPhone" id="customerPhone" value="<?php echo $info['phone_number']; ?>" readonly>
            </div>
            
        </div>
        <br/>
        <div class="form-row">
            <div class='col'>
                <a id="viewemphist" class="btn btn-primary" role="button" href="view_employment_history.php?taxid=<?php echo $info['tax_id']; ?>">View Employment History</a>
            </div>
            <div class='col'>
                <a id="viewemphist" class="btn btn-primary" role="button" href="view_customer_payments.php?taxid=<?php echo $info['tax_id']; ?>">View Payment History</a>
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
        $commission = $_POST['salespersonCommission'];
        $totalDue = $downPayment + $financedAmount + $commission;
        $salePrice = $_POST['salePrice'];
        $listedPrice = $_POST['listedPrice'];
        $installments = $_POST['installments'];

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

        $statement = $db->prepare("SELECT location_id FROM Sale_CustomerLocation WHERE sale_id = ?;");
        $statement->bindParam(1, $sale_id, PDO::PARAM_INT);
        $statement->execute();
        $location_id = $statement->fetchAll(PDO::FETCH_ASSOC)[0]['location_id'];

        $statement = $db->prepare("INSERT INTO Sale_CustomerLocation (sale_id, location_id) VALUES (?, ?);");
        $statement->bindParam(1, $sale_id, PDO::PARAM_INT);
        $statement->bindParam(2, $customerID, PDO::PARAM_INT);
        $statement->execute();

        // Generate financed payments.
        $monthly_cost = floatval($totalDue - $downPayment) / floatval($installments);
        $now = time();
        if ($monthly_cost > 0) {
            for ($i = 0; $i < $installments; $i++) {
                $now = strtotime("+1 month", $now);
                $nice_date = date('Y-m-d', $now);
                $statement = $db->prepare("INSERT INTO Payment (due_date, amount) VALUES (?, ?);");
                $statement->bindParam(1, $nice_date, PDO::PARAM_STR);
                $statement->bindParam(2, $monthly_cost, PDO::PARAM_STR);
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

        $db->query("COMMIT;")->execute();

        ?>
            <div class="alert alert-primary" role="alert">
                Sale record created. Click <a href="view_sale.php?saleid=<?php echo $sale_id; ?>">here</a> to view it.
            </div>
        <?php
    }
    require_once 'footer.php';
?>