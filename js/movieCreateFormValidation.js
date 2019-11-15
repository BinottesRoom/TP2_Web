// objet d'automatisation de la validation
let validationProvider;

$(document).ready(initUI);

function initUI() {
    initValidation('movieForm');
}

function initValidation(formId) {
    validationProvider = new ValidationProvider(formId);
    validationProvider.addControl("Title", validate_Title);
    validationProvider.addControl("CountrieId", validate_CountrieId);       
    validationProvider.addControl("Synopsis", validate_Synopsis);    
    validationProvider.addControl("Year", validate_Year);
    validationProvider.addControl("Author", validate_Author);
    validationProvider.addControl("StyleId", validate_StyleId);    
    validationProvider.addControl("ImageUploader", validate_ImageUploader);
}

function validate_Title(){
    let TBX = document.getElementById("Title");

    if (TBX.value === "")
        return "Titre est manquant";

    return "";
}

function validate_Author(){
    let TBX = document.getElementById("Author");

    if (TBX.value === "")
        return "Auteur est manquant";

    return "";
}

function validate_Synopsis(){
    let TBX = document.getElementById("Synopsis");

    if (TBX.value === "")
        return "Synopsis est manquant";

    return "";
}

function validate_Year() {
    let regex =/\b(19[0-8][0-9]|199[0-9]|2[01][0-9]{2}|3000)\b/;
    let TBX = document.getElementById("Year");
    if (TBX.value === "")
        return "Année manquante";

    if (!regex.test(TBX.value))
        return "Année invalide";

    return "";
}

function validate_ImageUploader(){
    let TBX_Name = document.getElementById("ImageUploader");

    if (TBX_Name.value === "")
        return "La photo est manquante";

    return "";
}

function validate_CountrieId() {
    let cbx = document.getElementById("CountrieId");
    if (cbx.value === "0")
        return "Choix du pays manquant";

    return "";
}

function validate_StyleId() {
    let cbx = document.getElementById("StyleId");
    if (cbx.value === "0")
        return "Choix du style manquant";

    return "";
}