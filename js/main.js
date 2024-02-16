const nav = document.querySelector(".menu");
const navLinks = nav.querySelectorAll("a");

function setActiveNav() {
    const scrollPosition = window.scrollY;
    for (let i = 0; i < navLinks.length; i++) {
        const link = navLinks[i];
        const sectionId = link.getAttribute("href").substring(1);
        const section = document.getElementById(sectionId);
        if (
            section.offsetTop <= scrollPosition &&
            section.offsetTop + section.offsetHeight > scrollPosition
        ) {
            navLinks.forEach((link) => link.classList.remove("active"));
            link.classList.add("active");
        }
    }
}

window.addEventListener("scroll", setActiveNav);