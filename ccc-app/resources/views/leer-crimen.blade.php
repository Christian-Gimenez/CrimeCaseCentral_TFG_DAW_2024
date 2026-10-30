@include('cabecera', ['title' => 'Lectura de crimen'])
@include('navbar')
<main class="main-leer-crimen main">
    <div class="lectura-crimen">
        @php
            $dirImagenPortada = '/imagenesPortada/' . request('id_crimen');
        @endphp
        @php
            $img = head(Storage::disk('public')->files($dirImagenPortada));
        @endphp
        <header class="header-titulo-boton ">
            <div class="foto-hero"
                style="background-image: url({{ asset('storage/' . $img) }}),linear-gradient(to bottom, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));">
                <h1>{{ Str::upper(request('titulo')) }}</h1>
                <nav>
                    @if (request('redactor') == session('usuario') || session('ver') == 'todo' || session('ver') == 'moderador')
                        <a
                            href="{{ route('borrar-crimen', ['winterhorn' => session('token'), 'id_crimen' => request('id_crimen')]) }}"><img
                                class="a-imagen" src="{{ asset('imgs/basura.png') }}" alt="Borrar crimen"></a>
                        <a
                            href="{{ route('editar-crimen-cargadb', ['winterhorn' => session('token'), 'id_crimen' => request('id_crimen')]) }}"><img
                                class="a-imagen" src="{{ asset('imgs/lapiz.png') }}" alt="Editar crimen"></a>
                    @endif

                    @if (session('ver') == 'todo' || session('ver') == 'moderador')
                        <a
                            href="{{ route('crear-incidencia-db', ['winterhorn' => session('token'), 'id_crimen' => request('id_crimen'), 'redactor' => request('redactor')]) }}"><img
                                class="a-imagen" src="{{ asset('imgs/advertencia (2).png') }}"
                                alt="Crear incidencia sobre el crimen"></a>
                    @endif
                </nav>
            </div>
        </header>
        <section class="contenedor-leer-crimen">
            <div class="datos-crimen">
                <p><strong>Fecha denuncia:</strong> {{ Str::headline(request('fecha_denuncia')) }}</p>
                <p><strong>Tipología de crimen:</strong> {{ Str::headline(request('tipologia')) }}</p>
                <p><strong>Zona:</strong> {{ Str::headline(request('zona')) }}</p>
                <!--SIN API KEY: -->
                    <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d194347.47826828313!2d-3.8443473332188804!3d40.43809861093788!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd422997800a3c81%3A0xc436dec1618c2269!2sMadrid!5e0!3m2!1ses!2ses!4v1717432685925!5m2!1ses!2ses"
                    width="600" height="250" style="border:5px solid darkslategray; border-radius:10px;"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <img src="" alt="">
            </div>

            <div class="archivador">
                @php
                    $directorio = '/archivadorImagenes/' . request('id_crimen');
                @endphp
                @php
                    $archivos = Storage::disk('public')->files($directorio);
                @endphp
                @foreach ($archivos as $archivo)
                    @if (Storage::disk('public')->exists($archivo))
                        <a href="{{ asset('storage/' . $archivo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $archivo) }}" alt="">
                        </a>
                    @endif
                @endforeach
                @if (empty($archivos))
                    <h3>No existen imagenes actualmente...</h3>
                @endif
            </div>


        </section>
        <div class="contenedor-desc-modus">
            <div class="background-txt">
                <h4>Descripción</h4>
                <p>{{ request('descripcion') }}</p>
            </div>
            <div class="background-txt">
                <h4>Modus Operandi</h4>
                <p>{{ request('modus_operandi') }}</p>
            </div>
        </div>
    </div>

    @if (request('resultado'))
        @include('ventana-emergente')
    @endif
</main>


@include('footer')
