let prevValue: string | number | string[]; // Si on annule l'édition on remets la valeur initiale.
function enableEditSettings(th: HTMLElement, idToEdit: string | number | string[]) {
    // th.preventDefault();
    const inputToEdit = $("#" + idToEdit);
    inputToEdit.prop("disabled", false);
    prevValue = inputToEdit.val();
    inputToEdit.trigger("focus");
    $('.editInputDiv').addClass('hidden');
    $('#div' + idToEdit)[0].innerHTML = '<div class="editInputDiv"><input type="submit" name="submitForm" value="' + idToEdit + '" class="editInput editInputCheck"></div><div class="editInputDiv"><button type="cancel" value="' + idToEdit + '" id="btnCancel" onclick="disableEditSettings(this, `' + idToEdit + '`)" class="editInput editInputCross"></button></div>';
}
;
function disableEditSettings(th: HTMLElement, idToEdit: string | number | string[]) {
    // th.preventDefault();
    const inputToEdit = $("#" + idToEdit);
    inputToEdit.prop("disabled", true);
    inputToEdit.val(prevValue);
    $('.editInputDiv').removeClass('hidden');
    $('#div' + idToEdit)[0].innerHTML = '<div class="editInputDiv"><button type="submit"  onclick="enableEdit(this, `' + idToEdit + '` )" value="' + idToEdit + '" class="editInput editInputEdit"></div>';
}
;
// To-do: JQuery -> PureJS.
// - Curseur en fin de chaine