var vagueError = "Something went wrong! Please try again.";

function showSnackbar(text) {
    let elmt = document.getElementById("snackbar");
    elmt.className = "show";
    elmt.innerHTML = text;
    setTimeout(() => { elmt.className = elmt.className.replace("show", "hide"); }, 3000);
}

// Cancerscript for the employment history section of the add customer form.

var employmentFormCounter = 2;

function growEmploymentHistoryForm() {
    let wrapperDiv = document.getElementById('employeeFormSink');
    let employmentRecordString = '';
    employmentRecordString += "<div class='form-row'>" +
        "<div class='col'>" +
            "<label for='employer" + employmentFormCounter + "'>Employer</label>" +
            "<input type='text' class='form-control' name='employer" + employmentFormCounter + "' id='employer" + employmentFormCounter + "' placeholder='Enter your employer'>" +
        "</div>" +
        "<div class=\"col\">" +
            "<label for=\"title" + employmentFormCounter + "\">Title</label>" +
            "<input type=\"text\" class=\"form-control\" name=\"title" + employmentFormCounter + "\" id=\"title" + employmentFormCounter + "\" placeholder='Enter your title'>" +
        "</div>" +
        "<div class=\"col\">" +
            "<label for=\"supervisor" + employmentFormCounter + "\">Supervisor</label>" +
            "<input type=\"text\" class=\"form-control\" name=\"supervisor" + employmentFormCounter + "\" id=\"supervisor" + employmentFormCounter + "\" placeholder='Enter your supervisor'>" +
        "</div>" +
    "</div>" +
    "<div class='form-row'>" +
        "<div class='col'>" +
            "<label for='employerPhone" + employmentFormCounter + "'>Employer Phone</label>" +
            "<input type='text' class='form-control' name='employerPhone" + employmentFormCounter + "' id='employerPhone" + 
            employmentFormCounter + "' placeholder=\"Enter your employer's phone number\">" +
        "</div>" + 
        "<div class='col'>" +
            "<label for='employerAddress" + employmentFormCounter + "'>Employer Address</label>" +
            "<input type='text' class='form-control' name='employerAddress" + employmentFormCounter + 
            "' id='employerAddress" + employmentFormCounter + "' placeholder=\"Enter your employer's address\">" +
        "</div>" + 
        "<div class='col'>" + 
            "<label for='startDate" + employmentFormCounter + "'>Start Date</label>" + 
            "<input type='text' class='form-control' name='startDate" + employmentFormCounter + 
            "' id='startDate" + employmentFormCounter + "' placeholder='Enter the day you started working'>" + 
        "</div>" + 
    "</div>";
    employmentFormCounter += 1;
    wrapperDiv.insertAdjacentHTML('beforeend', employmentRecordString);
}

// End of cancerscript for the employment history section of the add customer form.

// Javascript for the purchases page.

function toggleShowEnterSellerSection() {
    let wrapperDiv = document.getElementById('new-seller-sink');
    if (wrapperDiv.style.display == "none") {
        wrapperDiv.style.display = "block";
    } else {
        wrapperDiv.style.display = "none";
    }
}

function sendAddSeller() {
    let sellerName = document.getElementById("newSellerName").value;
    let sellerID = document.getElementById("newSellerTaxID").value;
    let street = document.getElementById("street").value;
    let city = document.getElementById("city").value;
    let state = document.getElementById("state").value;
    let zip = document.getElementById("zip").value;
    $.ajax({
        url: "seller.php",
        type: "POST",
        data: { 
            seller_tax_id: sellerID,
            name: sellerName,
            street: street,
            city: city,
            state: state,
            zip: zip
        },
        success: function(data, status, xhr) {
            let select = document.getElementById("sellerTaxID");
            let newOption = document.createElement("option");
            newOption.text = sellerName;
            newOption.value = sellerID;
            select.appendChild(newOption);
            toggleShowEnterSellerSection();
            document.getElementById("newSellerName").value = "";
            document.getElementById("newSellerTaxID").value = "";
            showSnackbar("Successfully added new seller!");
        },
        error: function(jqXhr, textStatus, errorMessage) {
            showSnackbar(vagueError);
        }
    });
}

var purchaseCounter = 0;

