const arrow = document.getElementById('arrow-dwn');
const selectBtn = document.getElementById('select-btn');
// Bandeau secondaire
const accTitle = document.getElementById('title');
const accountMenu = document.getElementById('account-menu');

arrow!.addEventListener('click', function () {
    selectBtn!.classList.toggle("open") // Le triangle se retourne vers le sens opposé et le menu s'ouvre
})

accTitle!.addEventListener('click', function () {
    accountMenu!.classList.toggle("open") // Le menu de compte s'ouvre
})