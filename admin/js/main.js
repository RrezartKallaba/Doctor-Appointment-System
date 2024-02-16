function toggleMenu() {
    var menu = document.getElementById("myLinks");
    var iconHamburger = document.querySelector(".openbtn");
    var iconClose = document.querySelector(".closebtn");

    if (menu.style.display === "block") {
        menu.style.display = "none";
        iconHamburger.style.display = "block";
        iconClose.style.display = "none";
    } else {
        menu.style.display = "block";
        iconHamburger.style.display = "none";
        iconClose.style.display = "block";
    }
}
window.addEventListener("resize", () => {
    var menu = document.getElementById("myLinks");
    var iconHamburger = document.querySelector(".openbtn");
    var iconClose = document.querySelector(".closebtn");

    if (window.innerWidth > 850) {
        menu.style.display = "inline";
        iconHamburger.style.display = "none";
        iconClose.style.display = "none";
    } else {
        menu.style.display = "none";
        iconHamburger.style.display = "block";
        iconClose.style.display = "none";
    }
});