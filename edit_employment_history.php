<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
?>
<form action='edit_employment_history.php' method='post'>
    <div class="form-row">
        <div class="col">
            <h2>Employment History</h2>
        </div>
    </div>
    <div class="form-row">
        <div class="col">
            <label for="customerID">Select Customer</label>
            <select class="form-control" id="customerID">
            <option selected>Choose</option>
            <?php
                foreach ($db->query("SELECT first_name, last_name, tax_id FROM Customer") as $row) {
                    $name_string = $row['first_name'] . ' ' . $row['last_name'] . ' ' . $row['tax_id'];
                    echo '<option value=' . $row['tax_id'] . '>' . $name_string . '</option>';
                }
            ?>
            </select>
        </div>
    </div>
    <div id='employeeFormSink'>
        <div class="form-row">
            <div class="col">
                <label for="employer1">Employer</label>
                <input type="text" class="form-control" name="employer1" id="employer1" placeholder="Enter your employer">
            </div>
            <div class="col">
                <label for="title1">Title</label>
                <input type="text" class="form-control" name="title1" id="title1" placeholder="Enter your title">
            </div>
            <div class="col">
                <label for="supervisor1">Supervisor</label>
                <input type="text" class="form-control" name="supervisor1" id="supervisor1" placeholder="Enter your supervisor">
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <label for="employerPhone1">Employer Phone</label>
                <input type="tel" class="form-control" name="employerPhone1" id="employerPhone1" placeholder="Enter your employer's phone number">
            </div>
            <div class="col">
                <label for="employerAddress1">Employer Address</label>
                <input type="text" class="form-control" name="employerAddress1" id="employerAddress1" placeholder="Enter your employer's address">
            </div>
            <div class="col">
                <label for="startDate1">Start Date</label>
                <input type="date" class="form-control" name="startDate1" id="startDate1" placeholder="Enter the day you started working">
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col">
            <button type="button" class="btn btn-primary" onclick="growEmploymentHistoryForm()">Add another employment entry</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>
<?php
    } else if ($method == "POST") {
        $db->exec("BEGIN TRANSACTION;");

        $tax_id = $_POST['customerID'];

        for ($i = 1; key_exists('employer' . $i, $_POST); $i++) {
            $employer = $_POST['employer' . $i];
            $title = $_POST['title' . $i];
            $supervisor = $_POST['supervisor' . $i];
            $employerPhone = $_POST['employerPhone' . $i];
            $employerAddress = $_POST['employerAddress' . $i];
            $startDate = $_POST['startDate' . $i];

            $statement = $db->prepare("CALL addEmploymentHistoryEntry(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->bindParam(1, $tax_id, PDO::PARAM_INT);
            $statement->bindParam(2, $employer, PDO::PARAM_STR);
            $statement->bindParam(3, $title, PDO::PARAM_STR);
            $statement->bindParam(4, $supervisor, PDO::PARAM_STR);
            $statement->bindParam(5, $employerPhone, PDO::PARAM_STR);
            $statement->bindParam(6, $employerAddress, PDO::PARAM_STR);
            $statement->bindParam(7, $startDate, PDO::PARAM_STR);
            $statement->execute();
        }

        $db->exec("COMMIT;");
    }
    require_once 'footer.php';
?>