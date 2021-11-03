<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
        if (!key_exists('vin', $_GET)) {
            http_response_code(400);
            exit;
        }

        $vin = $_GET['vin'];

        $statement = $db->prepare("SELECT * FROM Vehicle WHERE vin = ?;");
        $statement->bindParam(1, $vin, PDO::PARAM_STR);
        $statement->execute();
        $vehicle = $statement->fetchAll(PDO::FETCH_ASSOC)[0];
?>
<script>
    function updateProblem(pid) {
        if (pid) {
            $.ajax({
                url: "problem.php",
                type: "PATCH",
                data: { 
                    problem_id: pid,
                    description: document.getElementById("description" + pid).value,
                    actual_repair_cost: document.getElementById("actual_repair_cost" + pid).value
                },
                success: function(data, status, xhr) {
                    showSnackbar("Updated vehicle problem successfully.");
                },
                error: function(jqXhr, textStatus, errorMessage) {
                    showSnackbar(vagueError);
                }
            });
        }
    }

    function deleteProblem(pid) {
        if (pid) {
            $.ajax({
                url: "problem.php",
                type: "DELETE",
                data: { 
                    problem_id: pid
                },
                success: function(data, status, xhr) {
                    document.getElementById("problem-" + pid).remove();
                    showSnackbar("Deleted vehicle problem successfully.");
                },
                error: function(jqXhr, textStatus, errorMessage) {
                    showSnackbar(vagueError);
                }
            });
        }
    }
</script>
<div class='form-row'>
    <div class='col'>
        <h2>Vehicle Information</h2>
    </div>
</div>
<div class='form-row'>
    <div class='col'>
        <label>Make</label>
        <input type="text" class="form-control" value="<?php echo $vehicle['make']; ?>" readonly>
    </div>
    <div class='col'>
        <label>Model</label>
        <input type="text" class="form-control" value="<?php echo $vehicle['model']; ?>" readonly>
    </div>
    <div class='col'>
        <label>Year</label>
        <input type="text" class="form-control" value="<?php echo $vehicle['year']; ?>" readonly>
    </div>
    <div class='col'>
        <label>Color</label>
        <input type="text" class="form-control" value="<?php echo $vehicle['color']; ?>" readonly>
    </div>
    <div class='col'>
        <label>Miles</label>
        <input type="text" class="form-control" value="<?php echo $vehicle['miles']; ?>" readonly>
    </div>
    <div class='col'>
        <label>Style</label>
        <input type="text" class="form-control" value="<?php echo $vehicle['style']; ?>" readonly>
    </div>
    <div class='col'>
        <label>Condition</label>
        <input type="text" class="form-control" value="<?php echo $vehicle['condition']; ?>" readonly>
    </div>
</div>
<div class='form-row'>
    <div class='col'>
        <h3>Vehicle Problems</h3>
    </div>
</div>
<?php
    $statement = $db->prepare("SELECT * FROM Vehicle_Problem AS cp LEFT JOIN Problem AS p ON cp.problem_id = p.problem_id WHERE cp.vin = ?;");
    $statement->bindParam(1, $vin, PDO::PARAM_STR);
    $statement->execute();
    $problems = $statement->fetchAll(PDO::FETCH_ASSOC);
    if (count($problems) == 0) {
        echo "<p>No problems.</p>";
    } else {
        foreach ($problems as $problem) {
            ?>
            <div id='problem-<?php echo $problem['problem_id']; ?>'class='form-row'>
                <div class='col'>
                    <label>Description</label>
                    <input type="text" class="form-control" id="description<?php echo $problem['problem_id']; ?>" value="<?php echo $problem['description']; ?>" required>
                </div>
                <div class='col'>
                    <label>Estimated Repair Cost</label>
                    <input type="number" class="form-control" value="<?php echo $problem['estimated_repair_cost']; ?>" readonly>
                </div>
                <div class='col'>
                    <label>Actual Repair Cost</label>
                    <input type="number" class="form-control" id="actual_repair_cost<?php echo $problem['problem_id']; ?>" value="<?php echo $problem['actual_repair_cost']; ?>" required>
                </div>
                <div class='col'>
                    <button type="button" class="btn btn-primary" onclick="updateProblem(<?php echo $problem['problem_id']; ?>);">Update</button>
                    <button type="button" class="btn btn-primary" onclick="deleteProblem(<?php echo $problem['problem_id']; ?>);">Delete</button>
                </div>
            </div>
            <?php
        }
    }

        // Show warranties if sold. 
        if ($vehicle['sold'] == 1) {
            ?>
            <div class='form-row'>
                <div class='col'>
                    <h3>Warranties</h3>
                </div>
            </div>
            <?php
            $statement = $db->prepare("SELECT * FROM Vehicle_Warranty AS vw LEFT JOIN Warranty AS w ON w.warranty_id = vw.warranty_id WHERE vw.vin = ?;");
            $statement->bindParam(1, $vin, PDO::PARAM_STR);
            $statement->execute();
            $warranties = $statement->fetchAll(PDO::FETCH_ASSOC);
            $tid = 1;
            if (count($warranties) == 0) {
                echo "<p>No warranties.</p>";
            } else {
                foreach ($warranties as $warranty) {
                    ?>
                    <div class='form-row'>
                        <div class='col'>
                            <h4>Warranty <?php echo $tid++; ?></h4>
                        </div>
                    </div>
                    <div class='form-row'>
                        <div class='col'>
                            <label>Start Date</label>
                            <input type="date" class="form-control" value="<?php echo date('Y-m-d', strtotime($warranty['start_date'])); ?>" readonly>
                        </div>
                        <div class='col'>
                            <label>End Date</label>
                            <input type="date" class="form-control" value="<?php echo date('Y-m-d', strtotime($warranty['end_date'])); ?>" readonly>
                        </div>
                        <div class='col'>
                            <label>Total Cost</label>
                            <input type="text" class="form-control" value="<?php echo "$" . $warranty['cost']; ?>" readonly>
                        </div>
                        <div class='col'>
                            <label>Deductible</label>
                            <input type="text" class="form-control" value="<?php echo "$" . $warranty['deductible']; ?>" readonly>
                        </div>
                    </div>
                    <div class='form-row'>
                    <?php
                    $statement = $db->prepare("SELECT * FROM Warranty_Items AS wi LEFT JOIN Items AS i ON wi.item_id = i.item_id WHERE wi.warranty_id = ?;");
                    $statement->bindParam(1, $warranty['warranty_id'], PDO::PARAM_INT);
                    $statement->execute();
                    $items = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($items as $item) {
                        ?>
                            <label>Item Covered</label>
                            <input type="text" class="form-control" value="<?php echo $item['description']; ?>" readonly>
                        <?php
                    }
                    ?>
                    </div>
                    <?php
                }
            }
        }

    }
    require_once 'footer.php';
?>