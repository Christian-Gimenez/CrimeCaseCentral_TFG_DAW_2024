import { crearApendarNodos } from "./app.js";

const urlParams = new URLSearchParams(window.location.search);
const token = urlParams.get('winterhorn');
const pagina = urlParams.get('pagina') || 1;
const buscar = urlParams.get('buscar');
const registrosPorPagina = urlParams.get('registrosPorPagina');
const totalPaginas = urlParams.get('totalPaginas');


incidencias.forEach(function (incidencia) {
    const tr = crearApendarNodos(document.querySelector('table tbody'), 'tr');

    crearApendarNodos(tr, 'td', {}, incidencia.id);
    crearApendarNodos(tr, 'td', {}, incidencia.id_usuario);
    crearApendarNodos(tr, 'td', {}, incidencia.id_crimen);

    const a = crearApendarNodos(tr, 'td');

    const enlace1 = crearApendarNodos(a, 'a', {
        href: '/leer-crimen-db?winterhorn=' + token + '&id_crimen=' + incidencia.id_crimen
    });
    crearApendarNodos(enlace1, 'img', {
        src: '../imgs/compartir-enlace.png',
        alt: 'IR AL CRIMEN'
    });

    const enlace2 = crearApendarNodos(a, 'a', {
        href: '/borrar-incidencia?winterhorn=' + token + '&id_incidencia=' + incidencia.id
    });
    crearApendarNodos(enlace2, 'img', {
        src: '../imgs/basura.png',
        alt: 'Borrar incidencia'
    });
});

const columnas = {
    'ID': 'id',
    'REDACTOR CRIMEN': 'id_usuario',
    'NOMBRE CRIMEN': 'id_crimen'
};

let ordenActual = {
    columna: urlParams.get('ordenarPor') || 'id',
    direccion: urlParams.get('direccion') || 'asc'
};

document.querySelectorAll('th').forEach((th, i, arr) => {
    th.dataset.orden = columnas[th.textContent];
    if (i < arr.length - 1) {
        th.addEventListener('click', () => {
            if (ordenActual.columna === th.dataset.orden) {
                ordenActual.direccion = ordenActual.direccion === 'asc' ? 'desc' : 'asc';
            } else {
                ordenActual.direccion = 'asc';
            }

            var nuevaUrl = '';
            if (buscar) {
                nuevaUrl = `/get-incidencia?winterhorn=${token}&pagina=${pagina}&buscar=${buscar}&registrosPorPagina=${registrosPorPagina}&totalPaginas=${totalPaginas}&ordenarPor=${th.dataset.orden}&direccion=${ordenActual.direccion}`;

            } else {
                nuevaUrl = `/get-incidencia?winterhorn=${token}&pagina=${pagina}&ordenarPor=${th.dataset.orden}&direccion=${ordenActual.direccion}`;
            }
            window.location.href = nuevaUrl;
        });
    }
});
