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

        $statement = $db->prepare("SELECT COUNT(*) AS count FROM Purchase AS p;");
        $statement->execute();
        $total_purchases = $statement->fetchAll(PDO::FETCH_ASSOC)[0]['count'];
        $total_pages = $total_purchases / $items_per_page;

        $statement = $db->prepare("SELECT * 
            FROM Vehicle_Purchase AS vp 
            LEFT JOIN Purchase AS p ON vp.purchase_id = p.purchase_id 
            LEFT JOIN Employee AS e ON e.employee_id = p.tax_id 
            LEFT JOIN Seller AS s ON s.seller_tax_id = p.seller_id 
            ORDER BY p.date DESC LIMIT ?,?;");
        $statement->bindParam(1, $start, PDO::PARAM_INT);
        $statement->bindParam(2, $end, PDO::PARAM_INT);
        $statement->execute();
        $info = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
    <h1>Purchase Records</h1>
    <table class="table table-striped">
        <thead>
            <tr>
            <th scope="col">Date</th>
            <th scope="col">Paid Price</th>
            <th scope="col">Book Price</th>
            <th scope="col">Buyer</th>
            <th scope="col">Seller</th>
            <th scope="col">Vehicle</th>
            <th scope="col">Information</th>
            </tr>
        </thead>
        <tbody>
<?php
        foreach ($info as $i) {
            ?>
            <tr>
                <th scope="row"><?php echo date('Y-m-d', strtotime($i['date'])); ?></th>
                <td><?php echo "$" . $i['paid_price']; ?></td>
                <td><?php echo "$" . $i['book_price']; ?></td>
                <td><?php echo $i['first_name'] . " " . $i['last_name']; ?></td>
                <td><?php echo $i['name']; ?></td>
                <td><?php echo $i['vin']; ?></td>
                <td><a href="view_purchase.php?purchaseid=<?php echo $i['purchase_id']; ?>&vin=<?php echo $i['vin']; ?>">View Form</a></td>
            </tr> 
            <?php
        }
?>
        </tbody>
    </table>
    <nav aria-label="Pagination">
        <ul class="pagination">
            <?php
            for ($i = 0; $i < $total_pages; $i++) {
                echo "<li class='page-item'><a class='page-link' href='sale_list.php?page=" . $i . "'>" . ($i + 1)  ."</a></li>";
            }
            ?>
        </ul>
    </nav>
<?php
    }
    require_once 'footer.php';
?>