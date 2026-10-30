@include('cabecera', ['title' => 'Listado de usuarios'])
@include('navbar', ['title' => 'USUARIOS'])
<main class="main main-listado-usuarios">
    <section class="listado-usuarios">

        <header class="header-titulo-boton">
            <h1>USUARIOS</h1>
            <a href="{{ route('crear-usuario', ['winterhorn' => session('token')]) }}"><img class="a-imagen"
                    src="{{ asset('imgs/agregar-usuario.png') }}" alt="Logo crear usuario"></a>
        </header>
        @if (count($usuarios) >= 1)
            <table class="table-usuarios">
                <thead>
                    <tr>
                        <th>USUARIO</th>
                        <th>PERMISOS</th>
                        <th>FECHA CREACIÓN</th>
                        <th>FECHA ACTUALIZACIÓN</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <script>
                        const usuarios = @json($usuarios);
                    </script>
                    <script type="module" src="{{ asset('js/usuarios.js') }}"></script>
                </tbody>
            </table>
            <div class="paginacion">
                @if ($pagina > 1)
                    @if (substr(url()->current(), 21, 6) == 'buscar')
                        <a
                            href="{{ route('buscador', ['winterhorn' => session('token'), 'pagina' => $pagina - 1 , 'registrosPorPagina' => $registrosPorPagina, 'totalPaginas' => $totalPaginas, 'buscar' => $buscar, 'dondeBuscar' => parse_url(url()->current(), PHP_URL_PATH)]) }}">
                            <img class="flecha-paginacion flecha-izq" src="{{ asset('imgs/flecha-correcta.png') }}"
                                alt="">
                        </a>
                    @else
                        <a
                            href="{{ route('get-usuario', ['winterhorn' => session('token'), 'pagina' => $pagina - 1]) }}">
                            <img class="flecha-paginacion flecha-izq" src="{{ asset('imgs/flecha-correcta.png') }}"
                                alt="">
                        </a>
                    @endif

                @endif

                <span>{{ $pagina }}</span>
                @if ($pagina < $totalPaginas)
                    @if (substr(url()->current(), 21, 6) == 'buscar')
                        <a
                            href="{{ route('buscador', ['winterhorn' => session('token'), 'pagina' => $pagina + 1, 'registrosPorPagina' => $registrosPorPagina, 'totalPaginas' => $totalPaginas, 'buscar' => $buscar , 'dondeBuscar' => parse_url(url()->current(), PHP_URL_PATH)]) }}">
                            <img class="flecha-paginacion" src="{{ asset('imgs/flecha-correcta.png') }}"
                                alt="">
                        </a>
                    @else
                        <a
                            href="{{ route('get-usuario', ['winterhorn' => session('token'), 'pagina' => $pagina + 1]) }}">
                            <img class="flecha-paginacion" src="{{ asset('imgs/flecha-correcta.png') }}"
                                alt="">
                        </a>
                    @endif
                @endif
            </div>
        @else
            <h4 class="table-incidencias">No existen usuarios actualmente...</h4>
        @endif
    </section>

    @if (request('borrado'))
        @include('ventana-emergente')
    @endif
</main>

@include('footer')
