let bodyTableau: HTMLTableSectionElement = document.querySelector('[id=listAnalysis]');
let inputFilter: HTMLInputElement = document.querySelector('[id=filter]')

let bodyTableauAdmin: HTMLTableSectionElement = document.querySelector('[id=listAdmin]');
let inputFilterAdmin: HTMLInputElement = document.querySelector('[id=filterAdmin]')

let bodyTableauMembers: HTMLTableSectionElement = document.querySelector('[id=listMembers]');
let inputFilterMembers: HTMLInputElement = document.querySelector('[id=filterMembers]')

if (inputFilter !== null) {
    inputFilter.addEventListener('input', () => {
        fetch('./historyAnalysis.php?val=' + inputFilter.value)
            .then(response => response.text())
            .then(data => {
                bodyTableau.innerHTML = data;
            });
    });
}

if (inputFilterAdmin !== null) {
    inputFilterAdmin.addEventListener('input', () => {
        fetch('./index.php?val=' + inputFilterAdmin.value)
            .then(response => response.text())
            .then(data => {
                bodyTableauAdmin.innerHTML = data;
            });
    });
}

if (inputFilterMembers !== null) {
    inputFilterMembers.addEventListener('input', () => {
        fetch('./members.php?val=' + inputFilterMembers.value)
            .then(response => response.text())
            .then(data => {
                bodyTableauMembers.innerHTML = data;
            });
    });
}