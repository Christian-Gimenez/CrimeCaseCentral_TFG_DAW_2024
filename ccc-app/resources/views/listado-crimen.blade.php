@include('cabecera', ['title' => 'Listado de crímenes'])
@include('navbar', ['title' => 'CRIMENES'])
<main class="main main-listado-crimenes">
    <script type="module" src="{{ asset('js/crimenes.js') }}"></script>
    <section class="listado-crimenes">

        <header class="header-titulo-boton">
            <h1>CRIMENES</h1>
            @if (session('ver') != 'lector' && session('ver') != 'moderador')
                <a href="{{ route('crear-crimen', ['winterhorn' => session('token')]) }}"><img class="a-imagen"
                        src="{{ asset('imgs/agregar-crimen.png') }}" alt="Logo crear crimen"></a>
            @endif
        </header>

        @if (count($crimenes) >= 1)
            <section class="contenedor-crimenes">
                @foreach ($crimenes as $crimen)
                    <article class="card-crimen" id="{{ $crimen->id }}" name="{{ $crimen->id }}">
                        <img class="img-portada-crimen" src="{{ asset('storage/' . $crimen->imagen_portada) }}"
                            alt="">
                        <div class="txt-crimen">
                            <a
                                href="{{ route('leer-crimen-db', ['winterhorn' => session('token'), 'id_crimen' => $crimen->id]) }}">
                                <h3>Título Crimen: {{ Str::headline($crimen->titulo) }}</h3>
                            </a>
                            <p><strong>Fecha denuncia: </strong>{{ Str::headline($crimen->fecha_denuncia) }}</p>
                            <p><strong>Tipología del crimen: </strong>{{ Str::headline($crimen->tipologia) }}</p>
                            <p><strong>Zona: </strong>{{ Str::headline($crimen->zona) }}</p>
                            <p><strong>Descripción: </strong>
                                @if (strlen($crimen->descripcion) < 400)
                                    {{ $crimen->descripcion }}
                                @else
                                    {{ Str::limit($crimen->descripcion, 400) }}
                                    <a
                                        href="{{ route('leer-crimen-db', ['winterhorn' => session('token'), 'id_crimen' => $crimen->id]) }}">
                                        Leer más</a>
                                @endif
                            </p>
                        </div>

                        @if (session('ver') == 'todo' || session('ver') == 'moderador')
                            <figure class="boton-incidencia">
                                <a
                                    href="{{ route('crear-incidencia-db', ['winterhorn' => session('token'), 'id_crimen' => $crimen->id, 'redactor' => $crimen->id_usuario]) }}"><img
                                        class="a-imagen" src="{{ asset('imgs/advertencia (2).png') }}"
                                        alt="Crear incidencia sobre el crimen"></a>
                            </figure>
                        @endif
                    </article>
                @endforeach
            </section>

            <div class="paginacion">
                @if ($pagina > 1)
                    @if (substr(url()->current(), 21, 6) == 'buscar')
                        <a
                            href="{{ route('buscador', ['winterhorn' => session('token'), 'pagina' => $pagina - 1, 'registrosPorPagina' => $registrosPorPagina, 'totalPaginas' => $totalPaginas, 'buscar' => $buscar, 'dondeBuscar' => parse_url(url()->current(), PHP_URL_PATH)]) }}">
                            <img class="flecha-paginacion flecha-izq" src="{{ asset('imgs/flecha-correcta.png') }}"
                                alt="">
                        </a>
                    @else
                        <a
                            href="{{ route('get-crimen', ['winterhorn' => session('token'), 'pagina' => $pagina - 1]) }}">
                            <img class="flecha-paginacion flecha-izq" src="{{ asset('imgs/flecha-correcta.png') }}"
                                alt="">
                        </a>
                    @endif

                @endif

                <span>{{ $pagina }}</span>
                @if ($pagina < $totalPaginas)
                    @if (substr(url()->current(), 21, 6) == 'buscar')
                        <a
                            href="{{ route('buscador', ['winterhorn' => session('token'), 'pagina' => $pagina + 1, 'registrosPorPagina' => $registrosPorPagina, 'totalPaginas' => $totalPaginas, 'buscar' => $buscar, 'dondeBuscar' => parse_url(url()->current(), PHP_URL_PATH)]) }}">
                            <img class="flecha-paginacion" src="{{ asset('imgs/flecha-correcta.png') }}"
                                alt="">
                        </a>
                    @else
                        <a
                            href="{{ route('get-crimen', ['winterhorn' => session('token'), 'pagina' => $pagina + 1]) }}">
                            <img class="flecha-paginacion" src="{{ asset('imgs/flecha-correcta.png') }}"
                                alt="">
                        </a>
                    @endif
                @endif
            </div>
        @else
            <h4 class="table-crimenes">No existen crimenes actualmente...</h4>
        @endif

    </section>

    @if (request('ventana'))
        @include('ventana-emergente')
    @endif
</main>


@include('footer')
