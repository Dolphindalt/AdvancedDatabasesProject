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
    $.ajax({
        url: "seller.php",
        type: "POST",
        data: { 
            seller_tax_id: sellerID,
            name: sellerName
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

// End javascript for the purchases page.

// Javascript for time and dates. 

Date.prototype.toDateInputValue = (function() {
    var local = new Date(this);
    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
    return local.toJSON().slice(0,10);
});

// End of javascript for time and dates. 