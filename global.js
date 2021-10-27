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