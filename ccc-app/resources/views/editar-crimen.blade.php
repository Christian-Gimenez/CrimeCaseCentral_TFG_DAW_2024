@include('cabecera', ['title' => 'Edición del crimen'])
@include('navbar')
<main class="main-crear-crimen main">
    <script type="module" src="{{ asset('js/dragNdrop.js') }}"></script>
    <script type="module" src="{{ asset('js/validarFormularios.js') }}"></script>
    <form class="form-crear-crimen" action="{{ url('/editar-crimen-db?winterhorn=' . session('token')) }}" method="post"
        enctype="multipart/form-data">
        @csrf
        <header class="header-titulo-boton">
            <h1>EDICIÓN - CRIMEN</h1>
            <input class="boton-imagen imagen-edicion" type="submit" name="crear" value="">
        </header>

        <div class="contenedor-inputs-archivador">
            <div class="div-inputs">
                <input type="hidden" hidden name="id_crimen" value="{{ request('id_crimen') }}">
                <div class="input-label">
                    <label class="label-gray" for="tituloCrimen">Título del crimen:</label>
                    <input class="input-gray" type="text" name="tituloCrimen" id="tituloCrimen"
                        value="{{ request('tituloCrimen') }}" pattern="[A-Za-z0-9 áéíóúÁÉÍÓÚñÑ]{1,100}"
                        title="El título debe contener entre 1 y 100 caracteres alfanuméricos.">
                </div>

                <div class="input-label">
                    <label class="label-gray" for="fechaDenuncia">Fecha denuncia:</label>
                    <input class="input-gray" type="datetime-local" name="fechaDenuncia" id="fechaDenuncia"
                        value="{{ request('fecha_denuncia') }}">
                </div>

                <div class="input-label">
                    <label class="label-gray" for="imagenPortada">Imagen portada:</label>
                    <div class="btn-subir">
                        <span id="nombreArchivo">{{ basename(request('imagen_portada')) }}</span>
                        <label class="" for="imagenPortada">Subir
                            <input class="" type="file" name="imagenPortada" id="imagenPortada">
                        </label>
                    </div>
                </div>

                <div class="input-label">
                    <label class="label-gray" for="tipologia">Tipología del crimen:</label>
                    <select class="input-gray" name="tipologia" id="tipologia">
                        @foreach (request("tipologias") as $tipologia)
                            @if($tipologia === request("tipologia"))
                                <option selected value="{{ $tipologia }}">{{ $tipologia }}</option>
                            @else
                                <option value="{{ $tipologia }}">{{ $tipologia }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="input-label">
                    <label class="label-gray" for="zona">Zona:</label>
                    <input class="input-gray" type="text" name="zona" id="zona" value="{{ request('zona') }}"
                        pattern=".{1,1000}" title="La zona debe contener entre 1 y 1000 caracteres.">
                </div>
            </div>

            <div class="archivador-imagenes">
                <label class="label-gray" for="archivadorImagenes">Imágenes de archivo:</label>
                <div class="contenedor-dragNdrop" id="contenedorDragNDrop">
                    <div class="dragNdrop">
                        <input class="" type="file" name="archivadorImagenes[]" id="archivadorImagenes"
                            multiple>
                        @php
                            $directorio = '/archivadorImagenes/' . request('id_crimen');
                        @endphp
                        @if (Storage::disk('public')->exists($directorio))
                            @php
                                $archivos = Storage::disk('public')->files($directorio);
                            @endphp
                            @foreach ($archivos as $archivo)
                                @if (Storage::disk('public')->exists($archivo))
                                    <figure>
                                        <img src="{{ asset('storage/' . $archivo) }}" class="preview-img"
                                            id="{{ basename($archivo) }}" data-filename="{{ basename($archivo) }}" />
                                    </figure>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>




        </div>

        </div>

        <div class="contenedor-desc-modus">
            <div class="input-label">
                <label class="label-gray" for="descripcion">Descripción:</label>
                <textarea class="input-gray" type="text" name="descripcion" id="descripcion"
                    title="La descripción debe contener entre 1 y 4000 caracteres.">{{ trim(request('descripcion')) }}</textarea>
            </div>

            <div class="input-label">
                <label class="label-gray" for="modusOperandi">Modus operandi:</label>
                <textarea class="input-gray" type="text" name="modusOperandi" id="modusOperandi"
                    title="El modus operandi debe contener entre 1 y 4000 caracteres.">{{ trim(request('modus_operandi')) }}</textarea>
            </div>
        </div>
    </form>

    @if (request('resultado'))
        @include('ventana-emergente')
    @endif

</main>

@include('footer')
