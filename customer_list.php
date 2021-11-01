<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
        if (!key_exists('page', $_GET)) {
            http_response_code(400);
            exit;
        }
        
        $items_per_page = 10;
        $page_number = $_GET['page']; // pages indexed by 0 for lols
        $start = $page_number * $items_per_page; // 10 items per page
        $end = $start + $items_per_page;

        $statement = $db->prepare("SELECT * FROM Customer LIMIT ?,?;");
        $statement->bindParam(1, $start, PDO::PARAM_INT);
        $statement->bindParam(2, $end, PDO::PARAM_INT);
        $statement->execute();
        $customers = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
    <table class="table">
        <thead>
            <tr>
            <th scope="col">Tax ID</th>
            <th scope="col">Name</th>
            <th scope="col">Payments</th>
            <th scope="col">Information</th>
            <th scope="col">Employment History</th>
            </tr>
        </thead>
        <tbody>
<?php
        foreach ($customers as $customer) {
            ?>
            <tr>
                <th scope="row"><?php echo $customer['tax_id']; ?></th>
                <td><?php echo $customer['first_name'] . " " . $customer['last_name']; ?></td>
                <td><a href="view_customer_payments.php?taxid=<?php echo $customer['tax_id']; ?>">View Payments</a></td>
                <td><a href="view_customer.php?taxid=<?php echo $customer['tax_id']; ?>">View Information</a></td>
                <td><a href="view_employment_history.php?taxid=<?php echo $customer['tax_id']; ?>">View Employment History</a></td>
            </tr> 
            <?php
        }
?>
        </tbody>
    </table>
<?php
    }
    require_once 'footer.php';
?>