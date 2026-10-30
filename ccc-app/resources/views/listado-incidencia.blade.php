@include('cabecera', ['title' => 'Listado de incidencias'])
@include('navbar', ['title' => 'INCIDENCIAS'])
<main class="main main-listado-incidencias">
    <section class="listado-incidencias">

        <header class="header-titulo-boton">
            <h1>INCIDENCIAS</h1>
        </header>
        @if (count($incidencias) >= 1)
            <table class="table-incidencias">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>REDACTOR CRIMEN</th>
                        <th>NOMBRE CRIMEN</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <script>
                        const incidencias = @json($incidencias);
                    </script>
                    <script type="module" src="{{ asset('js/incidencias.js') }}"></script>

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
                            href="{{ route('get-incidencia', ['winterhorn' => session('token'), 'pagina' => $pagina - 1]) }}">
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
                            href="{{ route('get-incidencia', ['winterhorn' => session('token'), 'pagina' => $pagina + 1]) }}">
                            <img class="flecha-paginacion" src="{{ asset('imgs/flecha-correcta.png') }}"
                                alt="">
                        </a>
                    @endif
                @endif
            </div>
        @else
            <h4 class="table-incidencias">No existen incidencias actualmente...</h4>
        @endif
    </section>
    @if (request('borrado'))
        @include('ventana-emergente')
    @endif
</main>


@include('footer')
