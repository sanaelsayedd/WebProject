const togglebtn = document.querySelector(".toggle-btn");
const togglebtnIcon = document.querySelector(".toggle-btn i");
const dropDownMenu = document.querySelector(".dropdown-menu");

togglebtn.onclick = function () {
    dropDownMenu.classList.toggle("open");
    const isOpen  = dropDownMenu.classList.contains("open")
    togglebtnIcon.classList = isOpen
    ? "fa-solid fa-xmark"
    : "fa-solid fa-bars"
}