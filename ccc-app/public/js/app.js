// import './bootstrap';

/*CREAR Y APENDAR ELEMENTOS DEL DOM */
export function crearApendarNodos(padre, tipoElemento, atributos = {}, texto = '') {
    const elemento = document.createElement(tipoElemento);
    for (const [key, value] of Object.entries(atributos)) {
        elemento.setAttribute(key, value);
    }
    if (texto !== '') {
        const textNode = document.createTextNode(texto);
        elemento.appendChild(textNode);
    }
    if(padre) {
        padre.appendChild(elemento);
    }
    return elemento;
}

/*FUNCION EVENTOS CLICK BOTONES */
function cerrarVentana(evento) {
    document.querySelector(".btn-ok").removeEventListener("click", cerrarVentana);
    document.querySelector(".btn-x").removeEventListener("click", cerrarVentana);
    const ventana = document.querySelector(".bloquear-pantalla");
    ventana.parentElement.removeChild(ventana);
}

const ventana = document.querySelector(".ventana") ?? false;
if(ventana) {
    const botonOk = document.querySelector(".btn-ok");
    const botonX = document.querySelector(".btn-x");
    botonOk.addEventListener("click", cerrarVentana);
    botonX.addEventListener("click", cerrarVentana);
}

