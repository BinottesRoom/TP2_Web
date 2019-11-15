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
}

function validate_Name(){
    let TBX_Name = document.getElementById("Name");

    if (TBX_Name.value === "")
        return "Nom est manquant";

    return "";
}
