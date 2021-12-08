<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
?>
<h1 style="text-align: center;">Customer Information Form</h1>
<form action='add_customer.php' method='post'>
    <div  style="display: flex; padding-left: 3em; justify-content: space-evenly;">
        <div style="display: inline-block;">
            <h2> Add Customer</h2>
            <div class='form-row'>
                <div class='col'>
                    <label for="firstName">First Name</label>
                    <input type="text" class="form-control" name="firstName" id="firstName" placeholder="Enter first name" required>
                </div>
                <div class='col'>
                    <label for="lastName">Last Name</label>
                    <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Enter last name" required>
                </div>
            </div>
            <div class='form-row'>
                <div class='col'>
                    <label for="phoneNumber">Phone Number</label>
                    <input type="tel" class="form-control" name="phoneNumber" id="phoneNumber" placeholder="Enter phone number" required>
                </div>
                <div class='col'>
                    <label for="gender">Gender</label>
                    <input type="tel" class="form-control" name="gender" id="gender" placeholder="Enter your gender"required>
                </div>
            </div>
            <div class='form-row'>
                <div class='col'>
                    <label for="dateOfBirth">Date of Birth</label>
                    <input type="date" class="form-control" name="dateOfBirth" id="dateOfBirth" placeholder="Enter your date of birth"required>
                </div>
                <div class='col'>
                    <label for="taxPayerID">Tax Payer ID</label>
                    <input type="text" class="form-control" name="taxPayerID" id="taxPayerID" placeholder="Enter your tax payer ID"required>
                </div>
            </div>
        </div>
        <div style="display: inline-block;">
            <div class="form-row">
                <h2>Billing Address</h2>
            </div>
            <div class="form-row">
                <div class="col">
                    <label for="street">Street</label>
                    <input type="text" class="form-control" name="street" id="street" placeholder="Enter your street" required>
                </div>
                <div class="col">
                    <label for="city">City</label>
                    <input type="text" class="form-control" name="city" id="city" placeholder="Enter your city" required>
                </div>
                <div class="col">
                    <label for="state">State</label>
                    <input type="text" class="form-control" name="state" id="state" placeholder="Enter your state" required>
                </div>
                <div class="col">
                    <label for="zip">Postal/ZIP Code</label>
                    <input type="text" class="form-control" name="zip" id="zip" placeholder="Enter your postal/ZIP code" required>
                </div>
            </div>
            <div class="form-row" >
                <div class="col">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php
    } else if ($method == "POST") {
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
        $statement = $db->prepare("CALL addCustomerPageSubmit(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $statement->bindParam(1, $tax_id, PDO::PARAM_INT);
        $statement->bindParam(2, $first_name, PDO::PARAM_STR);
        $statement->bindParam(3, $last_name, PDO::PARAM_STR);
        $statement->bindParam(4, $phone_number, PDO::PARAM_STR);
        $statement->bindParam(5, $gender, PDO::PARAM_STR);
        $statement->bindParam(6, $date_of_birth, PDO::PARAM_STR);
        $statement->bindParam(7, $street, PDO::PARAM_STR);
        $statement->bindParam(8, $city, PDO::PARAM_STR);
        $statement->bindParam(9, $state, PDO::PARAM_STR);
        $statement->bindParam(10, $zip, PDO::PARAM_STR);
        $statement->execute();
        
        ?>
            <div class="alert alert-primary" role="alert">
                Customer record created. Click <a href="view_customer.php?taxid=<?php echo $tax_id; ?>">here</a> to view it.
            </div>
        <?php
    }
    require_once 'footer.php';
?>