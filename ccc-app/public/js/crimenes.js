import { crearApendarNodos } from "./app.js";

document.querySelectorAll(".card-crimen").forEach(card => {
    card.style.cursor = "pointer";
    let enlace = card.querySelector(".txt-crimen > a").getAttribute("href");
    card.addEventListener("click", function(event) {
        event.stopPropagation();
        location.href = enlace;
    })
});
