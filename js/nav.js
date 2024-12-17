document.addEventListener("DOMContentLoaded", function() {
    fetch("nav.html")
        .then(response => response.text())
        .then(html => {
            document.body.insertAdjacentHTML("afterbegin", html);
            destacarPaginaActual();
        })
        .catch(error => console.error("Error al cargar el navbar:", error));
});

function destacarPaginaActual() {
    const links = document.querySelectorAll("#navbar a");
    const rutaActual = window.location.pathname.split("/").pop();
    console.log(rutaActual); // tabla.html

    links.forEach(link => {
        const href = link.getAttribute("href").split("/").pop();
        if (href === rutaActual) {
            link.classList.add("activo");
        } else {
            link.classList.remove("activo");
        }
    });
}