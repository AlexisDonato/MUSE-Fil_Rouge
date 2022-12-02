// Client side forms check

function checkForm(event) {

    let name = document.querySelector(".LastNameField");
    let wrongName = document.getElementById('wrongName');
    let nameRE = new RegExp(/^[A-Z][a-zàéèêëîïôöûüùç.]+([ -][A-Z][a-zàéèêëîïôöûüùç.])*/);

    let firstName = document.querySelector(".FirstNameField");
    let wrongFirstName = document.getElementById("wrongFirstName");
    let firstNameRE = new RegExp(/^[A-Z][a-zàéèêëîïôöûüùç.]+([ -][A-Z][a-zàéèêëîïôöûüùç.])*/);

    let email = document.querySelector(".EmailField");
    let wrongEmail = document.getElementById("wrongEmail");
    let emailRE = new RegExp(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,3}))$/);

    let password = document.querySelector(".password-field");
    let wrongPassword = document.getElementById("wrongPassword");
    let passwordRE = new RegExp(/^\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/);

    let phone = document.querySelector(".PhoneField");
    let wrongPhone = document.getElementById("wrongPhone");
    let phoneRE = new RegExp(/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/); // French phone numbers

    let pro_cb =document.getElementById("registration_form_pro");

    let proCompanyName = document.getElementById("registration_form_proCompanyName");
    let wrongProCompanyName = document.getElementById("wrongProCompanyName");

    let proJobPosition = document.getElementById("registration_form_proJobPosition");
    let wrongProJobPosition = document.getElementById("wrongProJobPosition");

    let duns = document.getElementById("registration_form_proDuns");
    let wrongDuns = document.getElementById("wrongDuns");
    let dunsRE = new RegExp(/^[0-9]{9}$/);

    let registrationZipcode = document.getElementById("registration_form_address_zipcode");
    let wrongZipcode = document.getElementById('wrongZipcode');
    let zipcodeRE = new RegExp(/^[0-9]{5}$/);

    wrongFirstName.textContent = "";
    wrongName.textContent = "";
    wrongEmail.textContent = "";
    wrongPassword.textContent = "";
    wrongPhone.textContent = "";
    wrongProCompanyName.textContent = "";
    wrongDuns.textContent = "";
    wrongProJobPosition.textContent = "";
    wrongZipcode = "";

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
        wrongZipcode.style.color = "orange";
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


function toZipcode(zipcode, dataList) {

    const apiUrl = 'https://geo.api.gouv.fr/communes?codePostal='

    fetch(apiUrl+zipcode).then(response=>{

        response.json().then(json => {

            dataList.innerHTML = "";
            
            for (let i=0; i<json.length; i++) {
                dataList.innerHTML += `<option value="${json[i].nom}">`;
            }
        })
    })
}
const cityList = document.getElementById('cityList');
const registrationZipcode = document.getElementById("registration_form_address_zipcode")
registrationZipcode.addEventListener("keyup", () => {
    toZipcode(registrationZipcode.value, cityList);
});




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