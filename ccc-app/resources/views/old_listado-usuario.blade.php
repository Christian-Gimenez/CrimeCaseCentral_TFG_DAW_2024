@include('cabecera', ['title' => 'Listado de usuarios'])
@include('navbar')
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
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->nombre }}</td>
                            <td>{{ $usuario->permisos }}</td>
                            <td>{{ $usuario->creado }}</td>
                            <td>{{ $usuario->actualizado }}</td>
                            <td>
                                <a
                                    href="{{ route('editar-usuario-cargadb', ['winterhorn' => session('token'), 'id_usuario' => $usuario->id]) }}"><img
                                        src="{{ asset('imgs/lapiz.png') }}" alt="Editar usuario"></a>
                                <a
                                    href="{{ route('borrar-usuario', ['winterhorn' => session('token'), 'id_usuario' => $usuario->id]) }}"><img
                                        src="{{ asset('imgs/basura.png') }}" alt="Borrar usuario"></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="paginacion">
                @if ($pagina > 1)
                    <a href="{{ route('get-usuario', ['winterhorn' => session('token'), 'pagina' => $pagina - 1]) }}">
                        <img class="flecha-paginacion flecha-izq" src="{{ asset('imgs/flecha-correcta.png') }}"
                            alt="">
                    </a>
                @endif

                <span>{{ $pagina }}</span>

                @if (count($usuarios) == $registrosPorPagina)
                    <a href="{{ route('get-usuario', ['winterhorn' => session('token'), 'pagina' => $pagina + 1]) }}">
                        <img class="flecha-paginacion" src="{{ asset('imgs/flecha-correcta.png') }}" alt="">
                    </a>
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
