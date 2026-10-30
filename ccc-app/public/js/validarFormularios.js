import { crearApendarNodos } from "./app.js";

// Función para crear y mostrar la ventana de error
function mostrarVentanaError(mensaje) {
    const bloquearPantalla = crearApendarNodos(document.body, 'div', { class: 'bloquear-pantalla' });
    const ventana = crearApendarNodos(bloquearPantalla, 'div', { class: 'ventana' });

    const headerVentana = crearApendarNodos(ventana, 'header', { class: 'header-ventana' });
    crearApendarNodos(headerVentana, 'h5', {}, 'ERROR');
    const btnCerrar = crearApendarNodos(headerVentana, 'button', { class: 'btn-x' }, '✕');

    const bodyVentana = crearApendarNodos(ventana, 'div', { class: 'body-ventana' });
    crearApendarNodos(bodyVentana, 'h4', {}, 'Validación Fallida');
    const figura = crearApendarNodos(bodyVentana, 'figure', { class: 'texto-ventana' });
    crearApendarNodos(figura, 'img', { class: 'img-ventana', src: './imgs/error.png' });
    crearApendarNodos(figura, 'figcaption', {}, mensaje);

    const btnOk = crearApendarNodos(bodyVentana, 'button', { class: 'btn-ok btn-login' }, 'OK');

    btnCerrar.addEventListener('click', () => bloquearPantalla.remove());
    btnOk.addEventListener('click', () => bloquearPantalla.remove());
}

function validarFormulario(event) {
    event.stopPropagation();
    const form = event.target;
    let esValido = true;
    let mensajeError = '';

    Array.from(form.elements).forEach(element => {
        if (element.type === 'textarea') {

            if (element.value.length < 1 || element.value.length > 4000) {
                esValido = false;
                mensajeError = element.title || 'El texto debe contener entre 1 y 4000 caracteres.';
            }
        } else if (element.pattern && !new RegExp(element.pattern).test(element.value)) {
            esValido = false;
            mensajeError = element.title;
        }
    });

    if (!esValido) {
        event.preventDefault();
        mostrarVentanaError(mensajeError);
        return false;
    }

    return true;
}

function mostrarPassword(event) {
    event.preventDefault();
    const inputActual = document.querySelector("[data-ocultar-texto]");
    const clase = inputActual.classList.value;
    const name = inputActual.name;
    const id = inputActual.id;
    const title = inputActual.title;
    const password = inputActual.value;
    const pattern = inputActual.pattern;
    let inputNuevo;
    let ocultarTexto = inputActual.dataset.ocultarTexto;
    let type = "";
    if(ocultarTexto == "true") {
        type = "text";
        ocultarTexto = "false";
        event.target.style.backgroundImage = "url('../imgs/visible.png')";
    } else {
        type = "password";
        ocultarTexto = "true";
        event.target.style.backgroundImage = "url('../imgs/ojo.png')";
    }

    if (pattern) {
        inputNuevo = crearApendarNodos(null, "input", {
            type: type,
            "data-ocultar-texto": ocultarTexto,
            class: clase,
            name: name,
            pattern: pattern,
            id: id,
            title: title,
            value: password
        }, "");
    } else {
        inputNuevo = crearApendarNodos(null, "input", {
            type: type,
            "data-ocultar-texto": ocultarTexto,
            class: clase,
            name: name,
            id: id,
            title: title,
            value: password
        }, "");
    }

    inputActual.parentElement.insertBefore(inputNuevo, inputActual);
    inputActual.parentElement.removeChild(inputActual);
}


window.validarFormulario = validarFormulario;

document.querySelectorAll('form:not(.buscador)').forEach(form => {
    if (form.getAttribute('enctype') !== 'multipart/form-data') {
        form.addEventListener('submit', (evento) => {
            evento.stopPropagation();
            const esFormularioValido = validarFormulario(evento);
            if (esFormularioValido) {
                enviarFormJSON(evento, form);
            }
        });
    }
});

const campoPassword = document.querySelector('.mostrarPassword');
if(campoPassword) {
    campoPassword.addEventListener("click", mostrarPassword);
}
