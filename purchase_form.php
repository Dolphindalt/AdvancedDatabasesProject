<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
?>

<form>
    <div class="form-row">
        <div class="col">
            <h1>Purchase Form</h1>
        </div>
    </div>
    <div class="form-row">
        <div class="col">
            <label for="date">Date</label>
            <input type="date" class="form-control" name="date" id="date">
            <script>
                document.getElementById('date').value = new Date().toDateInputValue();
            </script>
        </div>
        <div class="col">
            <label for="sellerTaxID">Select Seller</label>
            <select class="form-control" id="sellerTaxID">
            <option selected>Choose</option>
            <?php
                foreach ($db->query("SELECT seller_tax_id, name FROM Seller") as $row) {
                    $name_string = $row['name'];
                    echo '<option value=' . $row['seller_tax_id'] . '>' . $name_string . '</option>';
                }
            ?>
            </select>
        </div>
        <div class="col">
            <button type="button" class="btn btn-primary" onclick="toggleShowEnterSellerSection()">Enter new seller</button>
        </div>
    </div>
    <div id='new-seller-sink' style="display: none;">
        <div class="form-row">
            <div class="col">
                <h2>Add New Seller</h2>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <label for="newSellerTaxID">Seller Tax ID</label>
                <input type="text" class="form-control" name="newSellerTaxID" id="newSellerTaxID">
            </div>
            <div class="col">
                <label for="newSellerName">Seller Name</label>
                <input type="text" class="form-control" name="newSellerName" id="newSellerName">
            </div>
            <div class="col">
                <button type="button" class="btn btn-primary" onclick="sendAddSeller()">Add Seller</button>
            </div>
        </div>
    </div>
    <div class="form-row">
        
    </div>
</form>

<?php
    }
    require_once 'footer.php';
?>