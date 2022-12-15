// Client side forms check

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

    if (!nameRE.test(name.value)) {
        event.preventDefault();
        wrongName.style.color = "orange";
        wrongName.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Nom invalide (numéros non autorisés, n'oubliez pas les majuscules)";
        email.focus();
        name.focus();
    }
    if (name.validity.valueMissing) {
        event.preventDefault();
        wrongName.style.color = "red";
        wrongName.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Nom requis";
        email.focus();
        name.focus();
    }

    if (!firstNameRE.test(firstName.value)) {
        event.preventDefault();
        wrongFirstName.style.color = "orange";
        wrongFirstName.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Prénom invalide (numéros non autorisés, n'oubliez pas les majuscules)";
        email.focus();
        firstName.focus();
    }
    if (firstName.validity.valueMissing) {
        event.preventDefault();
        wrongFirstName.style.color = "red";
        wrongFirstName.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Prénom requis";
        email.focus();
        firstName.focus();
    }

    if (!emailRE.test(email.value)) {
        event.preventDefault();
        wrongEmail.style.color = "orange";
        wrongEmail.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Email invalide (ex: info_noreply@muse.com)";
        email.focus();
    }
    if (email.validity.valueMissing) {
        event.preventDefault();
        wrongEmail.style.color = "red";
        wrongEmail.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Email requis";
        email.focus();
    }

    if (!passwordRE.test(password.value)) {
        event.preventDefault();
        wrongPassword.style.color = "orange";
        wrongPassword.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Mot de passe invalide (6 caractères, 1 majuscule, 1 minuscule et 1 chiffre minimum)";
        email.focus();
        password.focus();
    }
    if (password.validity.valueMissing) {
        event.preventDefault();
        wrongPassword.style.color = "red";
        wrongPassword.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Mot de passe requis";
        email.focus();
        password.focus();
    }

    if (!phoneRE.test(phone.value)) {
        event.preventDefault();
        wrongPhone.style.color = "orange";
        wrongPhone.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Numéro de téléphone invalide (ex: 0123456789, 01.23.45.67.89, ou +33(0) 123 456 789)";
        email.focus();
        phone.focus();
    }
    if (phone.validity.valueMissing) {
        event.preventDefault();
        wrongPhone.style.color = "red";
        wrongPhone.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Numéro de téléphone requis";
        email.focus();
        phone.focus();
    }

    if (!zipcodeRE.test(registrationZipcode.value)) {
        event.preventDefault();
        console.log(registrationZipcode.value);
        wrongZipcode.style.color = "orange"
        wrongZipcode.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Code postal invalide (ex: 75000)";
        email.focus();
        registrationZipcode.focus();
    }
    if (registrationZipcode.validity.valueMissing) {
        event.preventDefault();
        wrongZipcode.style.color = "red";
        wrongZipcode.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Code postal requis (ex: 75000)";
        email.focus();
        registrationZipcode.focus();
    }

    if (cityList.validity.valueMissing || cityList.value == "") {
        event.preventDefault();
        wrongCity.style.color = "red";
        wrongCity.innerHTML = "<i class='fa-solid fa-circle-exclamation'></i> Ville requise, vérifiez votre code postal";
        email.focus();
        cityList.focus();
    }

    if ((pro_cb.checked==true) && proCompanyName.validity.valueMissing) {
        event.preventDefault();
        wrongProCompanyName.style.color = "red";
        wrongProCompanyName.innerHTML = `<i class='fa-solid fa-circle-exclamation'></i> Raison sociale requise`;
        pro_form.style.display="block";
        email.focus();
        proCompanyName.focus();
    }
    if ((pro_cb.checked==true) && proJobPosition.validity.valueMissing) {
        event.preventDefault();
        wrongProJobPosition.style.color = "red";
        wrongProJobPosition.innerHTML = `<i class='fa-solid fa-circle-exclamation'></i> Poste requis`;
        pro_form.style.display="block";
        email.focus();
        proJobPosition.focus();
    }
    if ((pro_cb.checked==true) && !dunsRE.test(duns.value)) {
        event.preventDefault();
        wrongDuns.style.color = "orange";
        wrongDuns.innerHTML = `<i class='fa-solid fa-circle-exclamation'></i> Numéro invalide : entrée à 9 chiffres (ex: "123456789")`;
        pro_form.style.display="block";
        email.focus();
        duns.focus();
    }
    if ((pro_cb.checked==true) && dunsRE.test(duns.valueMissing)) {
        event.preventDefault();
        wrongDuns.style.color = "red";
        wrongDuns.innerHTML = `<i class='fa-solid fa-circle-exclamation'></i> DUNS requis : entrée à 9 chiffres (ex: "123456789")`;
        pro_form.style.display="block";
        email.focus();
        duns.focus();
    }
}

if (document.getElementById("submit")) {
    document.getElementById("submit").addEventListener("click", checkForm);
}

// Fetch API gouv cities from zipcode
const orderZipcode = document.getElementById("order_address_zipcode");
const addressZipcode = document.getElementById("address_zipcode");
const address1Zipcode = document.getElementById("address1_zipcode");

function toZipcode(zipcode, cityList) {

    const apiUrl = 'https://geo.api.gouv.fr/communes?codePostal='

    fetch(apiUrl+zipcode).then(response=>{

        response.json().then(json => {

            cityList.innerHTML = "";

            for (let i=0; i<json.length; i++) {
                cityList.innerHTML += `<option value="${json[i].nom}">${json[i].nom}</option>`;
            }
        })
    })
}

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



function proSubForm() {

    let pro_cb = document.getElementById("registration_form_pro");
    let pro_form = document.getElementById("proForm");
    let company_name = document.getElementById("registration_form_proCompanyName");
    let duns = document.getElementById("registration_form_proDuns");
    let job = document.getElementById("registration_form_proJobPosition");

        if (pro_cb.checked==true) {
            pro_form.style.display="block";
            pro_cb.scrollIntoView({behavior: "smooth", block: "center", inline: "start"});;
            company_name.setAttribute('required', '');
            duns.setAttribute('required', '');
            job.setAttribute('required', '');
        } else {
            pro_form.style.display="none";
            company_name.removeAttribute('required', '');
            duns.removeAttribute('required', '');
            job.removeAttribute('required', '');
        }
        if (pro_cb.checked==false) {
            pro_form.style.display="none";
            company_name.removeAttribute('required', '');
            duns.removeAttribute('required', '');
            job.removeAttribute('required', '');
        }
    }


function addCoupon() {

    let couponButton = document.getElementById("couponButton");
    let couponInput = document.getElementById("couponInput");

        if (getComputedStyle(couponInput).display != "none") {
            couponInput.style.display = "none";
            CouponButton.scrollIntoView({behavior: "smooth", block: "center", inline: "start"});
        } else {
            couponInput.style.display = "block";
            couponInput.scrollIntoView({behavior: "smooth", block: "center", inline: "start"});
        }
}


function newAddress() {

    let newAddressButton = document.getElementById("newAddressButton");
    let newAddressForm = document.getElementById("newAddressForm");

    let newAddressName = document.getElementById("newAddressName");
    let newAddressCountry = document.getElementById("newAddressCountry");
    let newAddressZipcode = document.getElementById("newAddressZipcode");
    let newAddressCity = document.getElementById("newAddressCity");
    let newAddressPathType = document.getElementById("newAddressPathType");
    let newAddressPathNumber = document.getElementById("newAddressPathNumber");

        if (getComputedStyle(newAddressForm).display != "none") {

            newAddressForm.style.display="none";
            newAddressButton.scrollIntoView({behavior: "smooth", block: "nearest", inline: "nearest"});

            newAddressName.removeAttribute('required', '');
            newAddressCountry.removeAttribute('required', '');
            newAddressZipcode.removeAttribute('required', '');
            newAddressCity.removeAttribute('required', '');
            newAddressPathType.removeAttribute('required', '');
            newAddressPathNumber.removeAttribute('required', '');

        } else {

            newAddressForm.style.display="block";
            newAddressForm.scrollIntoView({behavior: "smooth", block: "nearest", inline: "nearest"});

            newAddressName.setAttribute('required', '');
            newAddressCountry.setAttribute('required', '');
            newAddressZipcode.setAttribute('required', '');
            newAddressCity.setAttribute('required', '');
            newAddressPathType.setAttribute('required', '');
            newAddressPathNumber.setAttribute('required', '');
        }

        newAddressButton.onclick = newAddress();

        if (document.getElementById("newAddressButton")) {
            document.getElementById("newAddressButton").addEventListener("click", newAddress);
    }
}


function payCard() {

    let payCardButton = document.getElementById("payCardButton");
    let payCardForm = document.getElementById("payCardForm");

        if (getComputedStyle(payCardForm).display != "none") {
            payCardForm.style.display = "none";
            payCardButton.scrollIntoView({behavior: "smooth", block: "center", inline: "start"});
        } else {
            payCardForm.style.display = "block";
            payCardForm.scrollIntoView({behavior: "smooth", block: "center", inline: "start"});
        }
}