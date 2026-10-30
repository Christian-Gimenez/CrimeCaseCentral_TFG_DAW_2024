<nav class="navbar">
    <script type="module" src="{{ asset('js/menu.js') }}"></script>
    <figure>
        <img src="{{ asset('imgs/LOGO_DEFINITIVO.png') }}" alt="Logotipo Crime Case Central" id="imagen_inicial_navbar">
        <img src="{{ asset('imgs/Logo-Texto_CCC.png') }}" alt="Logo Crime Case Central">
        @if (!empty($title))
            <h1 id="titulo_movil">{{ $title }}</h1>
        @endif
        <button class="boton_menu_movil @if (empty($title)) margin-left @endif"><img
                src="{{ asset('imgs/menu.png') }}" alt="menu" class=""></button>
    </figure>
    <div id="menu" class="menu_slide menu_slide_oculto">
        @if (substr(url()->current(), 21, 3) == 'get' || substr(url()->current(), 21, 6) == 'buscar')
            <form method="post" action="{{ url('/buscador?winterhorn=' . session('token')) }}" class="buscador">
                @csrf
                @php
                    $dondeBuscar = parse_url(url()->current(), PHP_URL_PATH);
                @endphp
                <input type="text" name="buscar"
                    @if ($dondeBuscar == '/get-crimen' || $dondeBuscar == '/buscar-crimen') placeholder="Buscar crimen"
                    @elseif ($dondeBuscar == '/get-usuario' || $dondeBuscar == '/buscar-usuario')
                        placeholder="Buscar usuario"
                    @elseif ($dondeBuscar == '/get-incidencia' || $dondeBuscar == '/buscar-incidencia')
                        placeholder="Buscar incidencia" @endif><input
                    type="submit" value="">
                <input type="hidden" name="dondeBuscar" value="{{ $dondeBuscar }}">
                <input type="hidden" name="pagina" value="{{ $pagina }}">
                <input type="hidden" name="registrosPorPagina" value="{{ $registrosPorPagina }}">
                <input type="hidden" name="totalPaginas" value="{{ $totalPaginas }}">
            </form>
        @endif

        <ul>
            <li><a href="{{ route('get-crimen', ['winterhorn' => session('token')]) }}">CRIMENES</a></li>
            @if (session('ver') == "todo")
                <li><a href="{{ route('get-usuario', ['winterhorn' => session('token')]) }}">USUARIOS</a></li>
                <li><a href="{{ route('get-incidencia', ['winterhorn' => session('token')]) }}">INCIDENCIAS</a></li>
            @endif


            <li class="salir"><a href="{{ route('login') }}"><img class="salir" src="{{ asset('imgs/salida-de-emergencia.png') }}"
                        alt="Logo salir"></a></li>
            <li class="imagen_menu_slide"><img src="{{ asset('imgs/LOGO_DEFINITIVO.png') }}" alt=""></li>
        </ul>
    </div>
</nav>
