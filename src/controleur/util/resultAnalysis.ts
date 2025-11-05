const buttonElement = document.getElementById("button-show-div") as HTMLButtonElement;
const exportDiv = document.getElementById("export-div") as HTMLDivElement;

const questionDiv = document.getElementById("question-table") as HTMLElement;

buttonElement.addEventListener("click", toggleDetail, false);
function toggleDetail() {
    questionDiv.classList.toggle("absoluteHidden")
    exportDiv.classList.toggle("absoluteHidden")
}