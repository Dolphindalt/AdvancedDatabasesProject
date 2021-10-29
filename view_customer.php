<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
        if (!isset($_GET) && !key_exists('taxid', $GET)) {
            http_response_code(404);
            exit;
        }
        $tax_id = $_GET['taxid'];
        $statement = $db->prepare("SELECT * FROM Customer WHERE Customer.tax_id = ?");
        $statement->bindParam(1, $tax_id, PDO::PARAM_INT);
        $result_set = $statement->execute();
        $customer = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (empty($customer)) {
            http_response_code(404);
            exit;
        }
        $customer = $customer[0];
?>
<form action='view_customer.php' method='post'>
    <div class="form-row">
        <div class="col">
            <h1>Customer Information</h1>
        </div>
    </div>
    <div class='form-row'>
        <div class='col'>
            <label for="firstName">First Name</label>
            <input type="text" class="form-control" name="firstName" id="firstName" placeholder="<?php echo $customer['first_name']; ?>">
        </div>
        <div class='col'>
            <label for="lastName">Last Name</label>
            <input type="text" class="form-control" name="lastName" id="lastName" placeholder="<?php echo $customer['last_name']; ?>">
        </div>
    </div>
    <div class='form-row'>
        <div class='col'>
            <label for="phoneNumber">Phone Number</label>
            <input type="tel" class="form-control" name="phoneNumber" id="phoneNumber" placeholder="<?php echo $customer['phone_number']; ?>">
        </div>
        <div class='col'>
            <label for="gender">Gender</label>
            <input type="tel" class="form-control" name="gender" id="gender" placeholder="<?php echo $customer['gender']; ?>">
        </div>
    </div>
    <div class='form-row'>
        <div class='col'>
            <label for="dateOfBirth">Date of Birth</label>
            <input type="date" class="form-control" name="dateOfBirth" id="dateOfBirth" placeholder="<?php echo $customer['dob']; ?>">
        </div>
        <div class='col'>
            <label for="taxPayerID">Tax Payer ID</label>
            <input type="text" class="form-control" name="taxPayerID" id="taxPayerID" placeholder="<?php echo $customer['tax_id']; ?>" readonly>
        </div>
    </div>
    <div class="form-row">
        <h2>Billing Address</h2>
    </div>
    <?php 
        $statement = $db->prepare("CALL getCustomerAddress(?);");
        $statement->bindParam(1, $tax_id, PDO::PARAM_INT);
        $result_set = $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $location = $results[0];
    ?>
    <div class="form-row">
        <div class="col">
            <label for="street">Street</label>
            <input type="text" class="form-control" name="street" id="street" placeholder="<?php echo $location['address'] ?>">
        </div>
        <div class="col">
            <label for="city">City</label>
            <input type="text" class="form-control" name="city" id="city" placeholder="<?php echo $location['city'] ?>">
        </div>
        <div class="col">
            <label for="state">State</label>
            <input type="text" class="form-control" name="state" id="state" placeholder="<?php echo $location['state'] ?>">
        </div>
        <div class="col">
            <label for="zip">Postal/ZIP Code</label>
            <input type="text" class="form-control" name="zip" id="zip" placeholder="<?php echo $location['zip'] ?>">
        </div>
    </div>
    <div class="form-row">
        <div class="col">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>
<?php
    }
    require_once 'footer.php';
?>