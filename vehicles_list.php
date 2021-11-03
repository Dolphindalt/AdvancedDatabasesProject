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

        $statement = $db->prepare("SELECT COUNT(*) AS count FROM Vehicle AS v;");
        $statement->execute();
        $total_vehicles = $statement->fetchAll(PDO::FETCH_ASSOC)[0]['count'];
        $total_pages = $total_vehicles / $items_per_page;

        $statement = $db->prepare("SELECT * 
        FROM Vehicle AS v 
        ORDER BY v.year DESC LIMIT ?,?;");
        $statement->bindParam(1, $start, PDO::PARAM_INT);
        $statement->bindParam(2, $end, PDO::PARAM_INT);
        $statement->execute();
        $info = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
    <h1>Vehicle Records</h1>
    <table class="table table-striped">
        <thead>
            <tr>
            <th scope="col">VIN</th>
            <th scope="col">Make</th>
            <th scope="col">Model</th>
            <th scope="col">Year</th>
            <th scope="col">Sold</th>
            <th scope="col">Information</th>
            </tr>
        </thead>
        <tbody>
<?php
        foreach ($info as $i) {
            ?>
            <tr>
                <th scope="row"><?php echo $i['vin']; ?></th>
                <td><?php echo $i['make']; ?></td>
                <td><?php echo $i['model']; ?></td>
                <td><?php echo $i['year']; ?></td>
                <td><?php echo ($i['sold'] == 1) ? "Yes" : "No"; ?></td>
                <td><a href="view_vehicle.php?vin=<?php echo $i['vin']; ?>">View Record</a></td>
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
                echo "<li class='page-item'><a class='page-link' href='vehicles_list.php?page=" . $i . "'>" . ($i + 1)  ."</a></li>";
            }
            ?>
        </ul>
    </nav>
<?php
    }
    require_once 'footer.php';
?>