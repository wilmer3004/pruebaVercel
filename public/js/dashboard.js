// DASHBOARD
var el = document.getElementById("wrapper");
var toggleButton = document.getElementById("menu-toggle");

toggleButton.onclick = function () {
    el.classList.toggle("toggled");
};

// Desplegrar los botones de herramientas
document.addEventListener("DOMContentLoaded", function () {
    const dropdownButton = document.getElementById("dropdownButton");
    const items = document.getElementsByClassName("item");

    dropdownButton.addEventListener("click", function () {
        for (var i = 0; i < items.length; i++) {
            items[i].classList.toggle("show");
        }
    });
});

// Desplegrar los botones de horarios
document.addEventListener("DOMContentLoaded", function () {
    const dropdownButton1 = document.getElementById("dropdownButton1");
    const items1 = document.getElementsByClassName("item1");

    dropdownButton1.addEventListener("click", function () {
        for (var i = 0; i < items1.length; i++) {
            items1[i].classList.toggle("show");
        }
    });
});

