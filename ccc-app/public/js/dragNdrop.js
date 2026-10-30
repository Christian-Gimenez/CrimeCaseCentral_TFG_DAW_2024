import { crearApendarNodos } from "./app.js";

let archivosSeleccionados = [];
let imagenesEliminadas = [];

const formulario = document.querySelector("form:not(.buscador)");
formulario.addEventListener("submit", (evento) => { enviarFormJSON(evento, formulario) });
const contenedorDragNDrop = document.getElementById('contenedorDragNDrop');
document.getElementById('archivadorImagenes').addEventListener("click", (event) => event.preventDefault());

contenedorDragNDrop.addEventListener('dragover', (e) => {
    e.preventDefault();
    contenedorDragNDrop.classList.add('dragover');
});

contenedorDragNDrop.addEventListener('dragleave', () => {
    contenedorDragNDrop.classList.remove('dragover');
});

contenedorDragNDrop.addEventListener('drop', (e) => {
    e.preventDefault();
    contenedorDragNDrop.classList.remove('dragover');
    const files = e.dataTransfer.files;
    handleFiles(files);
});


function handleFiles(files) {
    const dragNdrop = document.querySelector('.dragNdrop');
    for (const file of files) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const figure = crearApendarNodos(dragNdrop, 'figure');
            const img = crearApendarNodos(figure, 'img', {
                src: e.target.result,
                class: "preview-img"
            });

            img.addEventListener('click', () => {
                dragNdrop.removeChild(figure);
                archivosSeleccionados = archivosSeleccionados.filter(archivo => archivo.id !== file.name);
                if (file.existing) {
                    imagenesEliminadas.push(file.name);
                }
            });

        img.addEventListener('mouseover', () => {
           img.src = "../imgs/basura.png";
           img.style.backgroundColor = "#FFCCCB";
        })

        img.addEventListener("mouseout", () => {
           img.src = e.target.result;
        })

            document.querySelector(".contenedor-dragNdrop").style = "overflow-y: scroll;";
        }
        reader.readAsDataURL(file);
        archivosSeleccionados.push({ id: file.name, file: file });
    }
}



function enviarFormJSON(event) {
    event.preventDefault();

    const esFormularioValido = window.validarFormulario(event);
    if (!esFormularioValido) {
        return;
    }

    const datosForm = new FormData(formulario);

    for (let i = 0; i < archivosSeleccionados.length; i++) {
        datosForm.append(`archivadorImagenes[${i}]`, archivosSeleccionados[i].file);
    }

    for (let i = 0; i < imagenesEliminadas.length; i++) {
        datosForm.append(`imagenesEliminadas[${i}]`, imagenesEliminadas[i]);
    }

    const tokenCSRF = datosForm.get('_token');
    datosForm.delete('_token');

    fetch(formulario.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': tokenCSRF,
        },
        body: datosForm,
    }).then(response => {
        if (response.ok) {
            return response.json();
        } else {
            throw new Error("Error en la solicitud: " + response.statusText);
        }
    }).then(json => {
        window.location.href = json.redirect_url;

    }).catch(error => {
        console.error("Error al procesar la solicitud:", error);
    });
}

window.onload = function () {
    const imagenes = document.querySelectorAll('.preview-img');

    imagenes.forEach(e => {
        var old_src = e.src;
        e.addEventListener('mouseover', () => {
            e.src = "../imgs/basura.png";
            e.style.backgroundColor = "#FFCCCB";
        },
        e.addEventListener("mouseout", () => {
            e.src = old_src;
        })
        )
    })

    const botonesEliminar = document.querySelectorAll('.a-imagen');

    imagenes.forEach((imagen, index) => {
        const filename = imagen.getAttribute('data-filename');
        const file = { id: imagen.src, file: new File([imagen.src], filename, { type: 'image/png' }), existing: true, name: filename };
        archivosSeleccionados.push(file);


        imagen.addEventListener('click', () => {
            imagen.parentNode.parentNode.removeChild(imagen.parentNode);
            archivosSeleccionados = archivosSeleccionados.filter(archivo => archivo !== file);
            if (file.existing) {
                imagenesEliminadas.push(file.name);
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', (event) => {
    const inputImagenPortada = document.getElementById('imagenPortada');
    const spanNombreArchivo = document.getElementById('nombreArchivo');

    inputImagenPortada.addEventListener('change', function () {
        const nombreArchivo = this.files[0].name;
        const nombreCortado = nombreArchivo.length > 30 ? nombreArchivo.substring(0, 27) + '...' : nombreArchivo;
        spanNombreArchivo.textContent = nombreCortado;
    });
});


document.addEventListener('DOMContentLoaded', (event) => {
    const inputImagenPortada = document.getElementById('imagenPortada');
    const spanNombreArchivo = document.getElementById('nombreArchivo');

    inputImagenPortada.addEventListener('change', function () {
        if (this.files.length > 0) {
            const nombreArchivo = this.files[0].name;
            const nombreCortado = nombreArchivo.length > 30 ? nombreArchivo.substring(0, 27) + '...' : nombreArchivo;
            spanNombreArchivo.textContent = nombreCortado;
        }
    });
});
