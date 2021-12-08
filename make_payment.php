<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
        if (!key_exists('pid', $_GET)) {
            http_response_code(400);
            exit;
        }
        
        $payment_id = $_GET['pid'];

        $statement = $db->prepare("SELECT * FROM Payment WHERE payment_id = ?;");
        $statement->bindParam(1, $payment_id, PDO::PARAM_INT);
        $statement->execute();
        $payment = $statement->fetchAll(PDO::FETCH_ASSOC)[0];
?>
<form action='make_payment.php' method='post'>
    <div class="form-row">
        <div class="col">
            <h2>Make Payment Record</h2>
        </div>
    </div>
    <div class="form-row">
        <input type="text" style="display: none;" value="<?php echo $payment_id; ?>" id="payment_id" name="payment_id" readonly>
        <div class="col">
            <label for="amount_due">Amount Due</label>
            <input type="text" class="form-control" name="amount_due" id="amount_due" value="<?php echo $payment['amount']; ?>" readonly>
        </div>
        <div class="col">
            <label for="bid">Bank Account ID</label>
            <input type="text" class="form-control" name="bid" id="bid" required>
        </div>
    </div>
    <div class="form-row">
        <div class="col">
            <button type="submit" class="btn btn-primary" >Pay</button>
        </div>
    </div>
</form>
<?php
    } else if ($method == "POST") {
        $bid = $_POST['bid'];
        $payment_id = $_POST['payment_id'];

        $statement = $db->prepare("UPDATE Payment SET paid_date = NOW(), bank_account = ? WHERE payment_id = ?;");
        $statement->bindParam(1, $bid, PDO::PARAM_INT);
        $statement->bindParam(2, $payment_id, PDO::PARAM_INT);
        $statement->execute();

        ?>
            <div class="alert alert-primary" role="alert">
                Payment record updated.
            </div>
        <?php
    }
    require_once 'footer.php';
?>