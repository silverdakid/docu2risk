let previousValue = ""; // Si on annule l'édition on remets la valeur initiale.
let prevRadio: JQuery<HTMLElement>;

// Input text :
function enableEdit(e: Event, idToEdit: string): void {
    const inputToEdit = $("#" + idToEdit) as JQuery<HTMLInputElement>;
    inputToEdit.prop("disabled", false);
    previousValue = inputToEdit.val() as string;
    inputToEdit.trigger("focus");
    $('.editInputDiv').addClass('hidden');
    $('#div' + idToEdit)[0].innerHTML = '<div class="editInputDiv"><button type="button" name="submitForm" value="' + idToEdit + '" onclick="disableEdit(this, `' + idToEdit + '`, true)" class="editInput editInputCheck"></button></div><div class="editInputDiv"><button type="cancel" value="' + idToEdit + '" id="btnCancel" onclick="disableEdit(this, `' + idToEdit + '`)" class="editInput editInputCross"></button></div>';
}

function disableEdit(th: HTMLButtonElement, idToEdit: string, param?: boolean): void {
    const inputToEdit = $("#" + idToEdit) as JQuery<HTMLInputElement>;
    inputToEdit.prop("disabled", true);
    if (param === undefined) {
        inputToEdit.val(previousValue);
    }
    $('.editInputDiv').removeClass('hidden');
    $('#div' + idToEdit)[0].innerHTML = '<div class="editInputDiv"><button type="button"  onclick="enableEdit(this, `' + idToEdit + '` )" value="' + idToEdit + '" class="editInput editInputEdit"></button></div>';
    if (param !== undefined) {
        $('#' + idToEdit).addClass('answered').removeClass('optional expected ia');
        $('#div' + idToEdit).remove();
    }
}

// Reste :

$('.answers-flex').on('submit', () => {
    $('input').each(function () {
        if ($(this).attr('disabled')) {
            $(this).removeAttr('disabled');
        }
    });
    $('select').each(function () {
        if ($(this).attr('disabled')) {
            $(this).removeAttr('disabled');
        }
    });
});

function replaceButtons(idToEdit: string, func: string, func2: string): void {
    $('.editInputDiv').addClass('hidden');
    $('#div' + idToEdit)[0].innerHTML = '<div class="editInputDiv"><button type="button" name="submitForm" value="' + idToEdit + '" onclick="confirmEdit(this, `' + idToEdit + '`,`' + func2 + '`)" class="editInput editInputCheck"></button></div><div class="editInputDiv"><button type="button" value="' + idToEdit + '" id="btnCancel" onclick="' + func + '" class="editInput editInputCross"></button></div>';
}

function restoreButtons(idToEdit: string, func: string): void {
    $('.editInputDiv').removeClass('hidden');
    let checkable = "";
    const div = $('#div' + idToEdit);
    // Si l'élément au-dessus a la classe optional :
    if (div.prev().hasClass("optional")) checkable = '<div class="editInputDiv"><button type="button" value="' + idToEdit + '" onclick="confirmEdit(this, ' + idToEdit + ', `' + func + '`);" class="editInput editInputCheck"></button></div>';

    const htmlDefault = '<div class="editInputDiv"><button type="button" value="' + idToEdit + '" onclick=' + `"${func}" ` + ' class="editInput editInputEdit"></button></div>';

    div[0].innerHTML = checkable + htmlDefault;
}

// Active un input de type select :
function enableSelect(th: HTMLButtonElement, idToEdit: string): void {
    const selectInput = $("#" + idToEdit) as JQuery<HTMLSelectElement>;
    // On l'active :
    selectInput.prop("disabled", false);
    // On assigne l'ancienne valeur à previousValue :
    previousValue = selectInput.val() as string;
    // On remplace les boutons présents par ceux de confirmation et d'annulation :
    replaceButtons(idToEdit, "restoreSelect(this, " + idToEdit + ")", null);
}

function restoreSelect(th: HTMLButtonElement, idToEdit: string): void {
    const selectInput = $("#" + idToEdit) as JQuery<HTMLSelectElement>;
    selectInput.val(previousValue); // On remets la valeur initial
    // On désactive le select :
    selectInput.prop("disabled", true);
    restoreButtons(idToEdit, "enableSelect(this, " + idToEdit + ")");
}

function enableRadio(th: HTMLButtonElement, idToEdit: string): void {
    // On active tous les radios buttons sélectionnés :
    $('input[name="' + idToEdit + '"]').removeAttr('disabled');
    // On récupère la valeur sélectionnée pour l'historique :
    prevRadio = $('input[name="' + idToEdit + '"]:checked');
    // On remplace les boutons présents par ceux de confirmation et d'annulation :
    replaceButtons(idToEdit, "restoreRadio(this, " + idToEdit + ")", 'enableRadio')
}

function restoreRadio(th: HTMLButtonElement, idToEdit: string): void {
    prevRadio.prop("checked", true);
    // On désactive les radios :
    $('input[name="' + idToEdit + '"]').attr('disabled', 'disabled');
    restoreButtons(idToEdit, "enableRadio(this, " + idToEdit + ")");
}

function confirmEdit(th: HTMLButtonElement, idToEdit: string, func: string): void {
    let input: JQuery<HTMLInputElement>;
    if (func == "enableRadio") {
        input = $('input[name="' + idToEdit + '"]:checked') as JQuery<HTMLInputElement>;
        $('input[name="' + idToEdit + '"]').attr('disabled', 'disabled');
    } else {
        input = $("#" + idToEdit) as JQuery<HTMLInputElement>;
        input.prop("disabled", true);
    }

    restoreButtons(idToEdit, func + "(this, " + idToEdit + ")");

    if (!input || !input.val() || input.val()!.length < 0) {
        return;
    }

    const div = $('#div' + idToEdit);
    div.prev().addClass('answered').removeClass('optional expected ia');
    div.remove();
}

$('.hoverButton').on('click', () => {
    $('.hoverButtonText').toggleClass('absoluteHidden');
});

function loading(): void {
    $('.loading').removeClass('absoluteHidden');
}

