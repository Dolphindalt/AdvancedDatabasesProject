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

        $statement = $db->prepare("SELECT COUNT(*) AS count FROM Sale AS s;");
        $statement->execute();
        $total_sales = $statement->fetchAll(PDO::FETCH_ASSOC)[0]['count'];
        $total_pages = $total_sales / $items_per_page;

        $statement = $db->prepare("SELECT c.first_name AS cfirst, c.last_name AS clast,
            e.first_name AS efirst, e.last_name AS elast, s.sale_id, s.total_due, 
            s.date, v.vin FROM Sale AS s 
            LEFT JOIN Vehicle_Sale AS vs ON vs.sale_id = s.sale_id 
            LEFT JOIN Sale_Employee AS se ON se.sale_id = s.sale_id
            LEFT JOIN Sale_Customer AS sc ON sc.sale_id = s.sale_id
            LEFT JOIN Employee AS e ON e.employee_id = se.employee_id
            LEFT JOIN Customer AS c ON c.tax_id = sc.tax_id
            LEFT JOIN Vehicle AS v ON v.vin = vs.vin ORDER BY s.date DESC LIMIT ?,?;");
        $statement->bindParam(1, $start, PDO::PARAM_INT);
        $statement->bindParam(2, $end, PDO::PARAM_INT);
        $statement->execute();
        $info = $statement->fetchAll(PDO::FETCH_ASSOC);

        $statement = $db->prepare("SELECT SUM(total_due) AS due FROM Sale AS s;");
        $statement->execute();
        $total_sales = $statement->fetchAll(PDO::FETCH_ASSOC)[0]['due'];
?>
    <h1>Sales Records</h1>
    <p>Total Expected Profits: <?php echo "$" . $total_sales; ?></p>
    <table class="table table-striped">
        <thead>
            <tr>
            <th scope="col">Date</th>
            <th scope="col">Sale Total</th>
            <th scope="col">Salesperson</th>
            <th scope="col">Customer</th>
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
                <td><?php echo "$" . $i['total_due']; ?></td>
                <td><?php echo $i['efirst'] . " " . $i['elast']; ?></td>
                <td><?php echo $i['cfirst'] . " " . $i['clast']; ?></td>
                <td><?php echo $i['vin']; ?></td>
                <td><a href="view_sale.php?saleid=<?php echo $i['sale_id']; ?>">View Form</a></td>
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