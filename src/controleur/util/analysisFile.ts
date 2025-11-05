const inputElement = document.getElementById("docInput") as HTMLInputElement;
const tBodyHTML = document.getElementById("analysisList") as HTMLTableSectionElement;
const filesDivHTML = document.getElementById("filesDiv") as HTMLDivElement;
const fileText = document.getElementById("fileText") as HTMLParagraphElement;
const analysisList = document.getElementById("analysisList") as HTMLTableSectionElement;
const submitAnalysis = document.querySelector("#submitAnalysis") as HTMLButtonElement;
const fileInputsContainer = document.querySelector(".fileInputDiv") as HTMLDivElement;

const arr: string[] = ["Wolfsberg", "ICI", "Invoice"];
let uploadedFiles: File[] = [];

inputElement.addEventListener("change", handleFiles, false);


fileInputsContainer.addEventListener("change", handleFileInput);

function handleFileInput(event: Event) {
    const fileInput = (event.target as HTMLInputElement);

    if (fileInput.files.length > 0) {
        // Crée un nouvel input type file à chaque saisie utilisateur
        const newFileInput = document.createElement("input");
        newFileInput.type = "file";
        newFileInput.name = "docInput[]"; // Array commun à tous les inputs type file concernés
        newFileInput.classList.add("fileInput");
        newFileInput.setAttribute("multiple", "");
        newFileInput.addEventListener("change", handleFiles, false);
        // On cache l'input précédent
        fileInput.classList.add("absoluteHidden");

        // On ajoute le nouvel input tout en haut des éléments de la div
        fileInputsContainer.prepend(newFileInput);
    }
}


function handleFiles(this: HTMLInputElement) {
    const fileList = this.files;

    if (fileList) {
        for (let i = 0; i < fileList.length; i++) {
            uploadedFiles.push(fileList[i]);
        }
        displayFileList();
    }
}

function removeFile(th: HTMLSpanElement, index: number) {
    uploadedFiles.splice(index, 1);
}

function displayFileList() {
    let tbody = "";
    let filesdiv = "";

    for (const [i, file] of uploadedFiles.entries()) {
        tbody += `<tr><td><input onclick="checkChecked()" type="checkbox" name="checkbox[${file.name}]" id="check${i}" class="checkBox"/> `;
        tbody += `<label for="check${i}">${file.name}</label></td>`;
        tbody += `<td>${generateDropdown(arr, file.name)}</td>`;
        filesdiv += `<div id="file${i}"><img src="../vue/css/assets/file.svg" alt="file${i}" height="87px" width="100px"/><p>${file.name}</p></div>`;
    }

    if (uploadedFiles.length > 0) {
        submitAnalysis.classList.toggle("absoluteHidden", false);
        fileText.classList.toggle("top", true);
        fileText.innerText = "INSERT FILES OR DRAG & DROP BELOW";
        filesDivHTML.classList.toggle("absoluteHidden", false);
        tbody = '<thead><tr><th colspan="2">List of files to review</th></tr></thead><tbody id="analysisList"></tbody>' + tbody;
    } else {
        fileText.innerText = "INSERT FILES OR DRAG & DROP";
        fileText.classList.toggle("top", false);
        submitAnalysis.classList.toggle("absoluteHidden", true);
        filesDivHTML.classList.toggle("absoluteHidden", true);
    }

    tBodyHTML.innerHTML = tbody;
    filesDivHTML.innerHTML = filesdiv;
}

// Si aucun fichier n'est sélectionné on désactive l'envoi du formulaire (vérification côté serveur présente).
// L'intérêt est d'éviter à l'utilisateur d'avoir à ré-insérer tous ses fichiers car la page s'actualise après avoir cliqué sur le bouton (et s'il y a une erreur) et les fichiers input se voient retirés
function checkChecked() {
    const checkboxList = document.querySelectorAll('input[type=checkbox]:checked');
    if (checkboxList.length > 1)
        submitAnalysis.disabled = false;
    else
        submitAnalysis.disabled = true;
}

function generateDropdown(array: string[], value: string) {
    value = value.split(".")[0];
    let html = `<select name='type${value}'>`;
    array.forEach(type => {
        html += `<option value="${type.toLowerCase()}" ${(value.toLowerCase().includes(type.toLowerCase()) ? "selected" : "")} >${type}</option>`;
    });
    html += "</select>";

    return html;
}
