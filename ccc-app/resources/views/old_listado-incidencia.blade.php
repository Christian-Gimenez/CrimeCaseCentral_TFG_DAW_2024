@include('cabecera', ['title' => 'Listado de incidencias'])
@include('navbar')
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

                    @foreach ($incidencias as $incidencia)
                        <tr>
                            <td>{{ $incidencia->id }}</td>
                            <td>{{ $incidencia->id_usuario }}</td>
                            <td>{{ $incidencia->id_crimen }}</td>
                            <td>
                                <a
                                    href="{{ route('leer-crimen-db', ['winterhorn' => session('token'), 'id_crimen' => $incidencia->id_crimen]) }}"><img
                                        src="{{ asset('imgs/compartir-enlace.png') }}" alt="IR AL CRIMEN"></a>
                                <a
                                    href="{{ route('borrar-incidencia', ['winterhorn' => session('token'), 'id_incidencia' => $incidencia->id]) }}"><img
                                        src="{{ asset('imgs/basura.png') }}" alt="Borrar incidencia"></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="paginacion">
                @if ($pagina > 1)
                    <a
                        href="{{ route('get-incidencia', ['winterhorn' => session('token'), 'pagina' => $pagina - 1]) }}">
                        <img class="flecha-paginacion flecha-izq" src="{{ asset('imgs/flecha-correcta.png') }}"
                            alt="">
                    </a>
                @endif

                <span>{{ $pagina }}</span>

                @if (count($incidencias) == $registrosPorPagina)
                    <a
                        href="{{ route('get-incidencia', ['winterhorn' => session('token'), 'pagina' => $pagina + 1]) }}">
                        <img class="flecha-paginacion" src="{{ asset('imgs/flecha-correcta.png') }}" alt="">
                    </a>
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
