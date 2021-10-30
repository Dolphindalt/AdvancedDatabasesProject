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

        $statement = $db->prepare("CALL getSalesForm(?);");
        $statement->bindParam(1, $sale_id, PDO::PARAM_INT);
        $statement->execute();
        $info = $statement->fetchAll(PDO::FETCH_ASSOC)[0];

        $commission = $info['commission'];
        $list_price = $info['list_price'];
        $sales_price = $info['sales_price'];
        $date = date('Y-m-d', strtotime($info['date']));
        $total_due = $info['total_due'];
        $down_payment = $info['down_payment'];
        $financed_amount = $info['financed_amount'];
        $employee_first_name = $info['employee_first_name'];
        $employee_last_name = $info['employee_last_name'];
        $customer_first_name = $info['customer_first_name'];
        $customer_last_name = $info['customer_last_name'];
        $customer_phone_number = $info['customer_phone_number'];
        $vin = $info['vin'];
        $miles = $info['miles'];
        $condition = $info['condition'];
        $style = $info['style'];
        $color = $info['color'];
?>

<form action='view_sale.php' method='post'>
    <h1>Sale Information</h1>
    <div class="form-row">
        <div class="col">
            <label for="date">Date</label>
            <input type="date" class="form-control" name="date" id="date" value="<?php echo $date; ?>" readonly>
        </div>
        <div class="col">
            <label for="totalDue">Total Due</label>
            <input type="text" class="form-control" name="totalDue" id="totalDue" value="<?php echo $total_due; ?>" readonly>
        </div>
        <div class="col">
            <label for="downPayment">Down Payment</label>
            <input type="text" class="form-control" name="downPayment" id="downPayment" value="<?php echo $down_payment; ?>" readonly>
        </div>
        <div class="col">
            <label for="financedAmount">Financed Amount</label>
            <input type="text" class="form-control" name="financedAmount" id="financedAmount" value="<?php echo $financed_amount; ?>" readonly>
        </div>
    </div>
    <div class="form-row">
        <div class="col">
            <label for="salePrice">Sale Price</label>
            <input type="text" class="form-control" name="salePrice" id="salePrice" value="<?php echo $sales_price; ?>" readonly>
        </div>
        <div class="col">
            <label for="listedPrice">Listed Price</label>
            <input type="text" class="form-control" name="listedPrice" id="listedPrice" value="<?php echo $list_price; ?>" readonly>
        </div>
    </div>
    <div class="form-row">
        <h2>Salesperson</h2>
    </div>
    <div class="form-row">
        <div class="col">
            <label for="">First Name</label>
            <input type="text" class="form-control" name="" id="" value="<?php echo $employee_first_name; ?>" readonly>
        </div>
        <div class="col">
            <label for="">Last Name</label>
            <input type="text" class="form-control" name="" id="" value="<?php echo $employee_last_name; ?>" readonly>
        </div>
        <div class="col">
            <label for="salespersonCommission">Commission %</label>
            <input type="number" class="form-control" name="salespersonCommission" id="salespersonCommission" value="<?php echo $commission; ?>" readonly>
        </div>
    </div>
    <div class="form-row">
        <h2>Customer Information</h2>
    </div>
    <div class="form-row">
        <div class="col">
            <label for="">First Name</label>
            <input type="text" class="form-control" name="" id="" value="<?php echo $customer_first_name; ?>" readonly>
        </div>
        <div class="col">
            <label for="">Last Name</label>
            <input type="text" class="form-control" name="" id="" value="<?php echo $customer_last_name; ?>" readonly>
        </div>
        <div class="col">
            <label for="">Phone Number</label>
            <input type="text" class="form-control" name="" id="" value="<?php echo $customer_phone_number; ?>" readonly>
        </div>
    </div>
    <div class="form-row">
        <h2>Vehicle Information</h2>
    </div>
    <div class="form-row">
        <div class="col">
            <label for="">VIN</label>
            <input type="text" class="form-control" name="" id="" value="<?php echo $vin; ?>" readonly>
        </div>
        <div class="col">
            <label for="">Miles</label>
            <input type="text" class="form-control" name="" id="" value="<?php echo $miles; ?>" readonly>
        </div>
        <div class="col">
            <label for="">Condition</label>
            <input type="text" class="form-control" name="" id="" value="<?php echo $condition; ?>" readonly>
        </div>
        <div class="col">
            <label for="">Style</label>
            <input type="text" class="form-control" name="" id="" value="<?php echo $style; ?>" readonly>
        </div>
        <div class="col">
            <label for="">Color</label>
            <input type="text" class="form-control" name="" id="" value="<?php echo $color; ?>" readonly>
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