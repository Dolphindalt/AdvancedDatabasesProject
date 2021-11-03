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
        $customer['dob'] = date('Y-m-d', strtotime($customer['dob']));
?>

<h1>Customer Information</h1>
<form action='view_customer.php' method='post' style="padding-left: 3em;">
    <div class="form-row">
        <div class="col">
            <h2>Customer</h2>
        </div>
    </div>
    <div class='form-row'>
        <div class='col'>
            <label for="firstName">First Name</label>
            <input type="text" class="form-control" name="firstName" id="firstName" value="<?php echo $customer['first_name']; ?>" required>
        </div>
        <div class='col'>
            <label for="lastName">Last Name</label>
            <input type="text" class="form-control" name="lastName" id="lastName" value="<?php echo $customer['last_name']; ?>" required>
        </div>
    </div>
    <div class='form-row'>
        <div class='col'>
            <label for="phoneNumber">Phone Number</label>
            <input type="tel" class="form-control" name="phoneNumber" id="phoneNumber" value="<?php echo $customer['phone_number']; ?>" required>
        </div>
        <div class='col'>
            <label for="gender">Gender</label>
            <input type="tel" class="form-control" name="gender" id="gender" value="<?php echo $customer['gender']; ?>" required>
        </div>
    </div>
    <div class='form-row'>
        <div class='col'>
            <label for="dateOfBirth">Date of Birth</label>
            <input type="date" class="form-control" name="dateOfBirth" id="dateOfBirth" value="<?php echo $customer['dob']; ?>" required>
        </div>
        <div class='col'>
            <label for="taxPayerID">Tax Payer ID</label>
            <input type="text" class="form-control" name="taxPayerID" id="taxPayerID" value="<?php echo $customer['tax_id']; ?>" readonly>
        </div>
    </div>
    <div class="form-row">
        <h2>Billing Address</h2>
    </div>
    <?php 
        $statement = $db->prepare("SELECT * FROM Customer_Location AS cl LEFT JOIN Location AS l ON cl.location_id = l.location_id WHERE cl.tax_id = ?;");
        $statement->bindParam(1, $tax_id, PDO::PARAM_INT);
        $result_set = $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $location = $results[0];
    ?>
    <div class="form-row">
        <div class="col">
            <label for="street">Street</label>
            <input type="text" class="form-control" name="street" id="street" value="<?php echo $location['address'] ?>" required>
        </div>
        <div class="col">
            <label for="city">City</label>
            <input type="text" class="form-control" name="city" id="city" value="<?php echo $location['city'] ?>" required>
        </div>
        <div class="col">
            <label for="state">State</label>
            <input type="text" class="form-control" name="state" id="state" value="<?php echo $location['state'] ?>" required>
        </div>
        <div class="col">
            <label for="zip">Postal/ZIP Code</label>
            <input type="text" class="form-control" name="zip" id="zip" value="<?php echo $location['ZIP'] ?>" required>
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
    else if ($method == "POST")
    {
        $db->query("START TRANSACTION;")->execute();

        $tax_id = $_POST['taxPayerID'];
        $first_name = $_POST['firstName'];
        $last_name = $_POST['lastName'];
        $phone_number = $_POST['phoneNumber'];
        $gender = $_POST['gender'];
        $date_of_birth = $_POST['dateOfBirth'];
        $street = $_POST['street'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];

        $statement = $db->prepare("UPDATE Customer SET first_name = ?, last_name = ?, 
            phone_number = ?, gender = ?, dob = ? WHERE tax_id = ?");
        $statement->bindParam(1, $tax_id, PDO::PARAM_INT);
        $statement->bindParam(2, $first_name, PDO::PARAM_STR);
        $statement->bindParam(3, $last_name, PDO::PARAM_STR);
        $statement->bindParam(4, $phone_number, PDO::PARAM_STR);
        $statement->bindParam(5, $gender, PDO::PARAM_STR);
        $statement->bindParam(6, $date_of_birth, PDO::PARAM_STR);
        $statement->execute();

        $statement = $db->prepare("SELECT l.location_id AS location_id FROM Customer_Location AS cl LEFT JOIN Location AS l ON cl.location_id = l.location_id WHERE cl.tax_id = ?;");
        $statement->bindParam(1, $tax_id, PDO::PARAM_INT);
        $statement->execute();
        $location_id = $statement->fetchAll(PDO::FETCH_ASSOC)[0]['location_id'];

        $statement = $db->prepare("UPDATE Location SET address = ?, city = ?, 
            state = ?, zip = ? WHERE location_id = ?");
        $statement->bindParam(1, $street, PDO::PARAM_STR);
        $statement->bindParam(2, $city, PDO::PARAM_STR);
        $statement->bindParam(3, $state, PDO::PARAM_STR);
        $statement->bindParam(4, $zip, PDO::PARAM_STR);
        $statement->bindParam(5, $location_id, PDO::PARAM_INT);
        $statement->execute();

        $db->query("COMMIT;")->execute();

        ?>
        <div class="alert alert-primary" role="alert">
            Customer record updated. Click <a href="view_customer.php?taxid=<?php echo $tax_id; ?>">here</a> to return to the record.
        </div>
        <?php
    }
    require_once 'footer.php';
?>