function growPurchaseForm() {
    let wrapperDiv = document.getElementById('purchase-form-sink');
    purchaseCounter += 1;
    let purchaseRecordString = " \
    <div style='background-color: lightgoldenrodyellow;'> \
        <div class='form-row'> \
            <div class='col'> \
                <h2>Purchase " + purchaseCounter + "</h2> \
            </div> \
        </div> \
        <div class='form-row'> \
            <div class='col'> \
                <label for='vin" + purchaseCounter + "'>VIN</label> \
                <input type='text' class='form-control' name='vin" + purchaseCounter + "' id='vin" + purchaseCounter + "'> \
            </div> \
            <div class='col'> \
                <label for='bookPrice" + purchaseCounter + "'>Book Price</label> \
                <input type='text' class='form-control' name='bookPrice" + purchaseCounter + "' id='bookPrice" + purchaseCounter + "'> \
            </div> \
            <div class='col'> \
                <label for='actualPrice" + purchaseCounter + "'>Actual Price</label> \
                <input type='text' class='form-control' name='actualPrice" + purchaseCounter + "' id='actualPrice" + purchaseCounter + "'> \
            </div> \
        </div> \
        <div class='form-row'> \
            <div class='col'> \
                <label for='make" + purchaseCounter + "'>Make</label> \
                <input type='text' class='form-control' name='make" + purchaseCounter + "' id='make" + purchaseCounter + "'> \
            </div> \
            <div class='col'> \
                <label for='model" + purchaseCounter + "'>Model</label> \
                <input type='text' class='form-control' name='model" + purchaseCounter + "' id='model" + purchaseCounter + "'> \
            </div> \
            <div class='col'> \
                <label for='year" + purchaseCounter + "'>Year</label> \
                <input type='text' class='form-control' name='year" + purchaseCounter + "' id='year" + purchaseCounter + "'> \
            </div> \
            <div class='col'> \
                <label for='color" + purchaseCounter + "'>Color</label> \
                <input type='text' class='form-control' name='color" + purchaseCounter + "' id='color" + purchaseCounter + "'> \
            </div> \
            <div class='col'> \
                <label for='miles" + purchaseCounter + "'>Miles</label> \
                <input type='text' class='form-control' name='miles" + purchaseCounter + "' id='miles" + purchaseCounter + "'> \
            </div> \
            <div class='col'> \
                <label for='style" + purchaseCounter + "'>Style</label> \
                <input type='text' class='form-control' name='style" + purchaseCounter + "' id='style" + purchaseCounter + "'> \
            </div> \
            <div class='col'> \
                <label for='condition" + purchaseCounter + "'>Condition</label> \
                <input type='text' class='form-control' name='condition" + purchaseCounter + "' id='condition" + purchaseCounter + "'> \
            </div> \
        </div> \
        <div class='form-row'> \
            <div class='col'> \
                <h3>Vehicle Problems</h3> \
            </div> \
            <div class='col'> \
                <button type='button' class='btn btn-primary' onclick='growVehicleProblems(" + purchaseCounter + ")'>Add Problem</button> \
            </div> \
        </div> \
        <div id='vehicle-problems-sink" + purchaseCounter + "'> \
        </div> \
    </div> \
    ";
    wrapperDiv.insertAdjacentHTML('beforeend', purchaseRecordString);
}

var problemsCountMap = new Map();

function growVehicleProblems(vehicleNumber) {
    let wrapperDiv = document.getElementById('vehicle-problems-sink' + vehicleNumber);
    if (!problemsCountMap.has(vehicleNumber)) {
        problemsCountMap.set(vehicleNumber, 1);
    } else {
        problemsCountMap.set(vehicleNumber, problemsCountMap.get(vehicleNumber) + 1);
    }
    let problemID = "" + vehicleNumber + problemsCountMap.get(vehicleNumber);
    let vehicleProblemsString = " \
    <div id='vehicle-problem" + problemID + "' class='background-color: lightblue'> \
        <div class='form-row'> \
            <div class='col'> \
                <label for='problemDescription" + problemID + "'>Description</label> \
                <input type='text' class='form-control' name='problemDescription" + problemID + "' id='problemDescription" + problemID + "'> \
            </div> \
            <div class='col'> \
                <label for='problemCost" + problemID + "'>Estimated Cost</label> \
                <input type='text' class='form-control' name='problemCost" + problemID + "' id='problemCost" + problemID + "'> \
            </div> \
        </div> \
    </div> \
    ";
    wrapperDiv.insertAdjacentHTML('beforeend', vehicleProblemsString);
}

// End javascript for the purchases page.

// Javascript for time and dates. 

Date.prototype.toDateInputValue = (function() {
    var local = new Date(this);
    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
    return local.toJSON().slice(0,10);
});

// End of javascript for time and dates. 