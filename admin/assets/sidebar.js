const hamburger = document.getElementById("hamburger");
const navDrawer = document.getElementById("navDrawer");

//HAMBURGER ONCLICK
hamburger.addEventListener("click", () => {
    if (navDrawer.style.display == "none" || navDrawer.style.display == "") {
        navDrawer.style.display = "inline-block";
    } else {
        navDrawer.style.display = "none";
    }
});

//ACTIVE NAV-LINK HIGHLIGHTING
const navLink = document.querySelectorAll(".navDrawer li");
console.log(navLink);

navLink.forEach(link => {
    const currPage = window.location.pathname;

    //Add active class to current page Link
    const Link = link.querySelector('a');
    const LinkAddress = link.querySelector("a").href;
    if (LinkAddress.endsWith(currPage)) {
        Link.classList.add('active');
    }
});

