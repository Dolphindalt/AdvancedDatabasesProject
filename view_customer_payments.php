<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
        if (!key_exists('taxid', $_GET)) {
            http_response_code(400);
            exit;
        }
        
        $tax_id = $_GET['taxid'];

        $statement = $db->prepare("SELECT * FROM Customer WHERE tax_id = ?;");
        $statement->bindParam(1, $tax_id, PDO::PARAM_INT);
        $statement->execute();
        $customer = $statement->fetchAll(PDO::FETCH_ASSOC)[0];

        $statement = $db->prepare("SELECT COUNT(p.payment_id) AS total_late_payments FROM Customer_Payments AS cp LEFT JOIN Payment AS p ON cp.payment_id = p.payment_id WHERE cp.tax_id = ? AND p.due_date < p.paid_date;");
        $statement->bindParam(1, $tax_id, PDO::PARAM_INT);
        $statement->execute();
        $aggregate_payment_stats = $statement->fetchAll(PDO::FETCH_ASSOC)[0];

        $statement = $db->prepare("SELECT * FROM Customer_Payments AS cp LEFT JOIN Payment AS p ON cp.payment_id = p.payment_id WHERE cp.tax_id = ?;");
        $statement->bindParam(1, $tax_id, PDO::PARAM_INT);
        $statement->execute();
        $payments = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
    <h2>Payments for <?php echo $customer['first_name'] . " " . $customer['last_name']; ?></h2>
<?php
    if (count($payments) != 0)
    {
        echo "<p>Total late payments: " . $aggregate_payment_stats['total_late_payments'] . "</p>";
        $payment_count = 0;
?>
    <table class="table table-striped">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Due Date</th>
            <th scope="col">Paid Date</th>
            <th scope="col">Amount</th>
            <th scope="col">Bank Account</th>
            <th scope="col">Make Payment</th>
            </tr>
        </thead>
        <tbody>
<?php
        foreach ($payments as $payment) {
                $payment_count++;
                $due_date = date('Y-m-d', strtotime($payment['due_date']));
                $paid_date = (is_null($payment['paid_date'])) ? "Need Payment" : date('Y-m-d', strtotime($payment['paid_date']));;
                $bank_account = (is_null($payment['bank_account'])) ? "Need Payment" : $payment['bank_account'];
            ?>
            <tr>
                <th scope="row"><?php echo $payment_count; ?></th>
                <td><?php echo $due_date; ?></td>
                <td><?php echo $paid_date; ?></td>
                <td><?php echo $payment['amount']; ?></td>
                <td><?php echo $bank_account; ?></td>
                <td><?php if ($paid_date == "Need Payment" ) { ?><a href="make_payment.php?pid=<?php echo $payment['payment_id']; ?>" class="btn btn-primary" role="button">Pay</a><?php } ?></td>
            </tr>
            <?php
        }
?>
        </tbody>
    </table>
<?php
        } else {
            echo "<h3>This customer has no payments!</h3>";
        }
    }
    require_once 'footer.php';
?>