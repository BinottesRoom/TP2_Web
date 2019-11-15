// objet d'automatisation de la validation
let validationProvider;
let emailExist = false;

$(document).ready(initUI);

function initUI() {
    initValidation('actorForm');
}

function initValidation(formId) {
    validationProvider = new ValidationProvider(formId);
    validationProvider.addControl("Name", validate_Name);
    validationProvider.addControl("CountrieId", validate_CountrieId);       
    validationProvider.addControl("ImageUploader", validate_ImageUploader);
}

function validate_Name(){
    let TBX = document.getElementById("Name");

    if (TBX.value === "")
        return "Nom est manquant";

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
