<?php
    require_once 'header.php';
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "GET")
    {
?>
<form>
    <div class="form-row">
        <h2>Employment History</h2>
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
                <input type="text" class="form-control" name="employerPhone1" id="employerPhone1" placeholder="Enter your employer's phone number">
            </div>
            <div class="col">
                <label for="employerAddress1">Employer Address</label>
                <input type="text" class="form-control" name="employerAddress1" id="employerAddress1" placeholder="Enter your employer's address">
            </div>
            <div class="col">
                <label for="startDate1">Start Date</label>
                <input type="text" class="form-control" name="startDate1" id="startDate1" placeholder="Enter the day you started working">
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col">
            <button type="button" class="btn btn-primary" onclick="growEmploymentHistoryForm()">Add another employment entry</button>
        </div>
    </div>
</form>
<?php
    }
    require_once 'footer.php';
?>