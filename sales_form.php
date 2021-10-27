<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
?>

<form>
    <h1>Sale Form</h1>
    <div class="form-row">
        <div class="col">
            <label for="date">Date</label>
            <input type="date" class="form-control" name="date" id="date" placeholder="">
        </div>
        <div class="col">
            <label for="totalDue">Total Due</label>
            <input type="text" class="form-control" name="totalDue" id="totalDue" placeholder="">
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
        <h2>Salesperson Information</h2>
    </div>
    <div class="form-row">
        <div class="col">
            <label for="salespersonFirstName">First Name</label>
            <input type="text" class="form-control" name="salespersonFirstName" id="salespersonFirstName" placeholder="" readonly>
        </div>
        <div class="col">
            <label for="salespersonLastName">Last Name</label>
            <input type="text" class="form-control" name="salespersonLastName" id="salespersonLastName" placeholder="" readonly>
        </div>
        <div class="col">
            <label for="salespersonCommission">Last Name</label>
            <input type="number" class="form-control" name="salespersonCommission" id="salespersonCommission" placeholder="25">
        </div>
    </div>
    <div class="form-row">
        <div class="col">
            <button class="btn btn-primary">Select Salesperson</button>
        </div>
    </div>
    <div class="form-row">
        <h2>Customer Information</h2>
    </div>
    <div class="form-row">
        <div class="col">
            <label for="customerFirstName">First Name</label>
            <input type="text" class="form-control" name="customerFirstName" id="customerFirstName" placeholder="" readonly>
        </div>
        <div class="col">
            <label for="customerLastName">Last Name</label>
            <input type="text" class="form-control" name="customerLastName" id="customerLastName" placeholder="" readonly>
        </div>
        <div class="col">
            <label for="customerPhoneNumber">Phone Number</label>
            <input type="number" class="form-control" name="customerPhoneNumber" id="customerPhoneNumber" placeholder="" readonly>
        </div>
    </div>
    <div class="form-row">
        <div class="col">
            <button class="btn btn-primary">Select Customer</button>
        </div>
    </div>
    <div class="form-row">
        <h2>Vehicle Information</h2>
    </div>
    <div class="form-row">
        <div class="col">
            <label for="VIN">VIN</label>
            <input type="text" class="form-control" name="VIN" id="VIN" placeholder="" readonly>
        </div>
        <div class="col">
            <label for="miles">Miles</label>
            <input type="text" class="form-control" name="miles" id="miles" placeholder="" readonly>
        </div>
        <div class="col">
            <label for="condition">Condition</label>
            <input type="number" class="form-control" name="condition" id="condition" placeholder="" readonly>
        </div>
        <div class="col">
            <label for="style">Style</label>
            <input type="number" class="form-control" name="style" id="style" placeholder="" readonly>
        </div>
        <div class="col">
            <label for="interiorColor">Interior Color</label>
            <input type="number" class="form-control" name="interiorColor" id="interiorColor" placeholder="" readonly>
        </div>
    </div>
    <div class="form-row">
        <div class="col">
            <button class="btn btn-primary">Select Vehicle</button>
        </div>
    </div>
</form>

<?php
    } 
    else if ($method == "POST") 
    {

    }
    require_once 'footer.php';
?>