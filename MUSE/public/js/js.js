// Client side forms check

// Here we define : 1-The input; 2-The span element for wrong entries; 3-The regular expression for the input
const name = document.querySelector(".LastNameField");
const wrongName = document.getElementById('wrongName');
const nameRE = new RegExp(/^[A-Z][a-zàéèêëîïôöûüùç.]+([ -][A-Z][a-zàéèêëîïôöûüùç.])*/);

const firstName = document.querySelector(".FirstNameField");
const wrongFirstName = document.getElementById("wrongFirstName");
const firstNameRE = new RegExp(/^[A-Z][a-zàéèêëîïôöûüùç.]+([ -][A-Z][a-zàéèêëîïôöûüùç.])*/);

const email = document.querySelector(".EmailField");
const wrongEmail = document.getElementById("wrongEmail");
const emailRE = new RegExp(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,3}))$/);

const password = document.querySelector(".password-field");
const wrongPassword = document.getElementById("wrongPassword");
const passwordRE = new RegExp(/^\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/);

const phone = document.querySelector(".PhoneField");
const wrongPhone = document.getElementById("wrongPhone");
const phoneRE = new RegExp(/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/); // French phone numbers

const pro_cb =document.getElementById("registration_form_pro");

const proCompanyName = document.getElementById("registration_form_proCompanyName");
const wrongProCompanyName = document.getElementById("wrongProCompanyName");

const proJobPosition = document.getElementById("registration_form_proJobPosition");
const wrongProJobPosition = document.getElementById("wrongProJobPosition");

const duns = document.getElementById("registration_form_proDuns");
const wrongDuns = document.getElementById("wrongDuns");
const dunsRE = new RegExp(/^[0-9]{9}$/);

const registrationZipcode = document.getElementById("registration_form_address_zipcode");
const wrongZipcode = document.getElementById('wrongZipcode');
const zipcodeRE = new RegExp(/^[0-9]{5}$/);

const cityList = document.getElementById('cityList');
const wrongCity = document.getElementById('wrongCity');


