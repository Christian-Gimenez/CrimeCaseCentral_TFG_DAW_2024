@include('cabecera', ['title' => 'Crear crimen'])
@include('navbar')
<main class="main-crear-crimen main">
    <script type="module" src="{{ asset('js/dragNdrop.js') }}"></script>
    <script type="module" src="{{ asset('js/validarFormularios.js') }}"></script>
    <form class="form-crear-crimen" action="{{ url('/crear-crimen-db?winterhorn=' . session('token')) }}" method="post"
        enctype="multipart/form-data">
        @csrf
        <header class="header-titulo-boton">
            <h1>CREACIÓN - CRIMEN</h1>
            <input class="boton-imagen" type="submit" name="crear" value="">
        </header>

        <div class="contenedor-inputs-archivador">
            <div class="div-inputs">
                <div class="input-label">
                    <label class="label-gray" for="tituloCrimen">Título del crimen:</label>
                    <input class="input-gray" type="text" name="tituloCrimen" id="tituloCrimen"
                        pattern="[A-Za-z0-9 áéíóúÁÉÍÓÚñÑ]{1,100}"
                        title="El título debe contener entre 1 y 100 caracteres alfanuméricos.">
                </div>

                <div class="input-label">
                    <label class="label-gray" for="fechaDenuncia">Fecha denuncia:</label>
                    <input class="input-gray" type="datetime-local" name="fechaDenuncia" id="fechaDenuncia">
                </div>

                <div class="input-label">
                    <label class="label-gray" for="imagenPortada">Imagen portada:</label>
                    <div class="btn-subir">
                        <span id="nombreArchivo"></span>
                        <label class="" for="imagenPortada">Subir
                            <input class="" type="file" name="imagenPortada" id="imagenPortada">
                        </label>
                    </div>
                </div>

                <div class="input-label">
                    <label class="label-gray" for="tipologia">Tipología crimen:</label>
                    <select class="input-gray" name="tipologia" id="tipologia">
                        @foreach ($tipologias as $tipologia)
                            <option value="{{ $tipologia }}">{{ $tipologia }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="input-label">
                    <label class="label-gray" for="zona">Zona:</label>
                    <input class="input-gray" type="text" name="zona" id="zona" pattern=".{1,1000}"
                        title="La zona debe contener entre 1 y 1000 caracteres.">
                </div>
            </div>

            <div class="archivador-imagenes">
                <label class="label-gray" for="archivadorImagenes">Imágenes de archivo:</label>
                <div class="contenedor-dragNdrop" id="contenedorDragNDrop">
                    <div class="dragNdrop">
                        <input class="" type="file" name="archivadorImagenes[]" id="archivadorImagenes"
                            multiple>
                    </div>
                </div>
            </div>

        </div>

        <div class="contenedor-desc-modus">
            <div class="input-label">
                <label class="label-gray" for="descripcion">Descripción:</label>
                <textarea class="input-gray" type="text" name="descripcion" id="descripcion"
                    title="La descripción debe contener entre 1 y 4000 caracteres."></textarea>
            </div>

            <div class="input-label">
                <label class="label-gray" for="modusOperandi">Modus operandi:</label>
                <textarea class="input-gray" type="text" name="modusOperandi" id="modusOperandi"
                    title="El modus operandi debe contener entre 1 y 4000 caracteres."></textarea>
            </div>
        </div>
    </form>

    @if (request('resultado'))
        @include('ventana-emergente')
    @endif

</main>

@include('footer')
