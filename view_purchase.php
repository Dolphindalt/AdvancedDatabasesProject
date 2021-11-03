<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
        if (!key_exists('purchaseid', $_GET) || !key_exists('vin', $_GET)) {
            http_response_code(400);
            exit;
        }

        $purchase_id = $_GET['purchaseid'];
        $vin = $_GET['vin'];

        $statement = $db->prepare("SELECT *, vp.color, vp.miles, vp.condition 
            FROM Vehicle_Purchase AS vp 
            LEFT JOIN Purchase AS p ON p.purchase_id = vp.purchase_id 
            LEFT JOIN Vehicle AS v ON v.vin = vp.vin 
            LEFT JOIN Employee AS e ON e.employee_id = p.tax_id 
            LEFT JOIN Seller AS s ON s.seller_tax_id = p.seller_id 
            LEFT JOIN Location AS l ON l.location_id = p.location_id 
            WHERE vp.vin = ? AND vp.purchase_id = ?;");
        $statement->bindParam(1, $vin, PDO::PARAM_STR);
        $statement->bindParam(2, $purchase_id, PDO::PARAM_INT);
        $statement->execute();
        $info = $statement->fetchAll(PDO::FETCH_ASSOC)[0];
?>
<h1>Purchase Record</h1>
<form method='post' style="display: flex-grow; justify-content: space-evenly; padding-left: 3em;">

<div class="form-row">
    <div class="col">
        <label>Date</label>
        <input type="date" class="form-control" value="<?php echo date('Y-m-d', strtotime($info['date'])); ?>" readonly>
    </div>
    <div class="col">
        <?php
            if ($info['is_auction']) {
                echo "<label style='display: inline-block;'>Was an auction</label>";
            } else {
                echo "<label style='display: inline-block;'>Was not an auction</label>";
            }
        ?>
    </div>
    <div class="col">
        <label>Book Price</label>
        <input type="text" class="form-control" value="<?php echo $info['book_price']; ?>" readonly>
    </div>
    <div class="col">
        <label>Paid Price</label>
        <input type="text" class="form-control" value="<?php echo $info['paid_price']; ?>" readonly>
    </div>
</div>
<div class="form-row">
    <div class="col">
        <label>Buyer</label>
        <input type="text" class="form-control" value="<?php echo $info['first_name'] . " " . $info['last_name']; ?>" readonly>
    </div>
    <div class="col">
        <label>Seller</label>
        <input type="text" class="form-control" value="<?php echo $info['name']; ?>" readonly>
    </div>
    <div class="col">
        <label>Street</label>
        <input type="text" class="form-control" value="<?php echo $info['address']; ?>" readonly>
    </div>
    <div class="col">
        <label>City</label>
        <input type="text" class="form-control" value="<?php echo $info['city']; ?>" readonly>
    </div>
    <div class="col">
        <label>State</label>
        <input type="text" class="form-control" value="<?php echo $info['state']; ?>" readonly>
    </div>
    <div class="col">
        <label>Postal/ZIP Code</label>
        <input type="text" class="form-control" value="<?php echo $info['ZIP']; ?>" readonly>
    </div>
</div>
<div class="form-row">
    <div class="col">
        <label>VIN</label>
        <input type="text" class="form-control" value="<?php echo $info['vin']; ?>" readonly>
    </div>
    <div class="col">
        <label>Make</label>
        <input type="text" class="form-control" value="<?php echo $info['make']; ?>" readonly>
    </div>
    <div class="col">
        <label>Model</label>
        <input type="text" class="form-control" value="<?php echo $info['model']; ?>" readonly>
    </div>
    <div class="col">
        <label>Year</label>
        <input type="text" class="form-control" value="<?php echo $info['year']; ?>" readonly>
    </div>
    <div class="col">
        <label>Color</label>
        <input type="text" class="form-control" value="<?php echo $info['color']; ?>" readonly>
    </div>
    <div class="col">
        <label>Miles</label>
        <input type="text" class="form-control" value="<?php echo $info['miles']; ?>" readonly>
    </div>
    <div class="col">
        <label>Style</label>
        <input type="text" class="form-control" value="<?php echo $info['style']; ?>" readonly>
    </div>
    <div class="col">
        <label>Condition</label>
        <input type="text" class="form-control" value="<?php echo $info['condition']; ?>" readonly>
    </div>
</div>
<?php 
    }
    require_once 'footer.php';
?>