function checkForm(event) {

    // Clearing the wrong spans text contents
    wrongFirstName.textContent = "";
    wrongName.textContent = "";
    wrongEmail.textContent = "";
    wrongPassword.textContent = "";
    wrongPhone.textContent = "";
    wrongProCompanyName.textContent = "";
    wrongDuns.textContent = "";
    wrongProJobPosition.textContent = "";
    wrongZipcode.textContent = "";
    wrongCity.textContent = "";

    // Checks the name input
    if (!nameRE.test(name.value)) {
        // If the name is invalid, prevents the form from being submitted and displays an error message
        event.preventDefault();
        wrongName.style.color = "orange";
        wrongName.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Nom invalide (numéros non autorisés, n'oubliez pas les majuscules)";
        // Sends the window focus on the name input without being stuck behind the navbar
        email.focus();
        name.focus();
    }
    if (name.validity.valueMissing) {
        // If the name is missing, prevents the form from being submitted and displays an error message
        event.preventDefault();
        wrongName.style.color = "red";
        wrongName.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Nom requis";
        // Sends the window focus on the name input without being stuck behind the navbar
        email.focus();
        name.focus();
    }

    // Checks the first name input
    if (!firstNameRE.test(firstName.value)) {
        // If the first name is invalid, prevents the form from being submitted and displays an error message
        event.preventDefault();
        wrongFirstName.style.color = "orange";
        wrongFirstName.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Prénom invalide (numéros non autorisés, n'oubliez pas les majuscules)";
        // Sends the window focus on the first name input without being stuck behind the navbar
        email.focus();
        firstName.focus();
    }
    if (firstName.validity.valueMissing) {
        // If the first name is missing, prevents the form from being submitted and displays an error message
        event.preventDefault();
        wrongFirstName.style.color = "red";
        wrongFirstName.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Prénom requis";
        // Sends the window focus on the first name input without being stuck behind the navbar
        email.focus();
        firstName.focus();
    }

    // Checks the email input
    if (!emailRE.test(email.value)) {
        // If the email is invalid, prevents the form from being submitted and displays an error message
        event.preventDefault();
        wrongEmail.style.color = "orange";
        wrongEmail.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Email invalide (ex: info_noreply@muse.com)";
        // Sends the window focus on the email
        email.focus();
    }
    if (email.validity.valueMissing) {
        // If the email is missing, prevents the form from being submitted and displays an error message
        event.preventDefault();
        wrongEmail.style.color = "red";
        wrongEmail.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Email requis";
        // Sends the window focus on the email
        email.focus();
    }

    // Checks the password input
    if (!passwordRE.test(password.value)) {
        // If the password is invalid, prevents the form from being submitted and displays an error message
        event.preventDefault();
        wrongPassword.style.color = "orange";
        wrongPassword.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Mot de passe invalide (6 caractères, 1 majuscule, 1 minuscule et 1 chiffre minimum)";
        // Sends the window focus on the password input without being stuck behind the navbar
        email.focus();
        password.focus();
    }
    if (password.validity.valueMissing) {
        // If the password is missing, prevents the form from being submitted and displays an error message
        event.preventDefault();
        wrongPassword.style.color = "red";
        wrongPassword.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Mot de passe requis";
        // Sends the window focus on the password input without being stuck behind the navbar
        email.focus();
        password.focus();
    }

    // Checcks the phone input
    if (!phoneRE.test(phone.value)) {
        // If the phone is invalid, prevents the form from being submitted and displays an error message
        event.preventDefault();
        wrongPhone.style.color = "orange";
        wrongPhone.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Numéro de téléphone invalide (ex: 0123456789, 01.23.45.67.89, ou +33(0) 123 456 789)";
        // Sends the window focus on the phone input without being stuck behind the navbar
        email.focus();
        phone.focus();
    }
    if (phone.validity.valueMissing) {
        // If the phone is missing, prevents the form from being submitted and displays an error message
        event.preventDefault();
        wrongPhone.style.color = "red";
        wrongPhone.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Numéro de téléphone requis";
        // Sends the window focus on the phone input without being stuck behind the navbar
        email.focus();
        phone.focus();
    }

    // Checks the zipcode input
    if (!zipcodeRE.test(registrationZipcode.value)) {
        // If the zipcode is invalid, prevents the form from being submitted and displays an error message
        event.preventDefault();
        wrongZipcode.style.color = "orange"
        wrongZipcode.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Code postal invalide (ex: 75000)";
        // Sends the window focus on the zipcode input without being stuck behind the navbar
        email.focus();
        registrationZipcode.focus();
    }
    if (registrationZipcode.validity.valueMissing) {
        // If the zipcode is missing, prevents the form from being submitted and displays an error message
        event.preventDefault();
        wrongZipcode.style.color = "red";
        wrongZipcode.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Code postal requis (ex: 75000)";
        // Sends the window focus on the zipcode input without being stuck behind the navbar
        email.focus();
        registrationZipcode.focus();
    }

    // Checks the city list input
    if (cityList.validity.valueMissing || cityList.value == "") {
        // If the city list is missing, prevents the form from being submitted and displays an error message
        event.preventDefault();
        wrongCity.style.color = "red";
        wrongCity.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Ville requise, vérifiez votre code postal";
        // Sends the window focus on the city list input without being stuck behind the navbar
        email.focus();
        cityList.focus();
    }

    // Checks the pro company name if the checkbox is checked
    if ((pro_cb.checked==true) && proCompanyName.validity.valueMissing) {
        // If the company name is missing, prevents the form from being submitted and displays an error message
        event.preventDefault();
        wrongProCompanyName.style.color = "red";
        wrongProCompanyName.innerHTML = `<i class='fa-solid fa-circle-exclamation'></i> Raison sociale requise`;
        // Makes the pro sub form stay open
        pro_form.style.display="block";
        // Sends the window focus on the company name input without being stuck behind the navbar
        email.focus();
        proCompanyName.focus();
    }

    // Checks the pro job position if the checkbox is checked
    if ((pro_cb.checked==true) && proJobPosition.validity.valueMissing) {
        // If the job position is missing, prevents the form from being submitted and displays an error message
        event.preventDefault();
        wrongProJobPosition.style.color = "red";
        wrongProJobPosition.innerHTML = `<i class='fa-solid fa-circle-exclamation'></i> Poste requis`;
        // Makes the pro sub form stay open
        pro_form.style.display="block";
        // Sends the window focus on the job position input without being stuck behind the navbar
        email.focus();
        proJobPosition.focus();
    }

    // Checks the pro duns if the checkbox is checked
    if ((pro_cb.checked==true) && !dunsRE.test(duns.value)) {
        // If the duns is invalid, prevents the form from being submitted and displays an error message
        event.preventDefault();
        wrongDuns.style.color = "orange";
        wrongDuns.innerHTML = `<i class='fa-solid fa-circle-exclamation'></i> Numéro invalide : entrée à 9 chiffres (ex: "123456789")`;
        // Makes the pro sub form stay open
        pro_form.style.display="block";
        // Sends the window focus on the duns input without being stuck behind the navbar
        email.focus();
        duns.focus();
    }
    if ((pro_cb.checked==true) && dunsRE.test(duns.valueMissing)) {
        // If the duns is missing, prevents the form from being submitted and displays an error message
        event.preventDefault();
        wrongDuns.style.color = "red";
        wrongDuns.innerHTML = `<i class='fa-solid fa-circle-exclamation'></i> DUNS requis : entrée à 9 chiffres (ex: "123456789")`;
        // Makes the pro sub form stay open
        pro_form.style.display="block";
        // Sends the window focus on the duns input without being stuck behind the navbar
        email.focus();
        duns.focus();
    }
}

// If the button 'submit' exists
if (document.getElementById("submit")) {
    // Sets up an event listener on the submit button
    document.getElementById("submit").addEventListener("click", checkForm);
}

// Fetch API gouv cities from zipcode
const orderZipcode = document.getElementById("order_address_zipcode");
const addressZipcode = document.getElementById("address_zipcode");
const address1Zipcode = document.getElementById("address1_zipcode");

// Define a function that takes a zip code and a city list element
// and populates the city list element with cities from the French government's geo API
function toZipcode(zipcode, cityList) {

    // URL for the French government's geo API
    const apiUrl = 'https://geo.api.gouv.fr/communes?codePostal='

    // Make a GET request to the API with the provided zip code using the 'fetch' method
    fetch(apiUrl+zipcode).then(response=>{

        // When the response is received, parse it as JSON
        response.json().then(json => {

            // Clear the city list element
            cityList.innerHTML = "";

            // Add an option for each city in the response to the city list element (dropdown selection)
            for (let i=0; i<json.length; i++) {
                cityList.innerHTML += `<option value="${json[i].nom}">${json[i].nom}</option>`;
            }
        })
        // Handle errors occurred during JSON parsing
        .catch(error => {
            // Display the error message in French
            wrongCity.innerHTML = "Une erreur s'est produite lors de la récupération des données : " + error;
        });
    })
    // Handle errors occurred during the fetch request
    .catch(error => {
        // Display the error message in French
        wrongCity.innerHTML = "Une erreur s'est produite lors de la requête : " + error;
    });
}

// Sets up event listeners on the corresponding input elements
if (registrationZipcode) {registrationZipcode.addEventListener("keyup", () => {
    toZipcode(registrationZipcode.value, cityList);
});
}
if (orderZipcode) {
    orderZipcode.addEventListener("keyup", () => {
        toZipcode(orderZipcode.value, cityList);
    });
}
if (addressZipcode) {
    addressZipcode.addEventListener("keyup", () => {
        toZipcode(addressZipcode.value, cityList);
    });
}
if (address1Zipcode) {
    address1Zipcode.addEventListener("keyup", () => {
        toZipcode(address1Zipcode.value, cityList);
    });
}


// Function to toggle the visibility of the pro form
function proSubForm() {

    // Gets the checkbox and form elements
    let pro_cb = document.getElementById("registration_form_pro");
    let pro_form = document.getElementById("proForm");
    let company_name = document.getElementById("registration_form_proCompanyName");
    let duns = document.getElementById("registration_form_proDuns");
    let job = document.getElementById("registration_form_proJobPosition");

        // If the checkbox is checked
        if (pro_cb.checked==true) {
            // Shows the pro form
            pro_form.style.display="block";
            // Scrolls the checkbox into view
            pro_cb.scrollIntoView({behavior: "smooth", block: "center", inline: "start"});
            // Sets the required attribute on the input elements
            company_name.setAttribute('required', '');
            duns.setAttribute('required', '');
            job.setAttribute('required', '');
        } else {
            // Hide the pro form
            pro_form.style.display="none";
            // Remove the required attribute from the input elements
            company_name.removeAttribute('required', '');
            duns.removeAttribute('required', '');
            job.removeAttribute('required', '');
        }
        // If the checkbox is not checked
        if (pro_cb.checked==false) {
            // Hide the pro form
            pro_form.style.display="none";
            // Remove the required attribute from the input elements
            company_name.removeAttribute('required', '');
            duns.removeAttribute('required', '');
            job.removeAttribute('required', '');
        }
    }


// Function to toggle the visibility of the coupon input field
function addCoupon() {

    // Gets the button and input elements
    let couponButton = document.getElementById("couponButton");
    let couponInput = document.getElementById("couponInput");

        // If the input field is not hidden
        if (getComputedStyle(couponInput).display != "none") {
            // Hides the input field
            couponInput.style.display = "none";
            // Scrolls the button into view
            CouponButton.scrollIntoView({behavior: "smooth", block: "center", inline: "start"});
        } else {
            // Shows the input field
            couponInput.style.display = "block";
            // Scrolls the input field into view
            couponInput.scrollIntoView({behavior: "smooth", block: "center", inline: "start"});
        }
}


// Function to toggle the visibility of the new address form
function newAddress() {

    // Gets the button and form elements
    let newAddressButton = document.getElementById("newAddressButton");
    let newAddressForm = document.getElementById("newAddressForm");

    let newAddressName = document.getElementById("newAddressName");
    let newAddressCountry = document.getElementById("newAddressCountry");
    let newAddressZipcode = document.getElementById("newAddressZipcode");
    let newAddressCity = document.getElementById("newAddressCity");
    let newAddressPathType = document.getElementById("newAddressPathType");
    let newAddressPathNumber = document.getElementById("newAddressPathNumber");

        // If the form is not hidden
        if (getComputedStyle(newAddressForm).display != "none") {

            // Hides the form
            newAddressForm.style.display="none";
            // Scrolls the button into view
            newAddressButton.scrollIntoView({behavior: "smooth", block: "nearest", inline: "nearest"});

            // Removes the required attribute from the input elements
            newAddressName.removeAttribute('required', '');
            newAddressCountry.removeAttribute('required', '');
            newAddressZipcode.removeAttribute('required', '');
            newAddressCity.removeAttribute('required', '');
            newAddressPathType.removeAttribute('required', '');
            newAddressPathNumber.removeAttribute('required', '');

        } else {

            // Shows the form
            newAddressForm.style.display="block";
            // Scrolls the form into view
            newAddressForm.scrollIntoView({behavior: "smooth", block: "nearest", inline: "nearest"});

            // Sets the required attribute on the input elements
            newAddressName.setAttribute('required', '');
            newAddressCountry.setAttribute('required', '');
            newAddressZipcode.setAttribute('required', '');
            newAddressCity.setAttribute('required', '');
            newAddressPathType.setAttribute('required', '');
            newAddressPathNumber.setAttribute('required', '');
        }

        // Attaches the newAddress function as an event listener to the button
        newAddressButton.onclick = newAddress();

        // If the newAddressButton element exists
        if (document.getElementById("newAddressButton")) {
            // Attaches the newAddress function as an event listener to the button
            document.getElementById("newAddressButton").addEventListener("click", newAddress);
    }
}


// Function to toggle the visibility of the pay with card form
function payCard() {

    // Gets the button and form elements
    let payCardButton = document.getElementById("payCardButton");
    let payCardForm = document.getElementById("payCardForm");

        // If the form is not hidden
        if (getComputedStyle(payCardForm).display != "none") {
            // Hides the form
            payCardForm.style.display = "none";
            // Scrolls the button into view
            payCardButton.scrollIntoView({behavior: "smooth", block: "center", inline: "start"});
        } else {
            // Shows the form
            payCardForm.style.display = "block";
            // Scrolls the form into view
            payCardForm.scrollIntoView({behavior: "smooth", block: "center", inline: "start"});
        }
}