<script>
    function deleteEmploymentHistory(eh_id) {
        $.ajax({
            url: "employment_history.php",
            type: "DELETE",
            data: { 
                employment_history_id: eh_id
            },
            success: function(data, status, xhr) {
                document.getElementById("history-" + eh_id).remove();
                showSnackbar("Successfully deleted employment history!");
            },
            error: function(jqXhr, textStatus, errorMessage) {
                showSnackbar(vagueError);
            }
        });
    }
</script>
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

        $statement = $db->prepare("SELECT * FROM Customer_EmploymentHistory AS ce LEFT JOIN EmploymentHistory AS e ON ce.employment_history_id = e.employment_history_id WHERE ce.tax_id = ? ORDER BY e.start_date DESC;");
        $statement->bindParam(1, $tax_id, PDO::PARAM_INT);
        $statement->execute();
        $histories = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
    <table class="table">
        <thead>
            <tr>
            <th scope="col">Start Date</th>
            <th scope="col">Employer</th>
            <th scope="col">Title</th>
            <th scope="col">Supervisor</th>
            <th scope="col">Phone</th>
            <th scope="col">Address</th>
            <th scope="col">Operations</th>
            </tr>
        </thead>
        <tbody>
<?php
        foreach ($histories as $history) {
            ?>
            <tr id="history-<?php echo $history['employment_history_id']; ?>">
                <th scope="row"><?php echo date('Y-m-d', strtotime($history['start_date'])); ?></th>
                <td><?php echo $history['employer']; ?></td>
                <td><?php echo $history['title']; ?></td>
                <td><?php echo $history['supervisor']; ?></td>
                <td><?php echo $history['phone']; ?></td>
                <td><?php echo $history['address']; ?></td>
                <td><button type="button" class="btn btn-primary" onclick="deleteEmploymentHistory(<?php echo $history['employment_history_id']; ?>)">Delete</button></td>
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