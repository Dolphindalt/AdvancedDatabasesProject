<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
?>
<h1>Add Customer Form</h1>
<form action='add_customer.php' method='post'>
    <div class='form-group'>
        <label for="firstName">First Name</label>
        <input type="text" class="form-control" name="firstName" id="firstName" placeholder="Enter first name">
    </div>
    <div class='form-group'>
        <label for="lastName">Last Name</label>
        <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Enter last name">
    </div>
    <div class='form-group'>
        <label for="phoneNumber">Phone Number</label>
        <input type="tel" class="form-control" name="phoneNumber" id="phoneNumber" placeholder="Enter phone number">
    </div>
    <div class='form-group'>
        <label for="gender">Gender</label>
        <input type="tel" class="form-control" name="gender" id="gender" placeholder="Enter your gender">
    </div>
    <div class='form-group'>
        <label for="dateOfBirth">Date of Birth</label>
        <input type="date" class="form-control" name="dateOfBirth" id="dateOfBirth" placeholder="Enter your date of birth">
    </div>
    <div class='form-group'>
        <label for="taxPayerID">Tax Payer ID</label>
        <input type="text" class="form-control" name="taxPayerID" id="taxPayerID" placeholder="Enter your tax payer ID">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<?php
    } else if ($method == "POST") {
        $tax_id = $_POST['taxPayerID'];
        $first_name = $_POST['firstName'];
        $last_name = $_POST['lastName'];
        $phone_number = $_POST['phoneNumber'];
        $gender = $_POST['gender'];
        $date_of_birth = $_POST['dateOfBirth'];
        $statement = $db->prepare("CALL insertCustomer(?, ?, ?, ?, ?, ?)");
        $statement->bindParam(1, $tax_id, PDO::PARAM_INT);
        $statement->bindParam(2, $first_name, PDO::PARAM_STR);
        $statement->bindParam(3, $last_name, PDO::PARAM_STR);
        $statement->bindParam(4, $phone_number, PDO::PARAM_STR);
        $statement->bindParam(5, $gender, PDO::PARAM_STR);
        $statement->bindParam(6, $date_of_birth, PDO::PARAM_STR);
        $statement->execute();
        ?>
        <div class="alert alert-primary" role="alert">
            The customer information has been recorded. 
        </div>
        <?php
    }
    require_once 'footer.php';
?>