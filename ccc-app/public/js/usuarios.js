import { crearApendarNodos } from "./app.js";

const urlParams = new URLSearchParams(window.location.search);
const token = urlParams.get('winterhorn');
const pagina = urlParams.get('pagina') || 1;
const buscar = urlParams.get('buscar');
const registrosPorPagina = urlParams.get('registrosPorPagina');
const totalPaginas = urlParams.get('totalPaginas');

usuarios.forEach(function(usuario) {
    const tr = crearApendarNodos(document.querySelector('table tbody'), 'tr');

    crearApendarNodos(tr, 'td', {}, usuario.nombre);
    crearApendarNodos(tr, 'td', {}, usuario.permisos);
    crearApendarNodos(tr, 'td', {}, usuario.creado);
    crearApendarNodos(tr, 'td', {}, usuario.actualizado);

    const td = crearApendarNodos(tr, 'td');

    const enlace1 = crearApendarNodos(td, 'a', {
        href: '/editar-usuario-cargadb?winterhorn=' + token + '&id_usuario=' + usuario.id
    });
    crearApendarNodos(enlace1, 'img', {
        src: '../imgs/lapiz.png',
        alt: 'Editar usuario'
    });

    const enlace2 = crearApendarNodos(td, 'a', {
        href: '/borrar-usuario?winterhorn=' + token + '&id_usuario=' + usuario.id
    });
    crearApendarNodos(enlace2, 'img', {
        src: '../imgs/basura.png',
        alt: 'Borrar usuario'
    });
});

const columnas = {
    'USUARIO': 'nombre',
    'PERMISOS': 'permisos',
    'FECHA CREACIÓN': 'creado',
    'FECHA ACTUALIZACIÓN': 'actualizado'
};

let ordenActual = {
    columna: urlParams.get('ordenarPor') || 'nombre',
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
                 nuevaUrl = `/get-usuario?winterhorn=${token}&pagina=${pagina}&buscar=${buscar}&registrosPorPagina=${registrosPorPagina}&totalPaginas=${totalPaginas}&ordenarPor=${th.dataset.orden}&direccion=${ordenActual.direccion}`;
            }else{
                 nuevaUrl = `/get-usuario?winterhorn=${token}&pagina=${pagina}&ordenarPor=${th.dataset.orden}&direccion=${ordenActual.direccion}`;
            }

            window.location.href = nuevaUrl;
        });
    }
});
