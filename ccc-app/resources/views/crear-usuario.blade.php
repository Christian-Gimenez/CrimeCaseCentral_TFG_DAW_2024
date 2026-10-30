@include('cabecera', ['title' => 'Listado de usuarios'])
@include('navbar')
<style>
    @media screen and (max-width: 1000px) {
        .especial {
            background-color: #D9D9D9;
        }
    }
</style>
<main class="main-crear-usuario main">
    <script type="module" src="{{ asset('js/validarFormularios.js') }}"></script>
    <form class="form-crear-usuario" action="{{ url('/crear-usuario-db?winterhorn=' . session('token')) }}" method="post">
        @csrf
        <header class="header-titulo-boton">
            <h1>CREACIÓN - USUARIO</h1>
            <input class="boton-imagen" type="submit" name="crear" value="">
        </header>

        <div class="div-inputs">
            <div class="input-label">
                <label class="label-white" for="nombre">NOMBRE:</label>
                <input class="input-white" type="text" name="nombre" id="nombre"
                    pattern="[A-Za-z áéíóúÁÉÍÓÚñÑ]{1,45}" title="El nombre debe tener entre 1 y 45 letras alfabéticas.">
            </div>
            <div class="input-label">
                <label class="label-white" for="contrasena">CONTRASEÑA:</label>
                <div class="password">
                    <input class="input-white" type="password" name="contrasena" id="contrasena"
                        data-ocultar-texto="true" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!#$%&*_-]).{8,255}"
                        title="La contraseña debe tener al menos 8 caracteres, incluyendo al menos una letra mayúscula, una letra minúscula, un número y un carácter especial (! # $ % & * _ -). Máximo 255 caracteres.">
                    <div class="mostrarPassword"></div>
                </div>
            </div>
            <div class="input-label">
                <label class="label-white" for="permisos">PERMISOS:</label>
                <select class="input-white" name="permisos" id="permisos">
                    <option value="10">10 - Lector</option>
                    <option value="20">20 - Editor</option>
                    <option value="30">30 - Moderador</option>
                    <option value="40">40 - Administrador</option>
                    <option value="50">50 - Super Administrador</option>
                </select>
            </div>
            <div class="input-label">
                <label class="label-white" for="creado">CREADO:</label>
                <input class="input-white input-disabled" type="date" name="creado" id="creado"
                    value="{{ date('Y-m-d') }}" disabled>
            </div>
            <div class="input-label">
                <label class="label-white" for="actualizado">ACTUALIZADO:</label>
                <input class="input-white input-disabled" type="date" name="actualizado" id="actualizado"
                    value="{{ date('Y-m-d') }}" disabled>
            </div>
        </div>
    </form>


    @if (request('resultado'))
        @include('ventana-emergente')
    @endif

</main>

@include('footer')
